<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Config;
use App\Traits\PrimeBankTrait;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    use PrimeBankTrait;

    public function index(Request $request)
    {
        $withdrawals = $request->user()
            ->withdrawals()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $withdrawals
        ]);
    }

    public function store(Request $request)
    {
        $config = Config::getSystemConfig();

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:'.$config->min_withdraw_amount.'|max:'.$config->max_withdraw_amount,
            'pix_key' => 'required|string|max:255',
            'pix_key_type' => 'required|in:cpf,cnpj,email,phone,random',
            'document' => 'required|string|max:14'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if ($user->balance < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo insuficiente para realizar o saque'
            ], 422);
        }
        if($user->isInfluencer()) {
            return response()->json([
                'success' => false,
                'message' => 'Influenciadores não podem sacar'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $withdrawal = $user->withdrawals()->create([
                'amount' => $request->amount,
                'pix_key' => $request->pix_key,
                'pix_key_type' => $request->pix_key_type,
                'document' => $request->document,
                'status' => 'pending'
            ]);

            $user->decrement('balance', $request->amount);

            if ($config->auto_withdraw_enabled && $request->amount <= $config->auto_withdraw_max_amount) {
                try {
                    $result = $this->processPrimeBankWithdrawal($withdrawal);
                    
                    if ($result['success']) {
                        $withdrawal->update([
                            'status' => 'completed',
                            'processed_at' => now(),
                            'transaction_id' => $result['transaction_id'] ?? null
                        ]);
                    } else {
                        \Log::warning('Falha no processamento automático do saque', [
                            'withdrawal_id' => $withdrawal->id,
                            'error' => $result['message'] ?? 'Erro desconhecido'
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Erro no processamento automático do saque', [
                        'withdrawal_id' => $withdrawal->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitação de saque criada com sucesso',
                'data' => $withdrawal
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar o saque',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, Withdrawal $withdrawal)
    {       
        if ($withdrawal->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Não autorizado'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $withdrawal
        ]);
    }

    public function limits()
    {
        $config = Config::getSystemConfig();
        // Log::info(message: $config);

        return response()->json([
            'success' => true,
            'data' => [
                'withdraw' => [
                    'min' => $config->min_withdraw_amount,
                    'max' => $config->max_withdraw_amount
                ]
            ]
        ]);
    }

    /**
     * Processar pagamento via PrimeBank
     */
    private function processPrimeBankWithdrawal(Withdrawal $withdrawal): array
    {
        try {
            $token = $this->getPrimeBankToken();
            
            if (!$token) {
                throw new \Exception('Não foi possível obter token de autenticação');
            }
    
            $cleanedDocument = preg_replace('/\D/', '', $withdrawal->document);
    
            $cleanedPixKey = preg_replace('/\s+/', '', $withdrawal->pix_key);
    
            if (in_array(strtolower($withdrawal->pix_key_type), ['cpf', 'phone'])) {
                $cleanedPixKey = preg_replace('/\D/', '', $cleanedPixKey);
            }
    
            $primeBankPixKeyType = '';
    
            switch (strtolower($withdrawal->pix_key_type)) {
                case 'cpf':
                    $primeBankPixKeyType = 'CPF';
                    break;
                case 'phone':
                    $primeBankPixKeyType = 'TELEFONE';
                    break;
                case 'email':
                    $primeBankPixKeyType = 'EMAIL';
                    break;
                case 'cnpj':
                    $primeBankPixKeyType = 'CNPJ';
                    break;
                case 'evp':
                case 'chave_aleatoria':
                    $primeBankPixKeyType = 'EVP';
                    break;
                default:
                    throw new \Exception('Tipo de chave PIX desconhecido para PrimeBank: ' . $withdrawal->pix_key_type);
                    break;
            }
    
            $response = $this->primeBankRequest('/pix/payment', 'POST', [
                'amount' => (float) $withdrawal->amount,
                'external_id' => (string) $withdrawal->id,
                'payerQuestion' => 'Pagamento de saque',
                'postbackUrl' => config('app.url') . '/api/webhooks/primebank',
                'creditParty' => [
                    'key' => $cleanedPixKey,
                    'keyType' => $primeBankPixKeyType,
                    'document' => $cleanedDocument, 
                    'name' => $withdrawal->user->nomecompleto
                ]
            ]);
    
            if (($response['statusCode'] ?? null) === 200) {
                return [
                    'success' => true,
                    'message' => 'Pagamento processado com sucesso!',
                    'transaction_id' => $response['transactionId'] ?? null
                ];
            }
    
            throw new \Exception($response['message'] ?? 'Erro ao processar pagamento');
    
        } catch (\Exception $e) {
            \Log::error('Erro ao processar pagamento PrimeBank', [
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage()
            ]);
    
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

} 
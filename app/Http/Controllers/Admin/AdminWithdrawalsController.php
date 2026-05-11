<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\User;
use App\Traits\PrimeBankTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class AdminWithdrawalsController extends Controller
{
    use PrimeBankTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Withdrawal::with(['user'])->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('pix_key', 'like', "%{$search}%")
                ->orWhere('document', 'like', "%{$search}%");
            });
        }

        $withdrawals = $query->paginate(15)->through(function ($withdrawal) {
            return [
                'id' => $withdrawal->id,
                'user' => [
                    'id' => $withdrawal->user->id,
                    'name' => $withdrawal->user->name,
                    'email' => $withdrawal->user->email,
                ],
                'amount' => $withdrawal->amount,
                'formatted_amount' => 'R$ ' . number_format($withdrawal->amount, 2, ',', '.'),
                'pix_key' => $withdrawal->pix_key,
                'pix_key_type' => $withdrawal->pix_key_type,
                'document' => $withdrawal->document,
                'status' => $withdrawal->status,
                'reason' => $withdrawal->reason,
                'processed_at' => $withdrawal->processed_at ? $withdrawal->processed_at->format('d/m/Y H:i:s') : null,
                'created_at' => $withdrawal->created_at->format('d/m/Y H:i:s'),
            ];
        });

        return Inertia::render('admin/withdrawals/index', [
            'withdrawals' => $withdrawals,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Withdrawal $withdrawal): Response
    {
        $withdrawal->load('user');

        return Inertia::render('admin/withdrawals/edit', [
            'withdrawal' => [
                'id' => $withdrawal->id,
                'user' => [
                    'id' => $withdrawal->user->id,
                    'name' => $withdrawal->user->name,
                    'email' => $withdrawal->user->email,
                    'balance' => $withdrawal->user->balance,
                ],
                'amount' => $withdrawal->amount,
                'formatted_amount' => 'R$ ' . number_format($withdrawal->amount, 2, ',', '.'),
                'pix_key' => $withdrawal->pix_key,
                'pix_key_type' => $withdrawal->pix_key_type,
                'document' => $withdrawal->document,
                'status' => $withdrawal->status,
                'reason' => $withdrawal->reason,
                'processed_at' => $withdrawal->processed_at ? $withdrawal->processed_at->format('d/m/Y H:i:s') : null,
                'created_at' => $withdrawal->created_at->format('d/m/Y H:i:s'),
            ]
        ]);
    }

    /**
     * Aprovar saque
     */
    public function approve(Request $request, Withdrawal $withdrawal): Response|RedirectResponse
    {
        if (!$withdrawal->isPending()) {
            return back()->withErrors(['error' => 'Este saque não está pendente.']);
        }

        try {
            DB::beginTransaction();

            $result = $this->processPrimeBankWithdrawal($withdrawal);

            if ($result['success']) {
                $withdrawal->markAsCompleted();
                
                DB::commit();
                return redirect()->route('admin.withdrawals.index')->with('success', 'Saque aprovado com sucesso!');
            } else {
                throw new \Exception($result['message'] ?? 'Erro ao processar pagamento.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao aprovar saque', [
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Erro ao aprovar saque: ' . $e->getMessage()]);
        }
    }

    /**
     * Rejeitar saque
     */
    public function reject(Request $request, Withdrawal $withdrawal): Response|RedirectResponse
    {
        if (!$withdrawal->isPending()) {
            return back()->withErrors(['error' => 'Este saque não está pendente.']);
        }

        $request->validate([
            'reason' => 'required|string|max:255'
        ], [
            'reason.required' => 'O motivo da rejeição é obrigatório.'
        ]);

        try {
            DB::beginTransaction();

            $user = $withdrawal->user;
            $user->balance += $withdrawal->amount;
            $user->save();

            $withdrawal->markAsCancelled($request->reason);

            DB::commit();
            return redirect()->route('admin.withdrawals.index')->with('success', 'Saque rejeitado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao rejeitar saque', [
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Erro ao rejeitar saque: ' . $e->getMessage()]);
        }
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


            if ($response['statusCode'] === 200) {
                $withdrawal->update([
                    'transaction_id' => $response['transactionId']
                ]);

                return [
                    'success' => true,
                    'message' => 'Pagamento processado com sucesso!'
                ];
            }

            throw new \Exception($response['message'] ?? 'Erro ao processar pagamento');

        } catch (\Exception $e) {
            Log::error('Erro ao processar pagamento PrimeBank', [
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
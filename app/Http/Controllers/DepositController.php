<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\PrimeBankTrait;
use App\Models\Deposit;
use App\Models\Config;

class DepositController extends Controller
{
    use PrimeBankTrait;

    /**
     * Criar um novo depósito
     */
    public function create(Request $request)
    {
        try {
            $minAmount = Config::getMinDepositAmount();
            $maxAmount = Config::getMaxDepositAmount();

            $validator = Validator::make($request->all(), [
                'amount' => "required|numeric|min:{$minAmount}|max:{$maxAmount}",
            ], [
                'amount.required' => 'O valor é obrigatório',
                'amount.numeric' => 'O valor deve ser um número válido',
                'amount.min' => "O valor mínimo é R$ " . number_format($minAmount, 2, ',', '.'),
                'amount.max' => "O valor máximo é R$ " . number_format($maxAmount, 2, ',', '.'),
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 400);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            $amount = $request->input('amount');

            if ($this->validatePrimeBankConfig()) {
                return $this->createPrimeBankDeposit($user, $amount);
            } else {
                return $this->createMockDeposit($user, $amount);
            }

        } catch (\Exception $e) {
            \Log::error('Erro ao criar depósito: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Criar depósito usando PrimeBank
     */
    private function createPrimeBankDeposit($user, $amount)
    {
        try {
            $externalId = $this->generateExternalId();
            $paymentId = 'pb_' . uniqid();

            $payerData = $this->formatPayerData($user);

            $postbackUrl = url('/api/webhookprimebank');

            $primeBankResponse = $this->createPrimeBankPixQRCode(
                $amount,
                $externalId,
                'Depósito na conta - Raspadinha',
                $postbackUrl,
                $payerData
            );

            $deposit = Deposit::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'description' => 'DEPOSITO',
                'payment_id' => $paymentId,
                'external_id' => $externalId,
                'transaction_id' => $primeBankResponse['transactionId'] ?? null,
                'status' => Deposit::STATUS_PENDING,
                'gateway' => Deposit::GATEWAY_PRIMEBANK,
                'qr_code' => $primeBankResponse['qrcode'] ?? null,
                'expires_at' => now()->addMinutes(30),
                'metadata' => [
                    'primebank_response' => $primeBankResponse,
                    'payer_data' => $payerData
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code gerado com sucesso',
                'data' => [
                    'qr_code' => $primeBankResponse['qrcode'] ?? null,
                    'payment_id' => $paymentId,
                    'transaction_id' => $primeBankResponse['transactionId'] ?? null,
                    'amount' => $amount,
                    'expires_at' => $deposit->expires_at->toISOString(),
                    'status' => $deposit->status,
                    'gateway' => 'primebank'
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao criar depósito PrimeBank: ' . $e->getMessage());
            
            return $this->createMockDeposit($user, $amount);
        }
    }

    /**
     * Criar depósito mock (fallback)
     */
    private function createMockDeposit($user, $amount)
    {
        $paymentId = 'mock_' . uniqid();
        $externalId = $this->generateExternalId('mock_');

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'description' => 'DEPOSITO',
            'payment_id' => $paymentId,
            'external_id' => $externalId,
            'status' => Deposit::STATUS_PENDING,
            'gateway' => Deposit::GATEWAY_MOCK,
            'qr_code' => $this->generateMockQRCode($amount),
            'expires_at' => now()->addMinutes(30),
            'metadata' => [
                'mock' => true,
                'note' => 'Depósito gerado em modo mock - PrimeBank não configurado'
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QR Code gerado com sucesso (modo demonstração)',
            'data' => [
                'qr_code' => $deposit->qr_code,
                'payment_id' => $paymentId,
                'amount' => $amount,
                'expires_at' => $deposit->expires_at->toISOString(),
                'status' => $deposit->status,
                'gateway' => 'mock'
            ]
        ]);
    }

    /**
     * Listar depósitos do usuário
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            $deposits = Deposit::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $deposits
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao listar depósitos: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Verificar status de um depósito
     */
    public function checkStatus(Request $request, $paymentId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }

            $deposit = Deposit::where('payment_id', $paymentId)
                ->where('user_id', $user->id)
                ->first();

            if (!$deposit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Depósito não encontrado'
                ], 404);
            }

            if ($deposit->gateway === Deposit::GATEWAY_PRIMEBANK && $deposit->transaction_id) {
                try {
                    $primeBankStatus = $this->checkPrimeBankTransactionStatus($deposit->transaction_id);
                    
                    if ($primeBankStatus && isset($primeBankStatus['status'])) {
                        $newStatus = null;
                        
                        switch (strtolower($primeBankStatus['status'])) {
                            case 'paid':
                            case 'approved':
                            case 'confirmed':
                                $newStatus = Deposit::STATUS_PAID;
                                break;
                            case 'pending':
                            case 'waiting':
                                $newStatus = Deposit::STATUS_PENDING;
                                break;
                            case 'cancelled':
                            case 'failed':
                            case 'expired':
                                $newStatus = Deposit::STATUS_FAILED;
                                break;
                        }
                        
                        if ($newStatus && $newStatus !== $deposit->status) {
                            $deposit->status = $newStatus;
                            
                            if ($newStatus === Deposit::STATUS_PAID) {
                                $deposit->paid_at = now();
                                
                                $user->increment('balance', $deposit->amount);
                                
                                \Log::info('Depósito aprovado via verificação de status', [
                                    'deposit_id' => $deposit->id,
                                    'user_id' => $user->id,
                                    'amount' => $deposit->amount,
                                    'new_balance' => $user->fresh()->balance
                                ]);
                            }
                            
                            $deposit->save();
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Erro ao verificar status no PrimeBank: ' . $e->getMessage());
                }
            }

            if ($deposit->gateway === Deposit::GATEWAY_MOCK && $deposit->status === Deposit::STATUS_PENDING) {
                $timeSinceCreation = now()->diffInSeconds($deposit->created_at);
                
                if ($timeSinceCreation >= 30) { 
                    $deposit->status = Deposit::STATUS_PAID;
                    $deposit->paid_at = now();
                    
                    $user->increment('balance', $deposit->amount);
                    
                    $deposit->save();
                    
                    \Log::info('Depósito mock aprovado automaticamente', [
                        'deposit_id' => $deposit->id,
                        'user_id' => $user->id,
                        'amount' => $deposit->amount
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'payment_id' => $deposit->payment_id,
                    'status' => $deposit->status,
                    'amount' => $deposit->amount,
                    'gateway' => $deposit->gateway,
                    'created_at' => $deposit->created_at->toISOString(),
                    'expires_at' => $deposit->expires_at?->toISOString(),
                    'paid_at' => $deposit->paid_at?->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao verificar status do depósito: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Gerar um QR code mockado para testes
     */
    private function generateMockQRCode($amount)
    {
        $pixData = [
            'amount' => $amount,
            'recipient' => 'RASPA GREEN LTDA',
            'city' => 'SAO PAULO',
            'txid' => uniqid()
        ];

        return '00020126420014BR.GOV.BCB.PIX0120' . $pixData['txid'] . '52040000530398654' . sprintf('%02d', strlen($amount)) . $amount . '5925' . $pixData['recipient'] . '6009' . $pixData['city'] . '62070503***6304XXXX';
    }

    /**
     * Webhook para receber notificações de pagamento do PrimeBank
     */
    public function webhook(Request $request)
    {
        try {
            $payload = $request->all();
            
            \Log::info('Webhook recebido', ['payload' => $payload]);

            $processed = $this->processPrimeBankWebhook($payload);

            if ($processed) {
                \Log::info('Webhook processado com sucesso');
                return response()->json(['success' => true]);
            } else {
                \Log::warning('Webhook não foi processado corretamente');
                return response()->json(['success' => false, 'message' => 'Webhook não processado'], 422);
            }

        } catch (\Exception $e) {
            \Log::error('Erro no webhook de depósito: ' . $e->getMessage(), [
                'payload' => $payload,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Erro interno'], 500);
        }
    }

    /**
     * Obter configurações de limites para o frontend
     */
    public function getLimits()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'deposit' => [
                        'min' => Config::getMinDepositAmount(),
                        'max' => Config::getMaxDepositAmount()
                    ],
                    'withdraw' => [
                        'min' => Config::getMinWithdrawAmount(),
                        'max' => Config::getMaxWithdrawAmount()
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao obter limites: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
} 
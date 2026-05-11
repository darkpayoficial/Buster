<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $config = Config::first();

        $referrals = User::where('referred_by', $user->referral_code)
            ->count();

        $pendingDeposits = Deposit::whereIn('user_id', function($query) use ($user) {
            $query->select('id')
                ->from('users')
                ->where('referred_by', $user->referral_code);
        })
        ->where('status', Deposit::STATUS_PENDING)
        ->count();

        $totalCommissionEarned = Deposit::where('user_id', $user->id)
            ->where('status', 'paid')
            ->where('description', 'LIKE', '%comissão%')
            ->sum('amount');

        $totalEarned = $totalCommissionEarned + $user->commission_balance;

        return Inertia::render('referral', [
            'user' => $user,
            'config' => $config,
            'stats' => [
                'referrals' => $referrals,
                'pending_deposits' => $pendingDeposits,
                'total_withdrawn' => $totalCommissionEarned,
                'total_earned' => $totalEarned,
                'available_balance' => $user->commission_balance,
            ]
        ]);
    }

    public function generateCode(Request $request)
    {
        Log::info('Iniciando geração de código de referência');
        
        $user = auth()->user();
        Log::info('Usuário:', ['id' => $user->id, 'referral_code' => $user->referral_code]);

        if ($user->referral_code) {
            Log::warning('Tentativa de gerar código para usuário que já possui');
            return back()->with('error', 'Você já possui um código de indicação.');
        }

        try {   
            do {
                $code = Str::random(8);
                Log::info('Tentando código:', ['code' => $code]);
            } while (User::where('referral_code', $code)->exists());

            Log::info('Código único gerado:', ['code' => $code]);

            $user->update([
                'referral_code' => $code
            ]);

            Log::info('Código salvo com sucesso');
            return back()->with('success', 'Código gerado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao gerar código:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao gerar código de indicação.');
        }
    }

    public function withdraw(Request $request)
    {
        $user = auth()->user();
        
        if ($user->commission_balance <= 0) {
            return back()->with('error', 'Saldo insuficiente para retirada.');
        }

        try {
            DB::beginTransaction();

            $externalId = 'COMM-' . Str::random(8);

            Deposit::create([
                'user_id' => $user->id,
                'amount' => $user->commission_balance,
                'gateway' => Deposit::GATEWAY_MOCK,
                'status' => Deposit::STATUS_PAID,
                'description' => 'Transferência de saldo - comissão de indicação',
                'payment_id' => $externalId,
                'external_id' => $externalId,
                'paid_at' => now(),
            ]);

            $user->increment('balance', $user->commission_balance);
            
            $user->update([
                'commission_balance' => 0
            ]);

            DB::commit();

            return back()->with('success', 'Saldo transferido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao transferir saldo:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao processar sua solicitação de transferência.');
        }
    }
} 
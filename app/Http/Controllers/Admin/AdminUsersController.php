<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Config;
use App\Models\JogoHistorico;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomecompleto', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('username', 'like', "%{$request->search}%")
                  ->orWhere('telefone', 'like', "%{$request->search}%")
                  ->orWhere('document', 'like', "%{$request->search}%");
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->status) {
            $query->where('bloqueado', $request->status === 'blocked');
        }

        $sortField = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortOrder);

        $users = $query->paginate(15)->through(function ($user) {
            $referralsCount = 0;
            $totalCommissionEarned = 0;
            
            if ($user->referral_code) {
                $referralsCount = User::where('referred_by', $user->referral_code)->count();
                
                $commissionDeposits = \App\Models\Deposit::where('user_id', $user->id)
                    ->where('status', 'paid')
                    ->where('description', 'LIKE', '%comissão%')
                    ->sum('amount');
                    
                $totalCommissionEarned = $commissionDeposits;
            }
            
            return [
                'id' => $user->id,
                'nomecompleto' => $user->nomecompleto,
                'email' => $user->email,
                'username' => $user->username,
                'telefone' => $user->telefone,
                'document' => $user->document,
                'role' => $user->role,
                'balance' => $user->balance,
                'total_deposit' => $user->total_deposit,
                'total_withdraw' => $user->total_withdraw,
                'total_cashback' => $user->total_cashback,
                'bloqueado' => $user->bloqueado,
                'is_influencer' => $user->is_influencer,
                'referral_code' => $user->referral_code,
                'referral_level' => $user->referral_level,
                'referral_xp' => $user->referral_xp,
                'referred_by' => $user->referred_by,
                'referral_commission' => $user->referral_commission,
                'commission_balance' => $user->commission_balance,
                'referrals_count' => $referralsCount,
                'total_commission_earned' => $totalCommissionEarned,
                'last_login' => $user->last_login,
                'last_ip' => $user->last_ip,
                'last_logout' => $user->last_logout,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        });

        return Inertia::render('admin/users/index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'status', 'sort_by', 'sort_order']),
            'config' => Config::getSystemConfig()
        ]);
    }

    public function edit(User $user)
    {
        $referralsCount = 0;
        $totalCommissionEarned = 0;
        
        if ($user->referral_code) {
            $referralsCount = User::where('referred_by', $user->referral_code)->count();
            
            $commissionDeposits = \App\Models\Deposit::where('user_id', $user->id)
                ->where('status', 'paid')
                ->where('description', 'LIKE', '%comissão%')
                ->sum('amount');
                
            $totalCommissionEarned = $commissionDeposits + $user->commission_balance;
        }

        $userData = [
            'id' => $user->id,
            'nomecompleto' => $user->nomecompleto,
            'email' => $user->email,
            'username' => $user->username,
            'telefone' => $user->telefone,
            'document' => $user->document,
            'role' => $user->role,
            'balance' => $user->balance,
            'total_deposit' => $user->total_deposit,
            'total_withdraw' => $user->total_withdraw,
            'total_cashback' => $user->total_cashback,
            'bloqueado' => $user->bloqueado,
            'is_influencer' => $user->is_influencer,
            'referral_code' => $user->referral_code,
            'referral_level' => $user->referral_level,
            'referral_xp' => $user->referral_xp,
            'referred_by' => $user->referred_by,
            'referral_commission' => $user->referral_commission,
            'commission_balance' => $user->commission_balance,
            'referrals_count' => $referralsCount,
            'total_commission_earned' => $totalCommissionEarned,
            'last_login' => $user->last_login,
            'last_ip' => $user->last_ip,
            'last_logout' => $user->last_logout,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        $gameHistory = JogoHistorico::where('user_id', $user->id)
            ->recent()
            ->limit(20)
            ->get()
            ->map(function ($game) {
                return [
                    'id' => $game->id,
                    'raspadinha_name' => $game->raspadinha_name,
                    'prize_name' => $game->prize_name,
                    'prize_value' => $game->prize_value,
                    'prize_img' => $game->prize_img,
                    'status' => $game->status,
                    'status_text' => $game->status === 'win' ? 'Vitória' : 'Derrota',
                    'status_color' => $game->status === 'win' ? 'success' : 'destructive',
                    'created_at' => $game->created_at->format('d/m/Y H:i'),
                    'created_at_relative' => $game->created_at->diffForHumans(),
                ];
            });

        return Inertia::render('admin/users/edit', [
            'userData' => $userData,
            'gameHistory' => $gameHistory,
            'config' => Config::getSystemConfig()
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nomecompleto' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'telefone' => 'required|string|max:255',
            'document' => 'nullable|string|max:255',
            'role' => 'required|in:USER,ADMIN',
            'balance' => 'required|numeric|min:0',
            'total_deposit' => 'required|numeric|min:0',
            'total_withdraw' => 'required|numeric|min:0',
            'total_cashback' => 'required|numeric|min:0',
            'bloqueado' => 'required|boolean',
            'is_influencer' => 'required|boolean',
            'referral_level' => 'required|integer|min:1',
            'referral_xp' => 'required|integer|min:0',
            'referral_commission' => 'required|numeric|min:0|max:100',
            'commission_balance' => 'required|numeric|min:0',
            'last_ip' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
        ]);

        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Usuário atualizado com sucesso.',
                'user' => $user->fresh()
            ]);
        }

        return back()->with('success', 'Usuário atualizado com sucesso.');
    }

    public function updateField(Request $request, User $user)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        $rules = [
            'nomecompleto' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'telefone' => 'required|string|max:255',
            'document' => 'nullable|string|max:255',
            'role' => 'required|in:USER,ADMIN',
            'balance' => 'required|numeric|min:0',
            'total_deposit' => 'required|numeric|min:0',
            'total_withdraw' => 'required|numeric|min:0',
            'total_cashback' => 'required|numeric|min:0',
            'is_influencer' => 'required|boolean',
            'referral_level' => 'required|integer|min:1',
            'referral_xp' => 'required|integer|min:0',
            'referral_commission' => 'required|numeric|min:0|max:100',
            'commission_balance' => 'required|numeric|min:0',
            'last_ip' => 'nullable|string|max:255',
        ];

        if (!array_key_exists($field, $rules)) {
            return response()->json(['message' => 'Campo inválido'], 400);
        }

        $validated = $request->validate([
            'field' => 'required|string',
            'value' => $rules[$field],
        ]);

        $user->update([
            $field => $value
        ]);

        return response()->json([
            'message' => 'Campo atualizado com sucesso',
            'user' => $user->fresh()
        ]);
    }

    public function toggleField(Request $request, User $user)
    {
        $field = $request->input('field');
        
        $allowedFields = ['bloqueado', 'is_influencer'];
        
        if (!in_array($field, $allowedFields)) {
            return response()->json(['message' => 'Campo inválido'], 400);
        }

        $currentValue = $user->{$field};
        $newValue = $field === 'bloqueado' ? !$currentValue : ($currentValue ? 0 : 1);
        
        $user->update([
            $field => $newValue
        ]);

        return response()->json([
            'message' => 'Status atualizado com sucesso',
            'user' => $user->fresh()
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->role === 'ADMIN' && User::where('role', 'ADMIN')->count() <= 1) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Não é possível excluir o último administrador.'], 400);
            }
            return back()->with('error', 'Não é possível excluir o último administrador.');
        }

        $user->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Usuário excluído com sucesso.']);
        }

        return back()->with('success', 'Usuário excluído com sucesso.');
    }

    public function generateReferralCode(User $user)
    {
        if ($user->referral_code) {
            return response()->json(['message' => 'Usuário já possui código de referência'], 400);
        }

        do {
            $code = \Illuminate\Support\Str::random(8);
        } while (User::where('referral_code', $code)->exists());

        $user->update([
            'referral_code' => $code,
            'referral_commission' => $user->referral_commission ?? 5.0
        ]);

        return response()->json([
            'message' => 'Código de referência gerado com sucesso',
            'user' => $user->fresh()
        ]);
    }
}
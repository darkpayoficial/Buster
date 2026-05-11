<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Registrar novo usuário
     */
    public function register(Request $request)
    {
        try {
            $referredBy = $request->referred_by ?? session('referral_code');
            
            $validator = Validator::make(array_merge($request->all(), ['referred_by' => $referredBy]), [
                'nomecompleto' => 'required|string|min:3',
                'email' => 'required|email|unique:users,email',
                'telefone' => 'required|string',
                'senha' => 'required|string|min:6',
                'referred_by' => 'nullable|string|exists:users,referral_code'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $user = User::create([
                'nomecompleto' => $request->nomecompleto,
                'email' => $request->email,
                'telefone' => $request->telefone,
                'password' => Hash::make($request->senha),
                'referred_by' => $referredBy,
                'username' => explode(' ', $request->nomecompleto)[0] . rand(100, 999)
            ]);

            if ($referredBy) {
                $referrer = User::where('referral_code', $referredBy)->first();
                Log::info('Usuário registrado com indicação', [
                    'new_user_id' => $user->id,
                    'new_user_email' => $user->email,
                    'referred_by_code' => $referredBy,
                    'referrer_id' => $referrer ? $referrer->id : null,
                    'referrer_name' => $referrer ? $referrer->nomecompleto : null
                ]);
                
                session()->forget('referral_code');
            }

            Auth::login($user);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Conta criada com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro no registro: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar conta. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Fazer login
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'login' => 'required|string',
                'password' => 'required|string',
                'remember' => 'boolean'
            ], [
                'login.required' => 'Email ou username é obrigatório',
                'password.required' => 'Senha é obrigatória',
            ]);

            $loginField = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            
            $user = User::where($loginField, $validated['login'])->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'login' => ['Usuário não encontrado.'],
                ]);
            }

            if ($user->isBlocked()) {
                throw ValidationException::withMessages([
                    'login' => ['Sua conta está bloqueada. Entre em contato com o suporte.'],
                ]);
            }

            if (!Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'password' => ['Senha incorreta.'],
                ]);
            }

            Auth::login($user, $validated['remember'] ?? false);
            
            $user->updateLastLogin($request->ip());

            return response()->json([
                'success' => true,
                'message' => 'Login realizado com sucesso!',
                'user' => $user->toArray(),
                'redirect' => '/'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Fazer logout
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user) {
                $user->updateLastLogout();
            }

            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Logout realizado com sucesso!',
                'redirect' => '/'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer logout.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Verificar se usuário está autenticado
     */
    public function check()
    {
        $user = Auth::user();
        
        return response()->json([
            'authenticated' => !!$user,
            'user' => $user ? $user->toArray() : null
        ]);
    }

    /**
     * Obter dados do usuário atual
     */
    public function user()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => $user->toArray()
        ]);
    }

    /**
     * Obter saldo atualizado do usuário
     */
    public function getBalance()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        $freshUser = $user->fresh();

        return response()->json([
            'success' => true,
            'balance' => $freshUser->balance
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\UltimosGanhos;
use App\Models\JogoHistorico;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function login()
    {
        if (Auth::check() && Auth::user()->role === 'ADMIN') {
            return redirect()->route('admin.dashboard');
        }

        $config = Config::getSystemConfig();
        return Inertia::render('admin/login', [
            'config' => $config
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        // Buscar o usuário pelo email diretamente
        $user = User::where('email', $credentials['email'])->first();
        
        // Verificar se o usuário existe e é admin
        if ($user && $user->role === 'ADMIN') {
            // Verificar a senha usando password_verify diretamente (bcrypt)
            if (password_verify($credentials['password'], $user->password)) {
                // Login manual
                Auth::login($user);
                $request->session()->regenerate();
                
                return response()->json([
                    'message' => 'Login realizado com sucesso!'
                ]);
            }
        }

        return response()->json([
            'message' => 'Credenciais inválidas.'
        ], 401);
    }

    public function dashboard()
    {
        $config = Config::getSystemConfig();
        
        $totalUsers = User::count();
        
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        $newUsersToday = User::whereDate('created_at', $today)->count();
        $newUsersYesterday = User::whereDate('created_at', $yesterday)->count();
        
        $totalScratchCards = UltimosGanhos::count();
        
        $totalPrizes = UltimosGanhos::sum('valueprize');
        
        $totalDeposits = Deposit::where('status', Deposit::STATUS_PAID)->sum('amount');
        $totalWithdrawals = Withdrawal::where('status', Withdrawal::STATUS_COMPLETED)->sum('amount');
        
        $totalPlayed = JogoHistorico::join('raspadinhas', 'jogos_historico.raspadinha_id', '=', 'raspadinhas.id')
            ->sum('raspadinhas.value');
        
        $netProfit = $totalDeposits - $totalWithdrawals;
        
        $recentTransactions = collect();
        
        $recentDeposits = Deposit::with('user')
            ->where('status', Deposit::STATUS_PAID)
            ->orderBy('paid_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($deposit) {
                return [
                    'type' => 'deposit',
                    'amount' => $deposit->amount,
                    'user' => $deposit->user->nomecompleto,
                    'date' => $deposit->paid_at,
                ];
            });
        
        $recentWithdrawals = Withdrawal::with('user')
            ->where('status', 'completed')
            ->orderBy('processed_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($withdrawal) {
                return [
                    'type' => 'withdrawal',
                    'amount' => $withdrawal->amount,
                    'user' => $withdrawal->user->nomecompleto,
                    'date' => $withdrawal->processed_at,
                ];
            });
        
        $recentPrizes = UltimosGanhos::orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($prize) {
                return [
                    'type' => 'prize',
                    'amount' => $prize->valueprize,
                    'user' => $prize->namewin,
                    'prize' => $prize->prizename,
                    'date' => $prize->created_at,
                ];
            });

        return Inertia::render('admin/dashboard', [
            'config' => $config,
            'user' => Auth::user(),
            'stats' => [
                'totalUsers' => $totalUsers,
                'newUsersToday' => $newUsersToday,
                'newUsersYesterday' => $newUsersYesterday,
                'totalScratchCards' => $totalScratchCards,
                'totalPrizes' => $totalPrizes,
                'totalDeposits' => $totalDeposits,
                'totalWithdrawals' => $totalWithdrawals,
                'totalPlayed' => $totalPlayed,
                'netProfit' => $netProfit,
                'recentTransactions' => $recentTransactions
                    ->concat($recentDeposits)
                    ->concat($recentWithdrawals)
                    ->concat($recentPrizes)
                    ->sortByDesc('date')
                    ->take(5)
                    ->values()
                    ->all(),
            ]
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}

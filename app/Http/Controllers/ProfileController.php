<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Config;
use App\Models\JogoHistorico;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $stats = [
            'total_deposited' => $user->deposits()->where('status', 'completed')->sum('amount') ?? 0,
            'total_withdrawn' => $user->withdrawals()->where('status', 'completed')->sum('amount') ?? 0,
            'total_cashback' => 0, 
        ];
        
        return Inertia::render('profile/conta', [
            'user' => $user,
            'stats' => $stats,
            'config' => Config::getSystemConfig(),
        ]);
    }

    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
        ], [
            'email.required' => 'Email é obrigatório',
            'email.email' => 'Email deve ter um formato válido',
            'email.unique' => 'Este email já está sendo usado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $request->user()->update([
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email atualizado com sucesso!',
        ]);
    }

    public function updatePhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'telefone' => 'required|string|min:14|max:15',
        ], [
            'telefone.required' => 'Telefone é obrigatório',
            'telefone.min' => 'Telefone deve ter formato válido',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $request->user()->update([
            'telefone' => $request->telefone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Telefone atualizado com sucesso!',
        ]);
    }

    public function updateDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document' => 'required|string|size:14',
        ], [
            'document.required' => 'Documento é obrigatório',
            'document.size' => 'CPF deve ter formato válido',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $request->user()->update([
            'document' => $request->document,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Documento atualizado com sucesso!',
        ]);
    }

    public function updateUsername(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomecompleto' => 'required|string|min:3|max:255',
        ], [
            'nomecompleto.required' => 'Nome é obrigatório',
            'nomecompleto.min' => 'Nome deve ter ao menos 3 caracteres',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $request->user()->update([
            'nomecompleto' => $request->nomecompleto,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nome atualizado com sucesso!',
        ]);
    }

    public function historico()
    {
        $user = Auth::user();
        $historico = JogoHistorico::where('user_id', $user->id)
            ->recent()
            ->paginate(10)
            ->through(function ($jogo) {
                return [
                    'id' => $jogo->id,
                    'raspadinha' => $jogo->raspadinha_name,
                    'status' => $jogo->status === 'win' ? 'Ganhou' : 'Perdeu',
                    'status_color' => $jogo->status === 'win' ? 'success' : 'destructive',
                    'premio' => $jogo->prize_name,
                    'valor' => $jogo->prize_value > 0 ? 'R$ ' . number_format($jogo->prize_value, 2, ',', '.') : '-',
                    'data' => $jogo->created_at->format('d/m/Y H:i'),
                ];
            });

        return Inertia::render('profile/historico', [
            'user' => $user,
            'config' => Config::first(),
            'historico' => $historico,
        ]);
    }

    public function transacoes(Request $request)
    {
        $user = Auth::user();
        $perPage = 10;
        $page = $request->get('page', 1);

        $deposits = Deposit::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($deposit) {
                return [
                    'id' => $deposit->id,
                    'tipo' => 'Depósito',
                    'tipo_cor' => 'success',
                    'status' => match($deposit->status) {
                        Deposit::STATUS_PENDING => 'Pendente',
                        Deposit::STATUS_PAID => 'Aprovado',
                        Deposit::STATUS_EXPIRED => 'Expirado',
                        Deposit::STATUS_CANCELLED => 'Cancelado',
                        Deposit::STATUS_FAILED => 'Falhou',
                        default => 'Desconhecido'
                    },
                    'status_cor' => match($deposit->status) {
                        Deposit::STATUS_PENDING => 'default',
                        Deposit::STATUS_PAID => 'success',
                        Deposit::STATUS_EXPIRED => 'destructive',
                        Deposit::STATUS_CANCELLED => 'destructive',
                        Deposit::STATUS_FAILED => 'destructive',
                        default => 'default'
                    },
                    'metodo' => match($deposit->gateway) {
                        Deposit::GATEWAY_PRIMEBANK => 'PIX',
                        Deposit::GATEWAY_MOCK => 'SISTEMA',
                        Deposit::GATEWAY_COMMISSION => 'COMISÃO',
                        default => 'Pix'
                    },
                    'valor' => 'R$ ' . number_format($deposit->amount, 2, ',', '.'),
                    'data' => $deposit->created_at->format('d/m/Y H:i'),
                ];
            });

        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($withdrawal) {
                return [
                    'id' => $withdrawal->id,
                    'tipo' => 'Saque',
                    'tipo_cor' => 'destructive',
                    'status' => match($withdrawal->status) {
                        'pending' => 'Pendente',
                        'completed' => 'Aprovado',
                        'cancelled' => 'Cancelado',
                        'failed' => 'Falhou',
                        default => 'Desconhecido'
                    },
                    'status_cor' => match($withdrawal->status) {
                        'pending' => 'default',
                        'completed' => 'success',
                        'cancelled' => 'destructive',
                        'failed' => 'destructive',
                        default => 'default'
                    },
                    'metodo' => 'Pix (' . match($withdrawal->pix_key_type) {
                        'cpf' => 'CPF',
                        'cnpj' => 'CNPJ',
                        'email' => 'Email',
                        'phone' => 'Telefone',
                        'random' => 'Chave Aleatória',
                        default => 'Desconhecido'
                    } . ')',
                    'valor' => 'R$ ' . number_format($withdrawal->amount, 2, ',', '.'),
                    'data' => $withdrawal->created_at->format('d/m/Y H:i'),
                    'created_at' => $withdrawal->created_at,
                ];
            });

        $allTransactions = $deposits->concat($withdrawals)
            ->sortByDesc('created_at')
            ->values();

        $items = $allTransactions->forPage($page, $perPage);
        
        $paginator = new LengthAwarePaginator(
            $items,
            $allTransactions->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        $paginator->getCollection()->transform(function ($item) {
            unset($item['created_at']);
            return $item;
        });

        return Inertia::render('profile/transacoes', [
            'user' => $user,
            'config' => Config::first(),
            'transacoes' => $paginator,
        ]);
    }

    public function entregas()
    {
        return Inertia::render('profile/entregas', [
            'user' => Auth::user(),
            'config' => Config::first(),
        ]);
    }

    public function seguranca()
    {
        return Inertia::render('profile/seguranca', [
            'user' => Auth::user(),
            'config' => Config::first(),
        ]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Senha atualizada com sucesso!');
    }
}

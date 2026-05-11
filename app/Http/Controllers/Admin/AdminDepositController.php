<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class AdminDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Deposit::with(['user'])->orderBy('created_at', 'desc');

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
                ->orWhere('payment_id', 'like', "%{$search}%")
                ->orWhere('external_id', 'like', "%{$search}%");
            });
        }

        $deposits = $query->paginate(15)->through(function ($deposit) {
            return [
                'id' => $deposit->id,
                'user' => [
                    'id' => $deposit->user->id,
                    'name' => $deposit->user->name,
                    'email' => $deposit->user->email,
                ],
                'amount' => $deposit->amount,
                'formatted_amount' => 'R$ ' . number_format($deposit->amount, 2, ',', '.'),
                'gateway' => $deposit->gateway,
                'payment_id' => $deposit->payment_id,
                'external_id' => $deposit->external_id,
                'transaction_id' => $deposit->transaction_id,
                'status' => $deposit->status,
                'qr_code' => $deposit->qr_code,
                'expires_at' => $deposit->expires_at ? $deposit->expires_at->format('d/m/Y H:i:s') : null,
                'paid_at' => $deposit->paid_at ? $deposit->paid_at->format('d/m/Y H:i:s') : null,
                'created_at' => $deposit->created_at->format('d/m/Y H:i:s'),
            ];
        });

        return Inertia::render('admin/deposits/index', [
            'deposits' => $deposits,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deposit $deposit): Response
    {
        $deposit->load('user');

        $qrCodeImage = null;
        if ($deposit->qr_code) {
            $qrCodeImage = 'data:image/png;base64,' . base64_encode($deposit->qr_code);
        }

        return Inertia::render('admin/deposits/edit', [
            'deposit' => [
                'id' => $deposit->id,
                'user' => [
                    'id' => $deposit->user->id,
                    'name' => $deposit->user->name,
                    'email' => $deposit->user->email,
                    'balance' => $deposit->user->balance,
                ],
                'amount' => $deposit->amount,
                'formatted_amount' => 'R$ ' . number_format($deposit->amount, 2, ',', '.'),
                'gateway' => $deposit->gateway,
                'payment_id' => $deposit->payment_id,
                'external_id' => $deposit->external_id,
                'transaction_id' => $deposit->transaction_id,
                'status' => $deposit->status,
                'qr_code' => $qrCodeImage,
                'expires_at' => $deposit->expires_at ? $deposit->expires_at->format('d/m/Y H:i:s') : null,
                'paid_at' => $deposit->paid_at ? $deposit->paid_at->format('d/m/Y H:i:s') : null,
                'created_at' => $deposit->created_at->format('d/m/Y H:i:s'),
            ]
        ]);
    }

    /**
     * Aprovar depósito
     */
    public function approve(Request $request, Deposit $deposit): RedirectResponse
    {
        if (!$deposit->isPending()) {
            return back()->withErrors(['error' => 'Este depósito não está pendente.']);
        }

        try {
            DB::beginTransaction();

            $user = $deposit->user;
            $user->balance += $deposit->amount;
            $user->save();

            $deposit->markAsPaid();

            DB::commit();
            return redirect()->route('admin.deposits.index')->with('success', 'Depósito aprovado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao aprovar depósito', [
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Erro ao aprovar depósito: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancelar depósito
     */
    public function cancel(Request $request, Deposit $deposit): RedirectResponse
    {
        if (!$deposit->isPending()) {
            return back()->withErrors(['error' => 'Este depósito não está pendente.']);
        }

        try {
            DB::beginTransaction();

            $deposit->markAsCancelled();

            DB::commit();
            return redirect()->route('admin.deposits.index')->with('success', 'Depósito cancelado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cancelar depósito', [
                'deposit_id' => $deposit->id,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Erro ao cancelar depósito: ' . $e->getMessage()]);
        }
    }
}
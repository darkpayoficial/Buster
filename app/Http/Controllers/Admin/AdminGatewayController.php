<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GatewaysKeys;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminGatewayController extends Controller
{
    /**
     * Exibir página de configuração dos gateways
     */
    public function index()
    {
        $gatewayKeys = GatewaysKeys::getKeys();

        return Inertia::render('admin/gateways/edit', [
            'gatewayKeys' => $gatewayKeys
        ]);
    }

    /**
     * Atualizar as configurações dos gateways
     */
    public function update(Request $request)
    {
        $request->validate([
            'primebank_client_id' => 'nullable|string|max:255',
            'primebank_client_secret' => 'nullable|string|max:255',
        ]);

        $gatewayKeys = GatewaysKeys::getKeys();
        
        $gatewayKeys->update([
            'primebank_client_id' => $request->primebank_client_id,
            'primebank_client_secret' => $request->primebank_client_secret,
        ]);

        return redirect()->back()->with('success', 'Configurações dos gateways atualizadas com sucesso!');
    }
} 
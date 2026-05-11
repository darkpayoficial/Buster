<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Banner;
use App\Models\UltimosGanhos;
use App\Models\Raspadinha;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $config = Config::getSystemConfig();
        $user = $request->user();
        
        if (!$config) {
            $config = [
                'id' => 1,
                'app_name' => 'Sistema de Raspadinhas',
                'logo' => '/logo.svg',
                'favicon' => '/favicon.svg',
                'footer_text' => 'Sistema de Raspadinhas - Sua sorte está aqui!',
                'contact_email' => 'contato@raspadinhas.com.br',
                'contact_phone' => '(11) 99999-9999',
                'address' => 'São Paulo, SP - Brasil'
            ];
        } else {
            $config = $config->toArray();
        }

        $banners = Banner::active()->ordered()->get()->toArray();
        
        $ultimosGanhos = UltimosGanhos::active()
        ->recent()
        ->limit(50)
        ->orderByDesc('valueprize')
        ->get()
        ->toArray();        
        $hotRaspadinhas = Raspadinha::active()->hot()->available()->orderBy('value')->limit(6)->get()->map(function ($raspadinha) {
            return [
                'id' => $raspadinha->id,
                'name' => $raspadinha->name,
                'title' => $raspadinha->title,
                'description' => $raspadinha->description,
                'photo' => $raspadinha->photo,
                'value' => $raspadinha->value,
                'max_prize' => $this->getMaxPrize($raspadinha),
                'hot' => $raspadinha->hot,
                'slug' => $this->generateSlug($raspadinha->name),
                'active' => $raspadinha->active,
            ];
        })->toArray();
        
        return Inertia::render('Home', [
            'config' => $config,
            'user' => $user ? $user->toArray() : null,
            'banners' => $banners,
            'ultimosGanhos' => $ultimosGanhos,
            'hotRaspadinhas' => $hotRaspadinhas,
        ]);
    }
    
    /**
     * Calcula o prêmio máximo da raspadinha
     */
    private function getMaxPrize(Raspadinha $raspadinha): float
    {
        $maxPrize = $raspadinha->activePrizes()->max('value');
        return $maxPrize ?? 0;
    }
    
    /**
     * Gera slug a partir do nome
     */
    private function generateSlug(string $name): string
    {
        return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $name));
    }

    /**
     * Processar link de indicação/referência
     */
    public function referral(Request $request, $code = null)
    {
        if ($code) {
            $referrer = \App\Models\User::where('referral_code', $code)->first();
            
            if ($referrer) {
                session(['referral_code' => $code]);
                
                Log::info('Código de referência capturado', [
                    'code' => $code,
                    'referrer_id' => $referrer->id,
                    'referrer_name' => $referrer->nomecompleto
                ]);
                
                return redirect()->route('home')->with('referral_message', 'Você foi indicado por ' . $referrer->nomecompleto . '! Registre-se para ganhar bônus.');
            } else {
                Log::warning('Código de referência inválido', ['code' => $code]);
                
                return redirect()->route('home')->with('error', 'Código de indicação inválido.');
            }
        }
        
        return redirect()->route('referral.index');
    }

    /**
     * Página de acesso não autorizado
     */
    public function notAuthorized(Request $request)
    {
        $config = \App\Models\Config::getSystemConfig();
        $user = $request->user();
        
        if (!$config) {
            $config = [
                'id' => 1,
                'app_name' => 'RASPA GREEN',
                'logo' => '/logo.svg',
                'favicon' => '/favicon.svg',
                'footer_text' => 'Sistema de Raspadinhas - Sua sorte está aqui!',
                'contact_email' => 'contato@raspagreen.com.br',
                'contact_phone' => '(11) 99999-9999',
                'address' => 'São Paulo, SP - Brasil'
            ];
        } else {
            $config = $config->toArray();
        }

        return Inertia::render('notauthorized', [
            'config' => $config,
            'user' => $user ? $user->toArray() : null,
        ]);
    }

    /**
     * Página 404 personalizada
     */
    public function notFound(Request $request)
    {
        $config = \App\Models\Config::getSystemConfig();
        $user = $request->user();
        
        if (!$config) {
            $config = [
                'id' => 1,
                'app_name' => 'RASPA GREEN',
                'logo' => '/logo.svg',
                'favicon' => '/favicon.svg',
                'footer_text' => 'Sistema de Raspadinhas - Sua sorte está aqui!',
                'contact_email' => 'contato@raspagreen.com.br',
                'contact_phone' => '(11) 99999-9999',
                'address' => 'São Paulo, SP - Brasil'
            ];
        } else {
            $config = $config->toArray();
        }

        return Inertia::render('notfound', [
            'config' => $config,
            'user' => $user ? $user->toArray() : null,
        ])->toResponse($request)->setStatusCode(404);
    }
} 
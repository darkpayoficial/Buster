<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingConfig = \App\Models\Config::first();
        
        if ($existingConfig) {
            $existingConfig->update([
                'min_deposit_amount' => $existingConfig->min_deposit_amount ?? 1.00,
                'max_deposit_amount' => $existingConfig->max_deposit_amount ?? 10000.00,
                'min_withdraw_amount' => $existingConfig->min_withdraw_amount ?? 10.00,
                'max_withdraw_amount' => $existingConfig->max_withdraw_amount ?? 50000.00,
            ]);
        } else {
            \App\Models\Config::create([
                'app_name' => 'RASPA GREEN',
                'logo' => '/logo.svg',
                'favicon' => '/favicon.svg',
                'footer_text' => 'Sistema de Raspadinhas - Sua sorte está aqui!',
                'contact_email' => 'contato@raspagreen.com.br',
                'contact_phone' => '(11) 99999-9999',
                'address' => 'São Paulo, SP - Brasil',
                'description' => 'A melhor plataforma de raspadinhas online do Brasil. Ganhe prêmios incríveis, PIX na unha e muito mais!',
                'keywords' => 'raspadinha, sorte, prêmios, jogos, online, brasil, pix'
            ]);
        }
    }
}

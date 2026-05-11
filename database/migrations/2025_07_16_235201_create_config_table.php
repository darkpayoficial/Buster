<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('config', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('RASPA GREEN');
            $table->string('logo')->default('/logo.svg');
            $table->string('favicon')->default('/favicon.svg');
            $table->string('footer_text')->default('Sistema de Raspadinhas - Sua sorte está aqui!');
            $table->string('contact_email')->default('contato@raspagreen.com.br');
            $table->string('contact_phone')->default('(11) 99999-9999');
            $table->string('address')->default('São Paulo, SP - Brasil');
            $table->text('description')->nullable()->default('A melhor plataforma de raspadinhas online do Brasil. Ganhe prêmios incríveis, PIX na unha e muito mais!');
            $table->text('keywords')->nullable()->default('raspadinha, sorte, prêmios, jogos, online, brasil, pix');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};

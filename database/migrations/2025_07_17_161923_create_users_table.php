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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            $table->string('nomecompleto');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('telefone');
            $table->string('password');
            
            $table->enum('role', ['USER', 'ADMIN'])->default('USER');
            
            $table->decimal('total_deposit', 15, 2)->default(0);
            $table->decimal('total_withdraw', 15, 2)->default(0);
            $table->decimal('total_cashback', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            
            $table->string('document')->nullable();
            
            $table->boolean('bloqueado')->default(false);
            $table->timestamp('last_login')->nullable();
            $table->string('last_ip')->nullable();
            $table->timestamp('last_logout')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            
            $table->index(['email', 'username']);
            $table->index('role');
            $table->index('bloqueado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

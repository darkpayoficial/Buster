<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jogos_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('raspadinha_id')->constrained('raspadinhas')->onDelete('cascade');
            $table->string('raspadinha_name');
            $table->unsignedBigInteger('prize_id')->nullable();
            $table->string('prize_name')->nullable();
            $table->decimal('prize_value', 10, 2)->default(0);
            $table->string('prize_img')->nullable();
            $table->enum('status', ['win', 'loss']);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('jogos_historico');
    }
}; 
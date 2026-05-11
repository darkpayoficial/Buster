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
        Schema::create('raspadinha_prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raspadinha_id')->constrained('raspadinhas')->onDelete('cascade');
            $table->string('name');     
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('probability', 5, 2);
            $table->string('display_value')->nullable();
            $table->boolean('is_jackpot')->default(false);
            $table->integer('max_wins')->nullable();
            $table->integer('current_wins')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['raspadinha_id', 'active']);
            $table->index('probability');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raspadinha_prizes');
    }
};

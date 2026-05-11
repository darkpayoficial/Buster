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
        Schema::table('config', function (Blueprint $table) {
            // Cores do tema
            $table->string('primary_color')->default('#4ADE80');
            $table->string('secondary_color')->default('#1F2937');
            $table->string('accent_color')->default('#6366F1');
            $table->string('background_color')->default('#000000');
            $table->string('foreground_color')->default('#FFFFFF');
            $table->string('muted_color')->default('#374151');
            $table->string('muted_foreground_color')->default('#9CA3AF');
            $table->string('card_color')->default('#111827');
            $table->string('card_foreground_color')->default('#FFFFFF');
            $table->string('border_color')->default('#374151');
            $table->string('input_color')->default('#374151');
            $table->string('ring_color')->default('#4ADE80');

            $table->boolean('auto_withdraw_enabled')->default(false);
            $table->decimal('auto_withdraw_max_amount', 10, 2)->default(1000.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->dropColumn([
                'primary_color',
                'secondary_color',
                'accent_color',
                'background_color',
                'foreground_color',
                'muted_color',
                'muted_foreground_color',
                'card_color',
                'card_foreground_color',
                'border_color',
                'input_color',
                'ring_color',
                'auto_withdraw_enabled',
                'auto_withdraw_max_amount'
            ]);
        });
    }
}; 
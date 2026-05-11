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
            $table->decimal('min_deposit_amount', 10, 2)->default(1.00)->after('address');
            $table->decimal('max_deposit_amount', 10, 2)->default(10000.00)->after('min_deposit_amount');
            $table->decimal('min_withdraw_amount', 10, 2)->default(10.00)->after('max_deposit_amount');
            $table->decimal('max_withdraw_amount', 10, 2)->default(50000.00)->after('min_withdraw_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->dropColumn([
                'min_deposit_amount',
                'max_deposit_amount', 
                'min_withdraw_amount',
                'max_withdraw_amount'
            ]);
        });
    }
};

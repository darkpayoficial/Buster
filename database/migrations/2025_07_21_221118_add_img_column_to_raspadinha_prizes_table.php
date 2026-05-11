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
        Schema::table('raspadinha_prizes', function (Blueprint $table) {
            $table->string('img')->nullable()->after('display_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raspadinha_prizes', function (Blueprint $table) {
            $table->dropColumn('img');
        });
    }
};

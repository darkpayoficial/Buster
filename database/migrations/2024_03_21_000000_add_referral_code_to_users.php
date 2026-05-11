<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code')->nullable()->unique()->after('role');
            $table->unsignedBigInteger('referral_level')->default(1)->after('referral_code');
            $table->unsignedBigInteger('referral_xp')->default(0)->after('referral_level');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'referral_level', 'referral_xp']);
        });
    }
}; 
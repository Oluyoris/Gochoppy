<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->string('wallet_type')->default('main')->after('user_id'); // or before balance
            // Optional: add index for faster lookups
            $table->index(['user_id', 'wallet_type']);
        });
    }

    public function down()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('wallet_type');
        });
    }
};
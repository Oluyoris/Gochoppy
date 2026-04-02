<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. 'service_charge_percent', 'paystack_secret_key', 'manual_bank_name', 'referral_bonus_amount'
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, decimal, boolean, json, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
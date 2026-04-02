<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            
            $table->string('reference')->unique();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
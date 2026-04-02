<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            
            $table->decimal('discount_amount', 10, 2)->nullable(); // Fixed amount only (e.g 1000)
            
            $table->json('applicable_categories'); // ["kitchen", "supermarket", "pharmacy", "dispatch"]
            
            $table->integer('max_uses')->default(10);
            $table->integer('used_count')->default(0);
            
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
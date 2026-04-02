<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_delivery_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('dispatch_percentage')->default(60);   // Dispatch gets 60%
            $table->integer('admin_percentage')->default(40);      // Admin gets 40%
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default row
        DB::table('admin_delivery_settings')->insert([
            'dispatch_percentage' => 60,
            'admin_percentage'    => 40,
            'is_active'           => true,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_delivery_settings');
    }
};
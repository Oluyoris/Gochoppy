<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_bus_stop_id')
                  ->nullable()
                  ->constrained('popular_bus_stops')
                  ->onDelete('set null');

            $table->foreignId('vendor_bus_stop_id')
                  ->nullable()
                  ->constrained('popular_bus_stops')
                  ->onDelete('set null');

            // Optional: Add index for faster queries
            $table->index(['user_bus_stop_id', 'vendor_bus_stop_id']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_bus_stop_id']);
            $table->dropForeign(['vendor_bus_stop_id']);
            $table->dropColumn(['user_bus_stop_id', 'vendor_bus_stop_id']);
        });
    }
};
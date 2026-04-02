<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add to main users table (for customers & dispatchers mainly)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('popular_bus_stop_id')
                  ->nullable()
                  ->constrained('popular_bus_stops')
                  ->onDelete('set null');
        });

        // Also add to vendor_profiles (in case vendor has different pickup point)
        Schema::table('vendor_profiles', function (Blueprint $table) {
            $table->foreignId('popular_bus_stop_id')
                  ->nullable()
                  ->constrained('popular_bus_stops')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('popular_bus_stop_id');
        });

        Schema::table('vendor_profiles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('popular_bus_stop_id');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role is the most important field
            $table->enum('role', ['customer', 'vendor', 'dispatcher'])
                  ->default('customer')
                  ->after('id');

            // Customers can login with username OR email
            $table->string('username')->unique()->nullable()
                  ->after('role');

            // Phone number - required for ALL roles
            $table->string('phone')->unique()
                  ->after('username');

            // Profile picture (used mostly by vendor & dispatcher)
            $table->string('avatar')->nullable()
                  ->after('phone');

            // Main address (delivery for customer, location for others)
            $table->text('address')->nullable()
                  ->after('avatar');

            // State / region
            $table->string('state')->nullable()
                  ->after('address');

            // Admin can disable account
            $table->boolean('is_active')->default(true)
                  ->after('state');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'username', 'phone', 'avatar', 'address', 'state', 'is_active'
            ]);
        });
    }
};
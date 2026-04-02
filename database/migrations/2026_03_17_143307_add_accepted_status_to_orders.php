<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Safely remove the existing check constraint if it exists
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check');

        // Add the new check constraint WITH 'NOT VALID' so it skips checking old rows
        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT orders_status_check
            CHECK (status IN (
                'ordered',
                'paid',
                'accepted',
                'packaged',
                'picked_up',
                'enroute',
                'delivered',
                'cancelled'
            )) NOT VALID
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the constraint we added
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check');

        // Optional: restore a previous version without 'accepted' (you can comment this out)
        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT orders_status_check
            CHECK (status IN (
                'ordered',
                'paid',
                'received',
                'packaged',
                'picked_up',
                'enroute',
                'delivered',
                'cancelled'
            ))
        ");
    }
};
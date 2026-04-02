<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Drop the existing check constraint on status
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check');

        // Step 2: Add the new check constraint with 'ordered' included
        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT orders_status_check
            CHECK (status IN (
                'ordered',
                'pending_payment',
                'paid',
                'preparing',
                'ready_for_pickup',
                'picked_up',
                'in_transit',
                'delivered',
                'cancelled'
            ))
        ");

        // Optional: Ensure default is still set (if needed)
        DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'pending_payment'");
    }

    public function down(): void
    {
        // Revert: Drop new constraint and add back old one (without 'ordered')
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check');

        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT orders_status_check
            CHECK (status IN (
                'pending_payment',
                'paid',
                'preparing',
                'ready_for_pickup',
                'picked_up',
                'in_transit',
                'delivered',
                'cancelled'
            ))
        ");

        DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'pending_payment'");
    }
};
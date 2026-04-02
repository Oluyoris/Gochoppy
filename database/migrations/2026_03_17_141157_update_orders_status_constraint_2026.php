<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check');

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

        // Optional - change default if you want orders to start as 'ordered'
        // DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'ordered'");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check');

        // You can leave rollback empty or put back old constraint
        // For safety, we'll just drop it in down()
    }
};
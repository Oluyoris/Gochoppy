<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop ALL constraints first - this is the safest way
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_status_check;');
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_method_check;');
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check;');

        // Now fix the data without any constraints active
        DB::statement("
            UPDATE orders 
            SET payment_status = 'paid' 
            WHERE payment_status = 'success' OR payment_status IS NULL
        ");

        // Re-add the constraints with all values from your current data + new ones we need
        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_payment_method_check 
            CHECK (payment_method IN ('paystack', 'bank_transfer', 'wallet'))
        ");

        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_payment_status_check 
            CHECK (payment_status IN ('pending', 'paid', 'failed'))
        ");

        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_status_check 
            CHECK (status IN ('ordered', 'confirmed', 'paid', 'received', 'packaged', 'picked_up', 'enroute', 'delivered'))
        ");
    }

    public function down()
    {
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_status_check;');
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_method_check;');
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check;');

        // Minimal rollback
        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_payment_method_check 
            CHECK (payment_method IN ('paystack', 'bank_transfer'))
        ");

        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_payment_status_check 
            CHECK (payment_status IN ('pending'))
        ");

        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_status_check 
            CHECK (status IN ('ordered', 'received', 'enroute', 'delivered'))
        ");
    }
};
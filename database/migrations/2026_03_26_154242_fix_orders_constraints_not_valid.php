<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop old constraints if they exist
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check;');
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_status_check;');
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_method_check;');

        // Fix any bad payment_status (change 'success' to 'paid')
        DB::statement("
            UPDATE orders 
            SET payment_status = 'paid' 
            WHERE payment_status = 'success' OR payment_status IS NULL
        ");

        // Add constraints WITHOUT validating existing rows (NOT VALID)
        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_payment_method_check 
            CHECK (payment_method IN ('paystack', 'bank_transfer', 'wallet')) NOT VALID
        ");

        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_payment_status_check 
            CHECK (payment_status IN ('pending', 'paid', 'failed')) NOT VALID
        ");

        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_status_check 
            CHECK (status IN ('ordered', 'paid', 'accepted', 'packaged', 'picked_up', 'enroute', 'delivered')) NOT VALID
        ");

        // Optional: You can validate later when you have time
        // DB::statement('ALTER TABLE orders VALIDATE CONSTRAINT orders_status_check;');
    }

    public function down()
    {
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check;');
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_status_check;');
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_method_check;');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, drop the old check constraint if it exists
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_method_check;');

        // Now recreate the constraint with all allowed values including 'wallet'
        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_payment_method_check 
            CHECK (payment_method IN ('paystack', 'bank_transfer', 'wallet'))
        ");
    }

    public function down()
    {
        // Revert to previous values (without wallet)
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_method_check;');

        DB::statement("
            ALTER TABLE orders 
            ADD CONSTRAINT orders_payment_method_check 
            CHECK (payment_method IN ('paystack', 'bank_transfer'))
        ");
    }
};
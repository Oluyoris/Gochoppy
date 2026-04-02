<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    \DB::statement("ALTER TABLE withdrawals DROP CONSTRAINT IF EXISTS withdrawals_status_check");

    \DB::statement("ALTER TABLE withdrawals ADD CONSTRAINT withdrawals_status_check 
                    CHECK (status IN ('pending', 'approved', 'rejected'))");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            //
        });
    }
};

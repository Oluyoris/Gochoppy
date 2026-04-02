<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('vendor_id')->constrained('users');
            $table->foreignId('dispatcher_id')->nullable()->constrained('users');

            $table->enum('status', [
                'pending_payment', 'paid', 'preparing', 'ready_for_pickup',
                'picked_up', 'in_transit', 'delivered', 'cancelled'
            ])->default('pending_payment');

            $table->enum('payment_method', ['paystack', 'bank_transfer']);
            $table->string('payment_reference')->nullable();
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');

            $table->decimal('items_total', 10, 2);
            $table->decimal('delivery_fee', 10, 2);
            $table->decimal('service_charge', 10, 2);
            $table->decimal('grand_total', 10, 2);

            $table->timestamp('estimated_delivery_time')->nullable();
            $table->timestamp('actual_delivery_time')->nullable();

            $table->text('customer_address');
            $table->text('vendor_address');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
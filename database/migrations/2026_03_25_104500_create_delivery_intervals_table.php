<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_intervals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_stop_id')->constrained('popular_bus_stops')->onDelete('cascade');
            $table->foreignId('to_stop_id')->constrained('popular_bus_stops')->onDelete('cascade');
            $table->integer('price');                    // e.g. 2900 (Naira)
            $table->string('estimated_time');            // e.g. "25-35 mins"
            $table->timestamps();

            $table->unique(['from_stop_id', 'to_stop_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_intervals');
    }
};
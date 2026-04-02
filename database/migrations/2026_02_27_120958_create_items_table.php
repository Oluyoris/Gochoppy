<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Helps fast filtering: only show kitchen items to food tab, etc.
            $table->enum('vendor_type', ['kitchen', 'supermarket', 'pharmacy'])
                  ->index();

            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->string('category')->nullable()->index(); // e.g. "Rice", "Beverages", "Pain Relief"

            $table->boolean('is_available')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
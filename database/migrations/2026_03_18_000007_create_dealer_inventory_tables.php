<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dealer_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained('dealers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('stock_quantity')->default(0);
            $table->unique(['dealer_id', 'product_id']);
            $table->timestamps();
        });

        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->nullable()->constrained('dealers')->onDelete('cascade'); // Null for global admin stock logs if needed
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->enum('type', ['IN', 'OUT']);
            $table->integer('quantity');
            $table->string('reference_type')->nullable(); // Order, Service, Manual Adjustment
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('previous_stock')->default(0);
            $table->integer('new_stock')->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
        Schema::dropIfExists('dealer_inventory');
    }
};

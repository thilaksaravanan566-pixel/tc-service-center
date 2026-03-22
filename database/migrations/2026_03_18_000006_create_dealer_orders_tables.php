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
        Schema::create('dealer_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('dealer_id')->constrained('dealers')->onDelete('cascade');
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->enum('status', ['pending', 'approved', 'packed', 'shipped', 'delivered', 'rejected'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid', 'partial'])->default('unpaid');
            $table->dateTime('order_date');
            $table->timestamps();
        });

        Schema::create('dealer_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_order_id')->constrained('dealer_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price_per_unit', 12, 2)->default(0.00);
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_order_items');
        Schema::dropIfExists('dealer_orders');
    }
};

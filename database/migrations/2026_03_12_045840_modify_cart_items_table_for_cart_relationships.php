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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropUnique(['customer_id', 'spare_part_id']);
            $table->dropColumn('customer_id');
            
            $table->foreignId('cart_id')->after('id')->constrained('carts')->onDelete('cascade');
            $table->unique(['cart_id', 'spare_part_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
            $table->dropUnique(['cart_id', 'spare_part_id']);
            $table->dropColumn('cart_id');
            
            $table->foreignId('customer_id')->after('id')->constrained('customers')->onDelete('cascade');
            $table->unique(['customer_id', 'spare_part_id']);
        });
    }
};

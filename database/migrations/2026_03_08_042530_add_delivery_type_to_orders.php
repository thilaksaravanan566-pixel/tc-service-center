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
        Schema::table('product_orders', function (Blueprint $table) {
            $table->enum('delivery_type', ['take_away', 'delivery'])->default('take_away');
        });

        Schema::table('service_orders', function (Blueprint $table) {
            $table->enum('delivery_type', ['take_away', 'delivery'])->default('take_away');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->dropColumn('delivery_type');
        });

        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn('delivery_type');
        });
    }
};

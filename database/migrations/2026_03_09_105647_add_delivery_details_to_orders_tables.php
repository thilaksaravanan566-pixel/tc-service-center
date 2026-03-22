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
        Schema::table('service_orders', function (Blueprint $table) {
            $table->text('delivery_address')->nullable();
            $table->text('delivery_location_url')->nullable();
            $table->string('delivery_mobile')->nullable();
            $table->unsignedBigInteger('delivery_partner_id')->nullable();
        });

        Schema::table('product_orders', function (Blueprint $table) {
            $table->text('delivery_address')->nullable();
            $table->text('delivery_location_url')->nullable();
            $table->string('delivery_mobile')->nullable();
            $table->unsignedBigInteger('delivery_partner_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_address', 'delivery_location_url', 'delivery_mobile', 'delivery_partner_id']);
        });

        Schema::table('product_orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_address', 'delivery_location_url', 'delivery_mobile', 'delivery_partner_id']);
        });
    }
};

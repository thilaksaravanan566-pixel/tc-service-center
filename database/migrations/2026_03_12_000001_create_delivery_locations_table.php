<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_locations', function (Blueprint $table) {
            $table->id();
            // polymorphic order reference (service_orders or product_orders)
            $table->string('order_type');          // 'service' | 'product'
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('delivery_partner_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();

            // Delivery partner live location
            $table->decimal('partner_lat', 10, 7)->nullable();
            $table->decimal('partner_lng', 10, 7)->nullable();

            // Customer delivery address / destination
            $table->decimal('customer_lat', 10, 7)->nullable();
            $table->decimal('customer_lng', 10, 7)->nullable();
            $table->text('customer_address')->nullable();

            // Status
            $table->enum('delivery_status', [
                'pending', 'assigned', 'picked_up', 'in_transit', 'delivered', 'failed'
            ])->default('pending');

            $table->timestamp('partner_location_updated_at')->nullable();
            $table->timestamps();

            $table->index(['order_type', 'order_id']);
            $table->index('delivery_partner_id');
            $table->index('delivery_status');
        });

        // Extend service_orders with location fields if not already present
        if (Schema::hasTable('service_orders')) {
            Schema::table('service_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('service_orders', 'customer_lat')) {
                    $table->decimal('customer_lat', 10, 7)->nullable()->after('delivery_address');
                }
                if (!Schema::hasColumn('service_orders', 'customer_lng')) {
                    $table->decimal('customer_lng', 10, 7)->nullable()->after('customer_lat');
                }
                if (!Schema::hasColumn('service_orders', 'delivery_status')) {
                    $table->string('delivery_status')->nullable()->after('customer_lng');
                }
            });
        }

        // Extend product_orders (customer orders) with location fields
        if (Schema::hasTable('customer_orders')) {
            Schema::table('customer_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('customer_orders', 'customer_lat')) {
                    $table->decimal('customer_lat', 10, 7)->nullable();
                }
                if (!Schema::hasColumn('customer_orders', 'customer_lng')) {
                    $table->decimal('customer_lng', 10, 7)->nullable();
                }
                if (!Schema::hasColumn('customer_orders', 'customer_address')) {
                    $table->text('customer_address')->nullable();
                }
            });
        }

        // Extend users with live location for delivery partners
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'current_lat')) {
                    $table->decimal('current_lat', 10, 7)->nullable();
                }
                if (!Schema::hasColumn('users', 'current_lng')) {
                    $table->decimal('current_lng', 10, 7)->nullable();
                }
                if (!Schema::hasColumn('users', 'location_updated_at')) {
                    $table->timestamp('location_updated_at')->nullable();
                }
                if (!Schema::hasColumn('users', 'is_online')) {
                    $table->boolean('is_online')->default(false);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_locations');
    }
};

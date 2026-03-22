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
        // SHIPMENTS for tracking Dealer Orders (Admin to Dealer)
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_order_id')->nullable()->constrained('dealer_orders')->onDelete('cascade');
            $table->enum('method', ['courier', 'bus_parcel'])->default('courier');
            
            // Courier Fields
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable();
            
            // Bus Fields
            $table->string('bus_name')->nullable();
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->string('lr_number')->nullable();
            $table->string('contact_number')->nullable();

            $table->dateTime('dispatch_at')->nullable();
            $table->dateTime('delivery_eta')->nullable();
            $table->enum('status', ['dispatched', 'in_transit', 'delivered'])->default('dispatched');
            $table->timestamps();
        });

        // DELIVERIES for tracking Service Returns and Orders (Last mile)
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('reference_type'); // dealer_order, service_return
            $table->unsignedBigInteger('reference_id');
            $table->foreignId('delivery_person_id')->nullable()->constrained('users');
            $table->enum('status', ['assigned', 'picked_up', 'in_transit', 'delivered'])->default('assigned');
            $table->text('pickup_location')->nullable();
            $table->text('drop_location')->nullable();
            $table->decimal('current_lat', 10, 8)->nullable();
            $table->decimal('current_lng', 11, 8)->nullable();
            $table->dateTime('picked_up_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
        });

        // STORE VISITS for CRM/Tech Field Visits
        Schema::create('store_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained('dealers')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // Technician/Staff
            $table->date('visit_date');
            $table->string('purpose')->nullable(); // Demo, Collection, Technical Audit
            $table->enum('status', ['scheduled', 'in_progress', 'completed'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->dateTime('check_in_at')->nullable();
            $table->decimal('check_in_lat', 10, 8)->nullable();
            $table->decimal('check_in_lng', 11, 8)->nullable();
            $table->dateTime('check_out_at')->nullable();
            $table->json('visit_photos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_visits');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('shipments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('tc_job_id')->unique(); // e.g., TC-2026-001
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('device_id')->constrained();
            $table->foreignId('technician_id')->nullable()->constrained('users');
            
            // Tracking Stages
            $table->enum('status', ['received', 'packing', 'shipping', 'out_for_delivery', 'delivered'])->default('received');
            $table->text('fault_details')->nullable();
            $table->decimal('estimated_cost', 10, 2)->default(0.00);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('service_orders'); }
};
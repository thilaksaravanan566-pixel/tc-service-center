<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Job Update Timeline ─────────────────────────────────────
        // Tracks each status change on a service order with note + photo
        Schema::create('job_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->foreignId('updated_by')->constrained('users')->cascadeOnDelete();
            $table->string('status');                           // new status value
            $table->string('previous_status')->nullable();      // for audit trail
            $table->text('note')->nullable();                   // technician note
            $table->string('photo_path')->nullable();           // proof/work photo
            $table->timestamps();

            $table->index(['service_order_id', 'created_at']);
        });

        // ─── Delivery Update Timeline ────────────────────────────────
        // Tracks each delivery status change with OTP / proof photo
        Schema::create('delivery_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_location_id')->constrained('delivery_locations')->cascadeOnDelete();
            $table->foreignId('updated_by')->constrained('users')->cascadeOnDelete();
            $table->string('status');                           // assigned | picked_up | in_transit | delivered | failed
            $table->string('previous_status')->nullable();
            $table->text('note')->nullable();
            $table->string('proof_photo')->nullable();          // delivery proof image
            $table->string('otp_code')->nullable();             // hashed OTP
            $table->boolean('otp_verified')->default(false);   // verified by customer
            $table->timestamp('otp_verified_at')->nullable();
            $table->decimal('lat', 10, 7)->nullable();          // GPS at delivery time
            $table->decimal('lng', 10, 7)->nullable();
            $table->timestamps();

            $table->index(['delivery_location_id', 'created_at']);
        });

        // ─── OTP Requests ────────────────────────────────────────────
        // Temporary OTP store for delivery confirmation (4-digit)
        Schema::create('delivery_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_location_id')->constrained('delivery_locations')->cascadeOnDelete();
            $table->string('phone');                            // customer phone that receives OTP
            $table->string('otp', 6);                          // plain 4-6 digit OTP
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_otps');
        Schema::dropIfExists('delivery_updates');
        Schema::dropIfExists('job_updates');
    }
};

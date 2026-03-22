<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('service_order_id')->nullable()->constrained('service_orders')->nullOnDelete();
            $table->json('image_paths')->nullable();           // Uploaded device photos
            $table->text('problem_description')->nullable();   // Customer's text description
            $table->text('ai_analysis')->nullable();           // AI generated diagnostic report
            $table->json('suggested_issues')->nullable();      // ['screen_damage', 'battery_swelling', ...]
            $table->json('suggested_parts')->nullable();       // Recommended spare part IDs
            $table->json('troubleshooting_steps')->nullable(); // Step-by-step instructions
            $table->decimal('confidence_score', 5, 2)->nullable(); // 0-100%
            $table->string('diagnosis_type')->default('text'); // text, photo, combined
            $table->timestamps();
        });

        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['banner', 'email', 'sms', 'push', 'discount'])->default('banner');
            $table->text('description')->nullable();
            $table->text('content')->nullable();          // HTML or message body
            $table->string('image_path')->nullable();
            $table->string('discount_code')->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->string('target_audience')->default('all'); // all, new_customers, repeat_customers
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('click_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('customer_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->default('call'); // call, email, visit, sms
            $table->text('notes');
            $table->timestamp('followup_at')->nullable(); // Scheduled follow-up time
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_followups');
        Schema::dropIfExists('marketing_campaigns');
        Schema::dropIfExists('ai_diagnoses');
    }
};

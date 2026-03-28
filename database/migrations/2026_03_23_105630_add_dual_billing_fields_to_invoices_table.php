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
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('bill_type', ['estimation', 'gst'])->default('estimation')->after('invoice_number');
            $table->date('valid_until')->nullable()->after('bill_type');
            $table->unsignedBigInteger('parent_estimate_id')->nullable()->after('service_order_id');

            $table->foreign('parent_estimate_id')->references('id')->on('invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['parent_estimate_id']);
            $table->dropColumn(['bill_type', 'valid_until', 'parent_estimate_id']);
        });
    }
};

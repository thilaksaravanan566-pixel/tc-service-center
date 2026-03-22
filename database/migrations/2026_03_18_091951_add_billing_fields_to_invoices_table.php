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
            $table->string('billing_type')->after('invoice_number')->nullable(); // dealer, online, walkin
            $table->decimal('gst_percentage', 5, 2)->default(18.00)->after('subtotal');
            $table->decimal('gst_amount', 15, 2)->default(0)->after('gst_percentage');
            $table->string('payment_status')->default('unpaid')->after('total'); // unpaid, paid, partial
            $table->string('payment_method')->nullable()->after('payment_status'); // cash, upi, card, bank_transfer
            $table->text('notes')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['billing_type', 'gst_percentage', 'gst_amount', 'payment_status', 'payment_method', 'notes']);
        });
    }
};

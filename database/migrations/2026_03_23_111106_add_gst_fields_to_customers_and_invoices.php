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
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'gst_number')) {
                $table->string('gst_number', 15)->nullable()->after('email');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'customer_gst')) {
                $table->string('customer_gst', 15)->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('invoices', 'state_code')) {
                $table->string('state_code', 2)->nullable()->after('customer_gst');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('gst_number');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['customer_gst', 'state_code']);
        });
    }
};

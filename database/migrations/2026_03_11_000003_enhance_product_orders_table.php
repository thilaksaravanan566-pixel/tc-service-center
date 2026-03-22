<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only add new columns; delivery fields were already added by 2026_03_09_105647
        Schema::table('product_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('product_orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('delivery_partner_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            if (Schema::hasColumn('product_orders', 'tracking_number')) {
                $table->dropColumn('tracking_number');
            }
        });
    }
};

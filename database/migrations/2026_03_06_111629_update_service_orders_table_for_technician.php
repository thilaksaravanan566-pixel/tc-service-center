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
            $table->text('engineer_comment')->nullable()->after('fault_details');
        });

        // Modify enum using raw SQL to avoid Doctrine issues (ONLY ON MYSQL)
        if (config('database.default') === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE service_orders MODIFY status ENUM('received', 'diagnosing', 'repairing', 'packing', 'shipping', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'received'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn('engineer_comment');
        });

        if (config('database.default') === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE service_orders MODIFY status ENUM('received', 'packing', 'shipping', 'out_for_delivery', 'delivered') DEFAULT 'received'");
        }
    }
};

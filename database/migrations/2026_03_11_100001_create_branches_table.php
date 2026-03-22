<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Add branch_id to service_orders
        if (Schema::hasTable('service_orders') && !Schema::hasColumn('service_orders', 'branch_id')) {
            Schema::table('service_orders', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->nullOnDelete();
            });
        }

        // Add branch_id to product_orders
        if (Schema::hasTable('product_orders') && !Schema::hasColumn('product_orders', 'branch_id')) {
            Schema::table('product_orders', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->nullOnDelete();
            });
        }

        // Add branch_id to users
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'branch_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('biometric_id')->constrained('branches')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'branch_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('branch_id');
            });
        }
        if (Schema::hasTable('product_orders') && Schema::hasColumn('product_orders', 'branch_id')) {
            Schema::table('product_orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('branch_id');
            });
        }
        if (Schema::hasTable('service_orders') && Schema::hasColumn('service_orders', 'branch_id')) {
            Schema::table('service_orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('branch_id');
            });
        }
        Schema::dropIfExists('branches');
    }
};

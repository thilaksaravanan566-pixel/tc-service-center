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
            $table->string('customer_name')->nullable()->after('customer_id');
            $table->string('phone')->nullable()->after('customer_name');
            $table->string('email')->nullable()->after('phone');
            $table->text('address')->nullable()->after('email');
            $table->string('device_name')->nullable()->after('service_order_id');
            $table->string('technician')->nullable()->after('device_name');
            $table->decimal('subtotal', 10, 2)->default(0)->after('technician');
            $table->decimal('tax', 10, 2)->default(0)->after('subtotal');
            $table->decimal('discount', 10, 2)->default(0)->after('tax');
            // 'amount' already exists, we'll use it as total or rename it if preferred, 
            // but the instructions say 'total'. Let's rename 'amount' to 'total' if it exists.
            if (Schema::hasColumn('invoices', 'amount')) {
                $table->renameColumn('amount', 'total');
            } else {
                $table->decimal('total', 10, 2)->default(0)->after('discount');
            }
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'total')) {
                $table->renameColumn('total', 'amount');
            }
            $table->dropColumn(['customer_name', 'phone', 'email', 'address', 'device_name', 'technician', 'subtotal', 'tax', 'discount']);
        });
    }
};

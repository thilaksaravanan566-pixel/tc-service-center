<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Company Profiles Table
        if (!Schema::hasTable('company_profiles')) {
            Schema::create('company_profiles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->default('Your Company Name');
                $table->string('logo')->nullable();
                $table->text('address')->nullable();
                $table->string('gst_number')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }

        // Invoice Settings Table
        if (!Schema::hasTable('invoice_settings')) {
            Schema::create('invoice_settings', function (Blueprint $table) {
                $table->id();
                $table->string('header_text')->nullable();
                $table->text('footer_message')->nullable();
                $table->text('terms_conditions')->nullable();
                $table->boolean('show_hsn_sac')->default(true);
                $table->boolean('show_discount')->default(true);
                $table->boolean('show_tax_breakup')->default(true);
                $table->boolean('show_signature')->default(true);
                $table->string('invoice_prefix')->default('INV_');
                $table->integer('invoice_number_length')->default(5);
                $table->integer('next_invoice_number')->default(1);
                $table->string('theme_color')->default('#4f46e5'); // Indigo-600
                $table->string('font_size')->default('14px');
                $table->string('default_template')->default('standard');
                $table->boolean('auto_reset_fy')->default(false);
                $table->timestamps();
            });
        }

        // Add columns to invoices table
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'cgst_amount')) {
                $table->decimal('cgst_amount', 10, 2)->default(0)->after('gst_amount');
            }
            if (!Schema::hasColumn('invoices', 'sgst_amount')) {
                $table->decimal('sgst_amount', 10, 2)->default(0)->after('cgst_amount');
            }
            if (!Schema::hasColumn('invoices', 'igst_amount')) {
                $table->decimal('igst_amount', 10, 2)->default(0)->after('sgst_amount');
            }
            if (!Schema::hasColumn('invoices', 'round_off')) {
                $table->decimal('round_off', 10, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('invoices', 'is_draft')) {
                $table->boolean('is_draft')->default(false)->after('status');
            }
            if (!Schema::hasColumn('invoices', 'qr_code_path')) {
                $table->string('qr_code_path')->nullable()->after('pdf_path');
            }
            if (!Schema::hasColumn('invoices', 'barcode_path')) {
                $table->string('barcode_path')->nullable()->after('qr_code_path');
            }
            if (!Schema::hasColumn('invoices', 'template_name')) {
                $table->string('template_name')->default('standard')->after('barcode_path');
            }
        });

        // Add columns to invoice_items table
        Schema::table('invoice_items', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_items', 'hsn_sac')) {
                $table->string('hsn_sac')->nullable()->after('description');
            }
            if (!Schema::hasColumn('invoice_items', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('price');
            }
            if (!Schema::hasColumn('invoice_items', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->default(0)->after('discount_amount');
            }
            if (!Schema::hasColumn('invoice_items', 'tax_percentage')) {
                $table->decimal('tax_percentage', 5, 2)->default(0)->after('discount_percentage');
            }
            if (!Schema::hasColumn('invoice_items', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->default(0)->after('tax_percentage');
            }
            if (!Schema::hasColumn('invoice_items', 'cgst_amount')) {
                $table->decimal('cgst_amount', 10, 2)->default(0)->after('tax_amount');
            }
            if (!Schema::hasColumn('invoice_items', 'sgst_amount')) {
                $table->decimal('sgst_amount', 10, 2)->default(0)->after('cgst_amount');
            }
            if (!Schema::hasColumn('invoice_items', 'igst_amount')) {
                $table->decimal('igst_amount', 10, 2)->default(0)->after('sgst_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
        Schema::dropIfExists('invoice_settings');
        
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'cgst_amount', 'sgst_amount', 'igst_amount', 'round_off', 
                'is_draft', 'qr_code_path', 'barcode_path', 'template_name'
            ]);
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn([
                'hsn_sac', 'discount_amount', 'discount_percentage', 
                'tax_percentage', 'tax_amount', 'cgst_amount', 'sgst_amount', 'igst_amount'
            ]);
        });
    }
};

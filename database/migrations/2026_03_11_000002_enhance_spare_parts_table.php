<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enrich spare_parts table with full e-commerce catalog fields
        Schema::table('spare_parts', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('name');
            $table->string('sku')->nullable()->unique()->after('brand');
            $table->text('description')->nullable()->after('sku');
            $table->integer('warranty_months')->default(0)->after('stock');
            $table->json('gallery_images')->nullable()->after('image_path'); // multiple images
            $table->boolean('is_active')->default(true)->after('gallery_images');
            $table->integer('sort_order')->default(0)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('spare_parts', function (Blueprint $table) {
            $table->dropColumn(['brand', 'sku', 'description', 'warranty_months', 'gallery_images', 'is_active', 'sort_order']);
        });
    }
};

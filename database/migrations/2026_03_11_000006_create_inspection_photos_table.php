<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->enum('photo_type', ['exterior', 'ram', 'storage', 'processor', 'motherboard', 'other'])->default('other');
            $table->string('photo_path');
            $table->string('label')->nullable(); // human readable description
            $table->text('notes')->nullable();
            $table->enum('inspection_stage', ['pre_repair', 'post_repair'])->default('pre_repair');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_photos');
    }
};

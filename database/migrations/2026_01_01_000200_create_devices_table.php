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
        Schema::create('devices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained()->onDelete('cascade');
    $table->string('type'); 
    $table->string('brand');
    $table->string('model');
    $table->string('serial_number')->nullable(); // Fixes "Column not found" error
    $table->string('processor')->nullable();
    $table->string('ram_old')->nullable();
    $table->string('storage_old')->nullable();
    $table->text('damage_photos')->nullable(); // Stores array of photo paths
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
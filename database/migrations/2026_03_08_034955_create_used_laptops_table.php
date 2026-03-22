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
        Schema::create('used_laptops', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->string('processor');
            $table->string('ram');
            $table->string('storage');
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(1);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('available'); // available, sold
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('used_laptops');
    }
};

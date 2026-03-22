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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_form_id')->constrained('dynamic_forms')->cascadeOnDelete();
            $table->string('label');
            $table->string('name'); // input name
            $table->string('type'); // text, select, file, radio, checkbox, etc.
            $table->json('options')->nullable(); // For select/radio configs
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};

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
        Schema::table('tech_players', function (Blueprint $table) {
            $table->bigInteger('coins')->default(500);
            $table->json('tycoon_state')->nullable(); // Store upgrades, techs, etc.
        });
    }

    public function down(): void
    {
        Schema::table('tech_players', function (Blueprint $table) {
            $table->dropColumn(['coins', 'tycoon_state']);
        });
    }
};

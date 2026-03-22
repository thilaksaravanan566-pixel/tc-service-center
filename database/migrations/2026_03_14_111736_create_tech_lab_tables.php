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
        // Tech Players (Gaming Profile)
        Schema::create('tech_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->integer('xp')->default(0);
            $table->integer('level')->default(1);
            $table->integer('games_played')->default(0);
            $table->float('success_rate')->default(0);
            $table->timestamps();
        });

        // Tech Scores
        Schema::create('tech_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('game_type'); // pc_build, laptop_repair, etc.
            $table->integer('score');
            $table->integer('time_seconds')->nullable();
            $table->timestamps();
        });

        // Tech Badges
        Schema::create('tech_badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Player Badges (Pivot)
        Schema::create('tech_player_badge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->constrained('tech_badges')->onDelete('cascade');
            $table->timestamp('unlocked_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_player_badge');
        Schema::dropIfExists('tech_badges');
        Schema::dropIfExists('tech_scores');
        Schema::dropIfExists('tech_players');
    }
};

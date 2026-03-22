<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add description and icon columns to feature_toggles table.
     */
    public function up(): void
    {
        Schema::table('feature_toggles', function (Blueprint $table) {
            if (!Schema::hasColumn('feature_toggles', 'description')) {
                $table->string('description')->nullable()->after('group');
            }
            if (!Schema::hasColumn('feature_toggles', 'icon')) {
                $table->string('icon')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('feature_toggles', function (Blueprint $table) {
            $table->dropColumn(['description', 'icon']);
        });
    }
};

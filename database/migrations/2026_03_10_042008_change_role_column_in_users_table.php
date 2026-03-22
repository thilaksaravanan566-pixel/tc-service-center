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
        // First drop the enum constraint by changing to generic string
        // Note: doctrine/dbal is required for altering columns
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('technician')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Because of the data loss risk when reverting to ENUM ('admin', 'technician') with 'delivery_partner' entries,
        // it is safer to leave as string on rollback or explicitly handle value replacements if absolutely needed.
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('technician')->change();
        });
    }
};

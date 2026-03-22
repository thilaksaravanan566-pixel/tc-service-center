<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to change the enum. 
        // For SQLite (testing), it might be different.
        // Since Laravel 11+ uses DBAL or native Schema methods better, let's try clean approach.
        
        // However, updating ENUMs is notoriously tricky in some DBs. 
        // Let's use raw SQL if it's MySQL, or just assume we can change it.
        
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'technician', 'dealer', 'delivery') DEFAULT 'technician'");
        } catch (\Exception $e) {
            // Fallback for other drivers or if column doesn't exist yet as enum
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('technician')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'technician') DEFAULT 'technician'");
        } catch (\Exception $e) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('technician')->change();
            });
        }
    }
};

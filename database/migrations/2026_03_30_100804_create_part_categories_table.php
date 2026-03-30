<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('part_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('status')->default('active'); // active / inactive
            $table->timestamps();
        });

        // Insert legacy default categories to maintain backward compatibility smoothly
        $defaults = [
            'RAM Memory', 'Storage (SSD/HDD)', 'Display / Screen', 'Battery', 'Keyboard',
            'Motherboard / Logic Board', 'Processor / CPU', 'Charger / Adapter', 'Cables & Connectors',
            'Monitor', 'Desktop Cabinet', 'CCTV Camera', 'IC', 'IO IC', 'GPU / Graphics Card',
            'Thermal Paste', 'Webcams', 'Speakers', 'A, B, C Panels', 'Mouse', 'Wireless KB & Mouse',
            'CMOS Battery', 'Other Component'
        ];

        foreach ($defaults as $name) {
            DB::table('part_categories')->insert([
                'name' => $name,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('part_categories');
    }
};

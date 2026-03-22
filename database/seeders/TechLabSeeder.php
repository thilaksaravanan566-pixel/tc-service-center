<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TechBadge;

class TechLabSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Junior Technician',
                'slug' => 'junior-tech',
                'description' => 'Completed your first hardware simulation.',
                'icon' => '🛠️'
            ],
            [
                'name' => 'Hardware Builder',
                'slug' => 'hardware-builder',
                'description' => 'Successfully assembled a full PC nodal system.',
                'icon' => '🏗️'
            ],
            [
                'name' => 'Laptop Surgeon',
                'slug' => 'laptop-surgeon',
                'description' => 'Completed a complex laptop thermal repair surgery.',
                'icon' => '🔪'
            ],
            [
                'name' => 'Cable Master',
                'slug' => 'cable-master',
                'description' => 'Achieved perfect cable management in builder mode.',
                'icon' => '🔌'
            ],
            [
                'name' => 'Troubleshooting Expert',
                'slug' => 'troubleshoot-expert',
                'description' => 'Solved 5 consecutive critical hardware anomalies.',
                'icon' => '🧠'
            ],
        ];

        foreach ($badges as $badge) {
            TechBadge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}

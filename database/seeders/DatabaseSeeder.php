<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Calling all seeders to populate TC Service Center
        $this->call([
            AdminSeeder::class,
            SparePartSeeder::class,
            CustomizationSeeder::class,
            NotificationTemplateSeeder::class,
        ]);
    }
}
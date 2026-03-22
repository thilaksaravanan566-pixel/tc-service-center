<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create the TC Admin
        User::updateOrCreate(
            ['email' => 'admin@tc.com'],
            [
                'name' => 'TC Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Create a Technician
        User::updateOrCreate(
            ['email' => 'tech@tc.com'],
            [
                'name' => 'TC Technician',
                'password' => Hash::make('tech123'),
                'role' => 'technician',
            ]
        );
    }
}
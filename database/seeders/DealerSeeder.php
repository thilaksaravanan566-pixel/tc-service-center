<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dealer;
use Illuminate\Support\Facades\Hash;

class DealerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a User with the 'dealer' role
        /** @var User $user */
        $user = User::updateOrCreate(
            ['email' => 'dealer@tc.com'],
            [
                'name' => 'John Dealer',
                'password' => Hash::make('dealer123'),
                'role' => 'dealer',
                'phone' => '9887766554',
                'address' => 'TC City, Main Road, Block 4',
            ]
        );

        // 2. Create the Dealer profile for that User
        Dealer::updateOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => 'Blue Ocean Hardware',
                'phone' => '9887766554',
                'address' => 'TC City, Main Road, Block 4',
                'gst_number' => 'GSTIN987654321',
                'status' => 'active',
            ]
        );
    }
}

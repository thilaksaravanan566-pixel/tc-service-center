<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SparePart;
use App\Models\User;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\ProductOrder;
use App\Models\ServiceOrder;
use App\Models\Device;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SuperAppSeeder extends Seeder
{
    public function run(): void
    {
        // 1. PHASE 22: Multi-Branch Setup
        /** @var Branch $branch */
        $branch = Branch::firstOrCreate(
            ['name' => 'Main Service Center'],
            [
                'address' => 'Downtown Circuit', 
                'city' => 'Metropolis',
                'phone' => '9876543210',
                'email' => 'contact@tcservice.com'
            ]
        );

        // 2. PHASE 18: HRM / Roles Setup
        /** @var User $admin */
        $admin = User::firstOrCreate(
            ['email' => 'admin@tcservice.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'branch_id' => $branch->id,
                'salary' => 80000,
                'biometric_id' => 'BIO-001'
            ]
        );

        /** @var User $technician */
        $technician = User::firstOrCreate(
            ['email' => 'tech@tcservice.com'],
            [
                'name' => 'Lead Technician',
                'password' => Hash::make('password123'),
                'role' => 'technician',
                'branch_id' => $branch->id,
                'salary' => 45000,
                'biometric_id' => 'BIO-002'
            ]
        );

        /** @var User $delivery */
        $delivery = User::firstOrCreate(
            ['email' => 'delivery@tcservice.com'],
            [
                'name' => 'Logistics Agent',
                'password' => Hash::make('password123'),
                'role' => 'delivery_partner',
                'branch_id' => $branch->id,
                'salary' => 25000,
                'biometric_id' => 'BIO-003'
            ]
        );

        /** @var Customer $customer */
        $customer = Customer::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'VIP Customer',
                'username' => 'vip_customer',
                'mobile' => '9000000001',
                'password' => Hash::make('password123'),
                'address' => '123 Tech Avenue, City'
            ]
        );

        // 3. PHASE 9: SPARE PARTS MARKETPLACE (Exact Requested List)
        $parts = [
            ['name' => 'Samsung 1TB NVMe SSD', 'category' => 'SSD', 'price' => 6500, 'stock' => 50],
            ['name' => 'WD Blue 2TB HDD', 'category' => 'HDD', 'price' => 4200, 'stock' => 20],
            ['name' => 'Crucial 16GB DDR4 Laptop RAM', 'category' => 'Laptop RAM', 'price' => 2800, 'stock' => 45],
            ['name' => 'Corsair 32GB DDR5 Desktop RAM', 'category' => 'Desktop RAM', 'price' => 8500, 'stock' => 30],
            ['name' => 'Intel Core i7 13700K', 'category' => 'Processors', 'price' => 35000, 'stock' => 10],
            ['name' => 'ASUS ROG B650 Motherboard', 'category' => 'Motherboards', 'price' => 22000, 'stock' => 12],
            ['name' => 'Dell XPS 15 Replacement Screen', 'category' => 'Laptop screens', 'price' => 12500, 'stock' => 8],
            ['name' => 'Lenovo ThinkPad Backlit Keyboard', 'category' => 'Laptop keyboards', 'price' => 3200, 'stock' => 15],
            ['name' => 'HP 6-Cell Original Battery', 'category' => 'Laptop batteries', 'price' => 4500, 'stock' => 25],
            ['name' => 'Acer 65W Original Charger', 'category' => 'Laptop chargers', 'price' => 1800, 'stock' => 60],
            ['name' => 'NZXT H510 ATX Cabinet', 'category' => 'Desktop cabinets', 'price' => 7500, 'stock' => 14],
            ['name' => 'LG 27inch 4K IPS Monitor', 'category' => 'Monitors', 'price' => 28000, 'stock' => 7],
            ['name' => 'TP-Link AX3000 WiFi 6 Router', 'category' => 'Networking routers', 'price' => 4800, 'stock' => 40],
            ['name' => 'Cisco 24-Port Gigabit Switch', 'category' => 'Switches', 'price' => 14000, 'stock' => 5],
            ['name' => 'Ubiquiti UniFi AP AC Pro', 'category' => 'Access points', 'price' => 12000, 'stock' => 18],
            ['name' => 'Hikvision 2MP Dome Camera', 'category' => 'CCTV cameras', 'price' => 1500, 'stock' => 100],
            ['name' => 'Dahua 8-Channel DVR', 'category' => 'DVR', 'price' => 4500, 'stock' => 20],
            ['name' => 'Hikvision 16-Channel POE NVR', 'category' => 'NVR', 'price' => 11000, 'stock' => 15],
        ];

        foreach ($parts as $p) {
            SparePart::firstOrCreate(
                ['name' => $p['name']],
                [
                    'brand' => 'Generic',
                    'sku' => strtoupper(substr(md5($p['name']), 0, 8)),
                    'category' => $p['category'],
                    'price' => $p['price'],
                    'stock' => $p['stock'],
                    'is_active' => true,
                ]
            );
        }

        // 4. PHASE 10 & 14: SERVICE MANAGEMENT & TRACKING
        /** @var Device $device */
        $device = Device::firstOrCreate(
            ['customer_id' => $customer->id, 'model' => 'MacBook Pro M2'],
            ['type' => 'Laptop', 'brand' => 'Apple']
        );

        ServiceOrder::firstOrCreate(
            ['tc_job_id' => 'TC-2026-0001'],
            [
                'customer_id' => $customer->id,
                'device_id' => $device->id,
                'technician_id' => $technician->id,
                'status' => 'diagnosing',
                'fault_details' => 'Screen completely blank, no backlight.',
                'estimated_cost' => 15000,
                'is_paid' => false,
                'delivery_type' => 'delivery',
                'delivery_address' => $customer->address
            ]
        );

        // 5. PHASE 19: FINANCE MANAGEMENT (Expenses for Graph Data)
        for ($i = 0; $i < 5; $i++) {
            Expense::firstOrCreate(
                ['description' => "Monthly Shop Rent - Month " . (intval(date('m')) - $i)],
                [
                    'branch_id' => $branch->id,
                    'category' => 'rent',
                    'amount' => 15000,
                    'expense_date' => Carbon::now()->subMonths($i)->startOfMonth(),
                    'payment_mode' => 'bank_transfer',
                    'created_by' => $admin->id
                ]
            );
        }
    }
}

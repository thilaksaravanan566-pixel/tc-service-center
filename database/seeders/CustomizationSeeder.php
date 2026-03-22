<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'Thambu Computers Service Center'],
            ['key' => 'company_gst', 'value' => 'GSTIN123456789'],
            ['key' => 'support_phone', 'value' => '+94 77 123 4567'],
            ['key' => 'support_email', 'value' => 'support@tcservice.com'],
            ['key' => 'company_address', 'value' => '123 Tech Avenue, Colombo, Sri Lanka'],
            ['key' => 'theme_color', 'value' => '#4f46e5'],
            ['key' => 'dark_mode', 'value' => 'enabled'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        $toggles = [
            'store_module',
            'repair_module',
            'warranty_module',
            'delivery_module',
            'crm_module',
            'hrm_module',
            'marketing_module',
            'analytics_module'
        ];

        foreach ($toggles as $toggle) {
            \App\Models\FeatureToggle::updateOrCreate(['name' => $toggle], ['is_active' => true]);
        }

        // --- PHASE 6: ROLE & PERMISSIONS ---
        $permissions = [
            ['name' => 'Manage Orders', 'slug' => 'manage_orders', 'group' => 'sales'],
            ['name' => 'Manage Services', 'slug' => 'manage_services', 'group' => 'repairs'],
            ['name' => 'Manage Inventory', 'slug' => 'manage_inventory', 'group' => 'stock'],
            ['name' => 'View Customers', 'slug' => 'view_customers', 'group' => 'crm'],
            ['name' => 'Manage Customers', 'slug' => 'manage_customers', 'group' => 'crm'],
            ['name' => 'View Reports', 'slug' => 'view_reports', 'group' => 'finance'],
            ['name' => 'Manage Settings', 'slug' => 'manage_settings', 'group' => 'system'],
            ['name' => 'Manage Staff', 'slug' => 'manage_staff', 'group' => 'hrm'],
            ['name' => 'Manage Branches', 'slug' => 'manage_branches', 'group' => 'system'],
        ];

        foreach ($permissions as $perm) {
            \App\Models\Permission::updateOrCreate(['slug' => $perm['slug']], $perm);
        }

        $roles = [
            ['name' => 'Administrator', 'slug' => 'admin', 'description' => 'Unrestricted master control.'],
            ['name' => 'Technician', 'slug' => 'technician', 'description' => 'Handles repairs and services.'],
            ['name' => 'Delivery Partner', 'slug' => 'delivery_partner', 'description' => 'Handles logistics and deliveries.']
        ];

        foreach ($roles as $r) {
            /** @var \App\Models\Role $role */
            $role = \App\Models\Role::updateOrCreate(['slug' => $r['slug']], $r);
            if ($role->slug === 'admin') {
                $role->permissions()->sync(\App\Models\Permission::pluck('id')->toArray());
            } elseif ($role->slug === 'technician') {
                $role->permissions()->sync(\App\Models\Permission::whereIn('slug', ['manage_services', 'manage_inventory'])->pluck('id')->toArray());
            }
        }
    }
}

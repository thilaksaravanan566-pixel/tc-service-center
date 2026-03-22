<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SuperAppStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_architecture_status(): void
    {
        $response = $this->get('/');
        // Redirects to shop
        $response->assertStatus(302);
    }

    public function test_admin_dashboard_auth_protection(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_api_routes_are_active(): void
    {
        // Try to access to trigger unauthenticated Sanctum error, 
        // verifying the API routes are properly registered and running.
        $response = $this->json('GET', '/api/v1/customer/profile');
        $response->assertStatus(401);
    }

    public function test_super_app_database_seeding_structure(): void
    {
        // Ensure Database can load the user setup
        $this->artisan('db:seed', ['--class' => 'SuperAppSeeder'])->assertExitCode(0);

        // Verify Branch Exists
        $this->assertDatabaseHas('branches', [
            'name' => 'Main Service Center',
        ]);

        // Verify Roles Inserted
        $this->assertDatabaseHas('users', [
            'email' => 'admin@tcservice.com',
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'tech@tcservice.com',
            'role' => 'technician',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'delivery@tcservice.com',
            'role' => 'delivery_partner',
        ]);

        // Verify Inventory Synced
        $this->assertDatabaseCount('spare_parts', 18);
        $this->assertDatabaseHas('spare_parts', [
            'name' => 'Samsung 1TB NVMe SSD'
        ]);

        // Verify Finance Synced
        $this->assertDatabaseCount('expenses', 5);
        $this->assertDatabaseHas('expenses', [
            'category' => 'rent'
        ]);
    }
}

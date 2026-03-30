<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DealerCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_create_page()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_'.uniqid().'@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
        
        $response = $this->actingAs($admin)->get('/admin/dealers/create');
        
        if ($response->status() >= 500) {
            echo "Exception: " . $response->exception->getMessage() . "\n";
            echo $response->exception->getTraceAsString() . "\n";
        }
        
        $response->assertStatus(200);
    }
}

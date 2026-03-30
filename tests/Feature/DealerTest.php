<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DealerTest extends TestCase
{
    public function test_admin_can_add_dealer()
    {
        $admin = User::firstOrCreate(['email' => 'admin@test.com'], [
            'name' => 'Admin Test',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
        
        $this->actingAs($admin);
        
        $response = $this->post('/admin/dealers', [
            'name' => 'Test Dealer',
            'business_name' => 'Test Business',
            'email' => uniqid() . 'dealer@test.com',
            'phone' => '1234567890',
            'address' => 'Test Address',
            'password' => 'password123',
            'gst_number' => 'GST123'
        ]);
        
        echo "Status: " . $response->status() . "\n";
        if ($response->status() == 500) {
            file_put_contents('error_test.html', $response->getContent());
        }
    }
}

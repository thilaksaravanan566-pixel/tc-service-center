<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginDebug_Test extends TestCase
{
    public function test_login_issue()
    {
        $user = User::where('email', 'admin@tc.com')->first();
        
        $response = $this->post('/login', [
            'email' => 'admin@tc.com',
            'password' => 'password123',
        ]);
        
        // Output response to console
        echo "Status: " . $response->status() . "\n";
        echo "Redirect: " . $response->headers->get('Location') . "\n";
        
        $response->assertStatus(302);
    }
}

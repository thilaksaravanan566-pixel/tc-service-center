<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$admin = \App\Models\User::where('role', 'admin')->first();

$request = Illuminate\Http\Request::create('/admin/dealers', 'POST', [
    'name' => 'Dealer HTTP Test',
    'business_name' => 'Test Business HTTP',
    'email' => uniqid() . 'dealer@example.com',
    'phone' => '1234567890',
    'address' => 'Test Address HTTP',
    'password' => 'password123',
]);
if ($admin) {
    \Illuminate\Support\Facades\Auth::login($admin);
}

$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() == 500) {
    file_put_contents('error_output.html', $response->getContent());
    echo "Saved to error_output.html\n";
}
$kernel->terminate($request, $response);

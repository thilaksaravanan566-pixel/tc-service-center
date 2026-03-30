<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$admin = \App\Models\User::where('role', 'admin')->first();

$request = Illuminate\Http\Request::create('/admin/dealers/create', 'GET');
if ($admin) {
    \Illuminate\Support\Facades\Auth::login($admin);
}

$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() >= 500) {
    echo $response->getContent();
}
$kernel->terminate($request, $response);

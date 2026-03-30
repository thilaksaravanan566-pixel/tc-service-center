<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = \App\Models\User::where('role', 'admin')->first();
if ($admin) {
    \Illuminate\Support\Facades\Auth::login($admin);
}

try {
    $view = view('admin.dealers.create')->render();
    echo "Create View Rendered! Length: " . strlen($view) . "\n";
} catch (\Throwable $e) {
    file_put_contents('create_error.txt', $e->getMessage() . "\n" . $e->getTraceAsString());
}

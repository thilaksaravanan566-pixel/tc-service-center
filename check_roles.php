<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$driver = \Illuminate\Support\Facades\DB::getDriverName();
echo "DB Driver: $driver\n\n";

// Check all users with delivery-related roles
$users = \Illuminate\Support\Facades\DB::select("SELECT id, name, email, role, created_at FROM users WHERE role LIKE '%delivery%' OR role LIKE '%dealer%'");
if (empty($users)) {
    echo "NO delivery/dealer users found!\n";
    // Check all roles present
    $roles = \Illuminate\Support\Facades\DB::select("SELECT DISTINCT role, COUNT(*) as count FROM users GROUP BY role");
    echo "\nAll roles in DB:\n";
    foreach ($roles as $r) echo "  role='{$r->role}' count={$r->count}\n";
} else {
    echo "Delivery/Dealer users found:\n";
    foreach ($users as $u) {
        echo "  id={$u->id} name='{$u->name}' email='{$u->email}' role='{$u->role}' created={$u->created_at}\n";
    }
}

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dealers = \App\Models\Dealer::with('user')->get();
$orphans = 0;
foreach ($dealers as $dealer) {
    if (!$dealer->user) {
        $orphans++;
        echo "Orphan dealer found! ID: " . $dealer->id . "\n";
    }
}
if ($orphans == 0) echo "No orphans found.\n";

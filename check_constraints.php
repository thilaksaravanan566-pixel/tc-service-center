<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$driver = \Illuminate\Support\Facades\DB::getDriverName();
if ($driver == 'mysql') {
    $columns = \Illuminate\Support\Facades\DB::select("DESCRIBE users");
    foreach($columns as $c) echo $c->Field . " | Null: " . $c->Null . " | Default: " . $c->Default . "\n";
} elseif ($driver == 'pgsql') {
    $columns = \Illuminate\Support\Facades\DB::select("SELECT column_name, is_nullable, column_default FROM information_schema.columns WHERE table_name = 'users'");
    foreach($columns as $c) echo $c->column_name . " | Null: " . $c->is_nullable . " | Default: " . $c->column_default . "\n";
}

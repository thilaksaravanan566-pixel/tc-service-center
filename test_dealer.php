<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = \Illuminate\Support\Facades\Schema::getColumnListing('dealers');
print_r($columns);

$userColumns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
print_r($userColumns);

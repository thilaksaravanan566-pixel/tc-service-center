<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userColumns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
$dealerColumns = \Illuminate\Support\Facades\Schema::getColumnListing('dealers');

file_put_contents('schema_check.txt', "USERS:\n" . implode(', ', $userColumns) . "\n\nDEALERS:\n" . implode(', ', $dealerColumns));

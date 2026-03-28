<?php
$request = Request::create('/login', 'POST', [
    'email' => 'tech@tc.com',
    'password' => 'tech123',
    '_token' => csrf_token(),
]);
$response = app()->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Location: " . $response->headers->get('Location') . "\n";

$isAuth = Auth::check() ? 'Yes' : 'No';
echo "Auth: " . $isAuth . "\n";

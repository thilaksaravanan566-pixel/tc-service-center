<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/**
 * TC Service Center - Authentication Routes
 * These routes handle the luxury login experience and session management.
 */

Route::middleware('guest')->group(function () {
    // Show the Luxury Login Form
    Route::get('login', [LoginController::class, 'showLoginForm'])
                ->name('login');

    // Handle Login Attempt
    Route::post('login', [LoginController::class, 'login']);

    // Forgot Password
    Route::get('forgot-password', [LoginController::class, 'showForgotPasswordForm'])
                ->name('password.request');
    Route::post('forgot-password', [LoginController::class, 'sendResetLink'])
                ->name('password.email');
});

Route::middleware('auth')->group(function () {
    // Handle Logout
    Route::post('logout', [LoginController::class, 'destroy'])
                ->name('logout');
});
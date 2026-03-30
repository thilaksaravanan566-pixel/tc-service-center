<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/**
 * TC Service Center - Authentication Routes
 * These routes handle the luxury login experience and session management.
 */

Route::middleware('guest')->group(function () {
    // Show the Luxury Login Form (Admin default)
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    
    // Role-specific login pages
    Route::get('dealer/login', [LoginController::class, 'showDealerLoginForm'])->name('login.dealer');
    Route::get('technician/login', [LoginController::class, 'showTechnicianLoginForm'])->name('login.technician');
    Route::get('delivery/login', [LoginController::class, 'showDeliveryLoginForm'])->name('login.delivery');

    // Handle Login Attempt (Global)
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
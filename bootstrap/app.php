<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| TC Service Center - Application Bootstrap
|--------------------------------------------------------------------------
|
| This file configures the routing and middleware for the application.
| It maps the 'admin' alias to your custom AdminMiddleware.
|
*/

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Subdomain routing for thambucomputers.com
            Route::middleware('web')
                ->group(base_path('routes/subdomains.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        // 1. Register Role Aliases
        $middleware->alias([
            'admin'            => \App\Http\Middleware\AdminMiddleware::class,
            'technician'       => \App\Http\Middleware\TechnicianMiddleware::class,
            'dealer'           => \App\Http\Middleware\CheckDealer::class,
            'delivery_partner' => \App\Http\Middleware\DeliveryMiddleware::class,
        ]);

        // 2. Global Middleware (Optional: Add common settings here)
        $middleware->validateCsrfTokens(except: [
            '/customer/login', // Avoid 419/418 Page Expired during external API testing on web auth
        ]);

        $middleware->redirectGuestsTo('/login');

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            $user = $request->user();
            if ($user && isset($user->role)) {
                if ($user->role === 'admin') return '/admin/dashboard';
                if ($user->role === 'technician') return '/technician/dashboard';
                if ($user->role === 'dealer') return '/dealer/dashboard';
                return '/';
            }
            // If it's a customer (using customer guard) or guest
            return route('customer.dashboard');
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle custom error pages for a luxury experience
    })->create();
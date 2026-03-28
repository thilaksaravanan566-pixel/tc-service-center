<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DeliveryMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $role = Auth::user()->role ?? '';

        if (in_array($role, ['delivery_partner', 'delivery', 'admin'])) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Access Denied: Delivery Partner privileges required.');
    }
}

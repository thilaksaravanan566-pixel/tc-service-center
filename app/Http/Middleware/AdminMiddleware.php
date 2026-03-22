<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if the user is even logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Check if the logged-in user has the 'admin' role
        // This matches the 'role' column we created in your Migration/User Model
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        // 3. If they are a technician trying to enter Admin areas, redirect them
        return redirect('/')->with('error', 'Access Denied: You do not have Administrative privileges.');
    }
}
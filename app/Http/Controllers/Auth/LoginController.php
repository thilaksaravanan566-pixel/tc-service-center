<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Ensure this view exists in resources/views/auth/login.blade.php
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            return match($role) {
                'admin'            => redirect()->intended('/admin/dashboard'),
                'dealer'           => redirect()->intended('/dealer/dashboard'),
                'delivery_partner',
                'delivery'         => redirect()->intended('/delivery-partner'),
                'technician'       => redirect()->intended('/technician/dashboard'),
                default            => redirect()->intended('/admin/dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        // Simulated luxury email send for Techs and Admins
        return back()->with('status', 'Administrative reset instructions dispatched securely.');
    }
}
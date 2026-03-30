<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(config('custom.company_favicon'))
        <link rel="icon" href="{{ asset('storage/' . config('custom.company_favicon')) }}" type="image/x-icon">
    @endif
    <title>Customer Login | {{ config('custom.company_name', 'TC Service Center') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0f0f1a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated background orbs */
        body::before {
            content: '';
            position: fixed;
            top: -30%;
            left: -20%;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%);
            animation: float-orb 12s ease-in-out infinite;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139,92,246,0.14) 0%, transparent 70%);
            animation: float-orb 16s ease-in-out infinite reverse;
            pointer-events: none;
        }
        @keyframes float-orb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.05); }
            66% { transform: translate(-20px, 30px) scale(0.97); }
        }

        .login-wrapper {
            width: 100%;
            max-width: 960px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 28px;
            overflow: hidden;
            backdrop-filter: blur(20px);
            box-shadow: 0 40px 100px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.04);
            position: relative;
            z-index: 1;
        }

        /* Left visual panel */
        .visual-panel {
            background: linear-gradient(145deg, #1a1040 0%, #0d0d1e 60%, #111827 100%);
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .visual-panel::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.25) 0%, transparent 70%);
        }
        .visual-panel::after {
            content: '';
            position: absolute;
            bottom: -40px; left: 20px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139,92,246,0.18) 0%, transparent 70%);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }
        .logo-mark {
            width: 44px; height: 44px;
            border-radius: 14px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 900;
            color: #fff;
            box-shadow: 0 8px 24px rgba(99,102,241,0.35);
        }
        .brand-name { font-size: 0.95rem; font-weight: 700; color: #fff; }
        .brand-sub  { font-size: 0.72rem; color: rgba(255,255,255,0.4); margin-top: 2px; }

        .visual-content { position: relative; z-index: 1; }
        .visual-headline {
            font-size: 2rem;
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 12px;
        }
        .visual-headline span {
            background: linear-gradient(135deg, #a5b4fc, #c4b5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .visual-sub {
            font-size: 0.83rem;
            color: rgba(255,255,255,0.45);
            line-height: 1.65;
        }

        .feature-list { position: relative; z-index: 1; }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        .feature-icon {
            width: 34px; height: 34px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .feature-icon svg { width: 16px; height: 16px; color: #a5b4fc; }
        .feature-label { font-size: 0.8rem; color: rgba(255,255,255,0.5); font-weight: 500; }

        /* Right form panel */
        .form-panel {
            background: #ffffff;
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            font-size: 1.7rem;
            font-weight: 800;
            color: #0f0f1a;
            letter-spacing: -0.03em;
            margin-bottom: 6px;
        }
        .form-subtitle { font-size: 0.84rem; color: #6b7280; margin-bottom: 28px; }

        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 7px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 17px; height: 17px;
            color: #9ca3af;
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 14px 12px 42px;
            font-size: 0.875rem;
            color: #111827;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
            outline: none;
        }
        .form-input::placeholder { color: #d1d5db; }
        .form-input:focus {
            background: #fff;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .remember-label input { accent-color: #6366f1; width: 15px; height: 15px; }
        .remember-label span { font-size: 0.8rem; color: #6b7280; }
        .forgot-link { font-size: 0.8rem; color: #6366f1; text-decoration: none; font-weight: 600; }
        .forgot-link:hover { text-decoration: underline; }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 0.9rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 6px 20px rgba(99,102,241,0.35);
            letter-spacing: 0.01em;
        }
        .btn-submit:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(99,102,241,0.4);
        }
        .btn-submit:active { transform: translateY(0); }

        .divider {
            text-align: center;
            font-size: 0.78rem;
            color: #9ca3af;
            margin: 18px 0;
            position: relative;
        }
        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #e5e7eb;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }

        .register-text {
            text-align: center;
            font-size: 0.82rem;
            color: #6b7280;
        }
        .register-link { color: #6366f1; font-weight: 700; text-decoration: none; }
        .register-link:hover { text-decoration: underline; }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 0.82rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
        }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 0.82rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .login-wrapper { grid-template-columns: 1fr; border-radius: 20px; }
            .visual-panel { display: none; }
            .form-panel { padding: 36px 28px; }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">

        {{-- Left Visual Panel --}}
        <div class="visual-panel">
            <div class="brand-logo">
                @if(config('custom.company_logo'))
                    <img src="{{ asset('storage/' . config('custom.company_logo')) }}" alt="Logo" style="height:44px;width:auto;object-fit:contain">
                @else
                    <div class="logo-mark">TC</div>
                @endif
                <div>
                    <div class="brand-name">{{ config('custom.company_name', 'Thambu Computers') }}</div>
                    <div class="brand-sub">Customer Portal</div>
                </div>
            </div>

            <div class="visual-content">
                <h2 class="visual-headline">
                    Your devices,<br>
                    <span>always in safe hands.</span>
                </h2>
                <p class="visual-sub">
                    Sign in to track your repairs in real-time, shop spare parts, and manage your warranty — all from one dashboard.
                </p>
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="feature-label">Real-time repair status tracking</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <span class="feature-label">Shop genuine spare parts</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="feature-label">Download invoices & warranties</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <span class="feature-label">AI support assistant 24/7</span>
                </div>
            </div>
        </div>

        {{-- Right Form Panel --}}
        <div class="form-panel">
            <h1 class="form-title">Sign In</h1>
            <p class="form-subtitle">Access your Thambu Computers account</p>

            @if ($errors->any())
                <div class="alert-error">
                    <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert-success">
                    <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('customer.login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               required autocomplete="email" placeholder="you@example.com"
                               class="form-input">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input id="password" type="password" name="password"
                               required autocomplete="current-password" placeholder="••••••••"
                               class="form-input">
                    </div>
                </div>

                <div class="form-footer">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" id="remember-me">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('customer.password.request') }}" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" id="login-submit-btn" class="btn-submit">
                    Sign In to Your Account
                </button>

                <div class="divider">or</div>

                <p class="register-text">
                    Don't have an account?
                    <a href="{{ route('customer.register') }}" class="register-link">Create one free →</a>
                </p>

                <p style="text-align:center;margin-top:20px">
                    <a href="{{ route('login') }}" style="font-size:0.75rem;color:#9ca3af;text-decoration:none">← Admin / Staff Login</a>
                </p>
            </form>
        </div>
    </div>

@include('partials.password-toggle')
</body>
</html>

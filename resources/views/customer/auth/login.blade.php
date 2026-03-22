<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(config('custom.company_favicon'))
        <link rel="icon" href="{{ asset('storage/' . config('custom.company_favicon')) }}" type="image/x-icon">
    @endif
    <title>Sign In | {{ config('custom.company_name', 'TC Service Center') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .brand-panel {
            background: #1e3a8a;
            position: relative;
            overflow: hidden;
        }
        .brand-panel::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -40px; left: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }

        .auth-input {
            width: 100%;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 11px 14px 11px 42px;
            font-size: 0.875rem;
            color: #1f2937;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
        }
        .auth-input::placeholder { color: #9ca3af; }
        .auth-input:focus {
            background: #ffffff;
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30,58,138,0.08);
        }

        .btn-login {
            width: 100%;
            background: #1e3a8a;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background 0.15s, box-shadow 0.15s;
        }
        .btn-login:hover {
            background: #152d6e;
            box-shadow: 0 4px 12px rgba(30,58,138,0.3);
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            width: 18px; height: 18px;
        }
    </style>
</head>
<body>

    <div class="login-card w-full max-w-4xl flex flex-col md:flex-row mx-4" style="min-height:600px;">

        {{-- Left Brand Panel --}}
        <div class="brand-panel md:w-5/12 p-10 flex flex-col justify-between relative z-10">
            <div>
                {{-- Logo --}}
                <div class="flex items-center gap-3 mb-12">
                    @if(config('custom.company_logo'))
                        <img src="{{ asset('storage/' . config('custom.company_logo')) }}" alt="Logo"
                             class="h-10 w-auto object-contain">
                    @else
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-base"
                             style="background:rgba(255,255,255,0.15)">TC</div>
                    @endif
                    <div>
                        <p class="text-sm font-bold text-white leading-tight">{{ config('custom.company_name', 'Thambu Computers') }}</p>
                        <p class="text-xs text-white/50">Customer Portal</p>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-white leading-snug mb-4">
                    Welcome<br>Back
                </h2>
                <p class="text-sm text-white/60 leading-relaxed">
                    Sign in to manage your repairs, orders, and device warranty — all in one place.
                </p>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(255,255,255,0.1)">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <p class="text-xs text-white/60">Real-time repair tracking</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(255,255,255,0.1)">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <p class="text-xs text-white/60">Shop spare parts & laptops</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(255,255,255,0.1)">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-xs text-white/60">Download invoices & warranties</p>
                </div>
            </div>
        </div>

        {{-- Right Form Panel --}}
        <div class="md:w-7/12 p-10 lg:p-14 flex flex-col justify-center">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Sign In</h1>
                <p class="text-sm text-gray-500">Enter your credentials to access your account.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl flex items-center gap-3"
                     style="background:#fef2f2; border:1px solid #fecaca; color:#b91c1c">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span class="text-sm font-medium">{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl flex items-center gap-3"
                     style="background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('customer.login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email Address</label>
                    <div class="relative">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input id="login-email" type="email" name="email" value="{{ old('email') }}"
                               required autocomplete="email"
                               placeholder="you@example.com"
                               class="auth-input">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password</label>
                    <div class="relative">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input id="login-password" type="password" name="password"
                               required autocomplete="current-password"
                               placeholder="••••••••"
                               class="auth-input">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember-me"
                               class="w-4 h-4 rounded border-gray-300 accent-blue-700">
                        <span class="text-xs text-gray-600">Remember me</span>
                    </label>
                    <a href="{{ route('customer.password.request') }}"
                       class="text-xs font-medium hover:underline" style="color:#1e3a8a">Forgot password?</a>
                </div>

                <div class="pt-2">
                    <button type="submit" id="login-submit-btn" class="btn-login">
                        Sign In to Your Account
                    </button>
                </div>

                <p class="text-center text-sm text-gray-500 pt-2">
                    Don't have an account?
                    <a href="{{ route('customer.register') }}"
                       class="font-semibold hover:underline" style="color:#1e3a8a">Create one</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>

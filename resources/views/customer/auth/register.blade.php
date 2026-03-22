<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(config('custom.company_favicon'))
        <link rel="icon" href="{{ asset('storage/' . config('custom.company_favicon')) }}" type="image/x-icon">
    @endif
    <title>Create Account | {{ config('custom.company_name', 'TC Service Center') }}</title>
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
            padding: 24px;
        }

        .register-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.04);
            width: 100%;
            max-width: 540px;
            overflow: hidden;
        }

        .auth-input {
            width: 100%;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 11px 14px;
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

        .btn-register {
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
        .btn-register:hover {
            background: #152d6e;
            box-shadow: 0 4px 12px rgba(30,58,138,0.3);
        }

        label { display: block; font-size: 0.75rem; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
    </style>
</head>
<body>

    <div class="register-card">

        {{-- Header Band --}}
        <div class="px-10 py-7" style="background:#1e3a8a">
            <div class="flex items-center gap-3">
                @if(config('custom.company_logo'))
                    <img src="{{ asset('storage/' . config('custom.company_logo')) }}" alt="Logo"
                         class="h-8 w-auto object-contain">
                @else
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm"
                         style="background:rgba(255,255,255,0.15)">TC</div>
                @endif
                <div>
                    <p class="text-sm font-bold text-white leading-tight">{{ config('custom.company_name', 'Thambu Computers') }}</p>
                    <p class="text-xs text-white/50">Create your account</p>
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="p-8 lg:p-10">

            <h1 class="text-xl font-bold text-gray-800 mb-1">Create Account</h1>
            <p class="text-sm text-gray-500 mb-6">Fill in the details below to get started.</p>

            @if ($errors->any())
                <div class="mb-5 p-4 rounded-xl" style="background:#fef2f2; border:1px solid #fecaca; color:#b91c1c">
                    <p class="text-sm font-semibold mb-2">Please fix the following:</p>
                    <ul class="text-sm space-y-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('customer.register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="reg-name">Full Name</label>
                        <input id="reg-name" type="text" name="name" value="{{ old('name') }}"
                               required autofocus placeholder="John Doe"
                               class="auth-input">
                    </div>
                    <div>
                        <label for="reg-username">Username</label>
                        <input id="reg-username" type="text" name="username" value="{{ old('username') }}"
                               required placeholder="johndoe"
                               class="auth-input">
                    </div>
                </div>

                <div>
                    <label for="reg-email">Email Address</label>
                    <input id="reg-email" type="email" name="email" value="{{ old('email') }}"
                           required placeholder="you@example.com"
                           class="auth-input">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="reg-password">Password</label>
                        <input id="reg-password" type="password" name="password"
                               required placeholder="Min. 8 characters"
                               class="auth-input">
                    </div>
                    <div>
                        <label for="reg-password-confirm">Confirm Password</label>
                        <input id="reg-password-confirm" type="password" name="password_confirmation"
                               required placeholder="Repeat password"
                               class="auth-input">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="register-submit-btn" class="btn-register">
                        Create My Account
                    </button>
                </div>

                <p class="text-center text-sm text-gray-500 pt-1">
                    Already have an account?
                    <a href="{{ route('customer.login') }}"
                       class="font-semibold hover:underline" style="color:#1e3a8a">Sign in</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>

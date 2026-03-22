<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | Admin Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, hsla(353,100%,46%,0.2) 0px, transparent 50%),
                radial-gradient(at 100% 100%, hsla(349,89%,39%,0.3) 0px, transparent 50%),
                radial-gradient(at 100% 0%, hsla(217,100%,13%,0.6) 0px, transparent 50%);
            background-attachment: fixed;
            background-size: 200% 200%;
            animation: gradientMove 15s ease infinite alternate;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .input-luxury {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }

        .input-luxury:focus {
            background: rgba(255, 255, 255, 0.07);
            border-color: #dc2626;
            outline: none;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15);
        }

        .btn-animated {
            background-size: 200% auto;
            background-image: linear-gradient(to right, #dc2626 0%, #991b1b 51%, #dc2626 100%);
            transition: 0.5s;
        }

        .btn-animated:hover {
            background-position: right center;
            color: #fff;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md z-10">
        <div class="glass-panel p-10 rounded-[2.5rem] relative overflow-hidden group">
            
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-white tracking-tight mb-2">Staff Recovery</h2>
                <p class="text-sm font-medium text-slate-400">Dispatch a secure reset token to your corporate email.</p>
            </div>

            @if (session('status'))
                <div class="bg-green-500/10 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl backdrop-blur-sm">
                    <p class="text-[10px] font-black uppercase text-green-500 tracking-widest mb-1">Dispatched</p>
                    <p class="text-xs font-bold text-green-200">{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-500/10 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl backdrop-blur-sm">
                    <ul class="text-xs font-bold text-red-200 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 block ml-1">Admin / Tech Email</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="input-luxury w-full py-4 px-4 rounded-xl text-sm placeholder-slate-600 font-bold" placeholder="admin@tc.com">
                    </div>
                </div>

                <button type="submit" class="btn-animated w-full py-4 mt-2 rounded-xl font-black uppercase tracking-widest text-sm shadow-xl shadow-red-900/30 text-white">
                    Send Link
                </button>
            </form>
            
            <div class="mt-8 text-center border-t border-white/10 pt-6">
                 <p class="text-sm font-medium text-slate-400">
                    <a href="{{ route('login') }}" class="text-slate-500 hover:text-white font-bold ml-1 transition-colors">← Back to Staff Login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registry Recovery | Matrix Node</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: { 800: '#0f172a', 900: '#020617', 950: '#000000' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #000000;
            color: #f8fafc;
        }

        .matrix-bg {
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(2, 6, 23, 1) 0px, transparent 100%);
        }

        .portal-card {
            background: rgba(2, 6, 23, 0.6);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.8), inset 0 1px 1px rgba(255, 255, 255, 0.05);
            animation: cardEntrance 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(60px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .auth-input {
            background-color: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            color: #fff;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .auth-input:focus {
            background-color: rgba(99, 102, 241, 0.05);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .glow-button {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.3);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glow-button:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 25px 50px rgba(99, 102, 241, 0.5);
            filter: brightness(1.1);
        }

        .tech-grid {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
        }
    </style>
</head>
<body class="matrix-bg min-h-screen flex items-center justify-center p-6 md:p-12">

    <!-- Auth Interface Container -->
    <div class="portal-card w-full max-w-md rounded-[3rem] relative z-10 border-white/5 overflow-hidden">
        <div class="absolute inset-0 tech-grid opacity-20 pointer-events-none"></div>
        
        <div class="p-10 lg:p-16 relative z-20">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <div class="w-16 h-16 bg-slate-950 border border-white/5 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-2xl relative group overflow-hidden">
                    <div class="absolute inset-0 bg-indigo-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <svg class="w-8 h-8 text-indigo-400 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <h2 class="text-3xl font-black text-white mb-3 tracking-tighter">Registry Recovery</h2>
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-indigo-400/80">Re-initialize your access key</p>
                <div class="w-16 h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full mx-auto mt-6 shadow-[0_0_20px_rgba(99,102,241,0.5)]"></div>
            </div>

            @if (session('status'))
                <div class="mb-10 bg-emerald-500/10 border-l-4 border-emerald-500 p-6 rounded-r-2xl backdrop-blur-sm shadow-2xl animate-slide-up flex items-center gap-4">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400">{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-10 bg-red-500/10 border-l-4 border-red-500 p-6 rounded-r-2xl backdrop-blur-sm animate-shake">
                    <p class="text-[10px] font-black uppercase text-red-400 tracking-[0.3em] mb-3">Protocol Error</p>
                    <ul class="text-[11px] font-bold text-red-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <span class="w-1 h-1 bg-red-500 rounded-full"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('customer.password.email') }}" class="space-y-8">
                @csrf

                <div class="space-y-3 group">
                    <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-600 ml-2 group-focus-within:text-indigo-400 transition-colors">Communication Link (Email)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-6 pointer-events-none text-slate-600 group-focus-within:text-indigo-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="auth-input w-full text-sm font-bold rounded-2xl py-6 pl-16 pr-6 shadow-inner tracking-wide" placeholder="identity@matrix.node">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="glow-button w-full py-6 rounded-2xl font-black uppercase tracking-[0.4em] text-[11px] text-white flex justify-center items-center gap-4 group/btn">
                        DISPATCH RECOVERY LINK
                        <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                    
                    <div class="mt-12 text-center border-t border-white/5 pt-10">
                        <a href="{{ route('customer.login') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-600 hover:text-indigo-400 transition-all flex items-center justify-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to Access Terminal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Floating Background element -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[10%] -left-[10%] w-[60%] h-[60%] rounded-full bg-indigo-600/10 mix-blend-screen filter blur-[150px] animate-pulse"></div>
    </div>
</body>
</html>

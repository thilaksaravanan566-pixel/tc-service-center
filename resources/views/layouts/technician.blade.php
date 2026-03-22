<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(config('custom.company_favicon'))
        <link rel="icon" href="{{ asset('storage/' . config('custom.company_favicon')) }}" type="image/x-icon">
    @endif
    <title>TC Tech | {{ config('custom.company_name', 'Premium Service Dashboard') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        gold: {
                            400: '#FDE047',
                            500: '#EAB308',
                            600: '#CA8A04',
                            700: '#A16207',
                        },
                        dark: {
                            900: '#0a0a0f',
                            800: '#13131a',
                            700: '#1c1c24',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: #0a0a0f; 
            color: #e2e8f0;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(202, 138, 4, 0.05), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(202, 138, 4, 0.08), transparent 25%);
            background-attachment: fixed;
        }

        .glass-panel {
            background: rgba(19, 19, 26, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .glass-card {
            background: rgba(28, 28, 36, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            border-left: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        .text-gold-gradient {
            background: linear-gradient(to right, #FDE047, #CA8A04);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-gold { 
            background: linear-gradient(135deg, #CA8A04 0%, #A16207 100%);
            color: white; 
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(202, 138, 4, 0.3);
            border: 1px solid rgba(253, 224, 71, 0.3);
        }

        .btn-gold:hover { 
            box-shadow: 0 8px 25px rgba(202, 138, 4, 0.5); 
            background: linear-gradient(135deg, #EAB308 0%, #CA8A04 100%);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0a0a0f; }
        ::-webkit-scrollbar-thumb { background: rgba(202, 138, 4, 0.3); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #CA8A04; }
    </style>
</head>
<body class="antialiased text-gray-200">

    <div class="flex min-h-screen">
        <aside class="w-64 glass-panel text-white flex-shrink-0 hidden md:flex flex-col border-r border-white/5 relative z-20">
            <div class="p-6 border-b border-white/5 flex items-center gap-3">
                @if(config('custom.company_logo'))
                    <img src="{{ asset('storage/' . config('custom.company_logo')) }}" class="relative z-10 w-10 h-10 rounded-lg object-contain bg-white/5 p-1 drop-shadow" alt="Logo">
                @else
                    <div class="relative z-10 font-black text-3xl tracking-tighter text-white">
                        TC<span class="text-gold-500">.</span>
                    </div>
                @endif
                <div class="relative z-10 ml-2">
                    <h1 class="text-xs font-black tracking-widest uppercase text-gold-400 mt-1">Tech Panel</h1>
                </div>
            </div>
            
            <nav class="flex-1 p-4 space-y-2 mt-4">
                <a href="{{ route('technician.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-xl transition-all {{ request()->routeIs('technician.dashboard') ? 'bg-gold-500/10 text-gold-400 font-bold border border-gold-500/20 shadow-[0_0_15px_rgba(202,138,4,0.15)]' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Active Services</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center p-3 text-gray-400 hover:text-red-400 font-bold uppercase text-xs tracking-widest flex justify-center items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Secure Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden relative">
            <header class="glass-panel border-b border-white/5 p-4 flex justify-between items-center z-10">
                <div class="text-sm font-medium text-gray-400 uppercase tracking-widest">
                    Technician <span class="text-gold-500 font-black">Environment</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-bold text-gray-200">{{ auth()->user()->name }}</span>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-gold-600 to-gold-400 text-dark-900 flex items-center justify-center font-black shadow-[0_0_15px_rgba(202,138,4,0.4)] border border-gold-300">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-6 relative z-0">
                @yield('content')
            </div>
            
            <!-- Glow Effects -->
            <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
                <div class="absolute top-[20%] right-[-10%] w-[30%] h-[30%] rounded-full bg-gold-600/5 blur-[120px]"></div>
            </div>
        </main>
    </div>

</body>
</html>

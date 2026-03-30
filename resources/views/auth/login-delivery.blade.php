<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @if(config('custom.company_favicon'))
        <link rel="icon" href="{{ asset('storage/' . config('custom.company_favicon')) }}" type="image/x-icon">
    @endif
    <title>Delivery Portal | {{ config('custom.company_name', 'Thambu Computers') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Spartan:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Spartan', sans-serif; overflow: hidden; margin: 0; padding: 0; }
        .ambient-bg {
            background: linear-gradient(-45deg, #7c2d12, #9a3412, #c2410c, #ea580c, #eab308);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite alternate;
        }
        @keyframes gradientBG { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .floating-card { box-shadow: 0 40px 80px rgba(0,0,0,0.6); animation: floatIn 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; transform: translateY(40px); }
        @keyframes floatIn { to { opacity: 1; transform: translateY(0); } }
        .illustration-panel {
            background: url('https://images.unsplash.com/photo-1586528116311-ad8ed7a64a64?auto=format&fit=crop&q=80&w=1000') center right / cover no-repeat;
            position: relative;
        }
        .illustration-overlay { background: linear-gradient(180deg, rgba(124, 45, 18, 0.4) 0%, rgba(124, 45, 18, 0.95) 100%); position: absolute; inset: 0; }
        .glow-orb {
            position: absolute; top: 20%; left: 30%; width: 250px; height: 250px;
            background: rgba(249, 115, 22, 0.4); filter: blur(80px); border-radius: 50%;
            animation: pulseGlow 4s ease-in-out infinite alternate;
        }
        @keyframes pulseGlow { from { transform: scale(0.8); opacity: 0.6; } to { transform: scale(1.2); opacity: 1; } }
        .modern-input { background-color: #fff7ed; transition: all 0.3s ease; }
        .modern-input:focus { background-color: #ffffff; border-color: #f97316; box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1); }
        .btn-gradient {
            background: linear-gradient(90deg, #c2410c 0%, #f97316 100%);
            transition: all 0.4s ease; background-size: 200% auto;
        }
        .btn-gradient:hover { background-position: right center; box-shadow: 0 10px 20px -5px rgba(249, 115, 22, 0.5); transform: translateY(-2px); }
    </style>
</head>
<body class="ambient-bg min-h-screen flex items-center justify-center p-4 md:p-8">
    <div class="floating-card bg-white w-full max-w-5xl rounded-3xl flex flex-col md:flex-row overflow-hidden min-h-[550px] relative z-10">
        <div class="hidden md:block w-1/2 illustration-panel">
            <div class="illustration-overlay"></div>
            <div class="glow-orb"></div>
            <div class="absolute inset-0 flex flex-col justify-end p-12 text-white z-10">
                <div class="mb-4">
                    @if(config('custom.company_logo'))
                        <img src="{{ asset('storage/' . config('custom.company_logo')) }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=TC&background=f97316&color=fff&size=100&rounded=true&bold=true';" alt="Logo" class="h-12 w-auto object-contain opacity-90 drop-shadow-lg mb-4">
                    @else
                        <div class="h-10 w-10 bg-[#f97316] rounded-xl flex items-center justify-center text-white font-black text-2xl mb-4 shadow-lg">TC</div>
                    @endif
                </div>
                <h3 class="text-3xl font-black mb-3 tracking-tighter leading-tight">Fast Delivery<br>Logistics Hub</h3>
                <p class="text-xs font-semibold text-orange-200 leading-relaxed uppercase tracking-wider mb-2">TC Express Partner</p>
                <div class="w-12 h-1 bg-orange-500 rounded-full"></div>
            </div>
            <div class="absolute inset-0 z-0 opacity-20" style="background-image: linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 20px 20px;"></div>
        </div>

        <div class="w-full md:w-1/2 p-10 lg:p-16 flex flex-col justify-center bg-white relative">
            <h2 class="text-4xl font-black text-[#7c2d12] mb-2 tracking-tighter">DRIVER LOGIN</h2>
            <p class="text-[11px] uppercase tracking-widest text-slate-400 mb-10 font-bold">Access live routes & logistics.</p>
            @if ($errors->any())
                <div class="mb-6 bg-red-50 text-red-500 text-[10px] font-bold p-4 rounded-xl border border-red-100 uppercase tracking-widest shadow-sm border-l-4 border-l-red-500">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                @csrf
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none text-slate-400 group-focus-within:text-[#f97316] transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="rider email address" class="modern-input w-full border-transparent text-xs text-slate-800 font-bold placeholder-slate-400 rounded-xl py-4 pl-12 pr-4 outline-none">
                </div>
                
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none text-slate-400 group-focus-within:text-[#f97316] transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <input type="password" name="password" required placeholder="app pincode" class="modern-input w-full border-transparent text-xs text-slate-800 font-bold placeholder-slate-400 rounded-xl py-4 pl-12 pr-4 outline-none">
                </div>

                <div class="flex items-center justify-between mt-2 pl-1">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-3 h-3 rounded border-slate-300 text-[#f97316] focus:ring-[#f97316] cursor-pointer bg-slate-100 outline-none hover:bg-white">
                        <span class="ml-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-slate-600 transition-colors">Keep Logged In</span>
                    </label>
                </div>

                <div class="pt-4 flex items-center gap-6">
                    <button type="submit" class="btn-gradient text-white font-black text-[11px] uppercase tracking-widest py-3.5 px-8 rounded-xl flex items-center gap-3 w-auto shadow-md">
                        START SHIFT
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#9a3412]">ON-ROAD</span>
                </div>
            </form>
        </div>
    </div>
@include('partials.password-toggle')
</body>
</html>

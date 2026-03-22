<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: true, mobileOpen: false, darkMode: true, profileOpen: false }" :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dealer Portal') — {{ config('custom.company_name', 'TC Center') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, body { font-family: 'Inter', sans-serif; }
        :root { --accent: #6366f1; --sidebar-w: 260px; }
        body { background: #09090f; color: #e5e7eb; }
        .card { background: #13131f; border: 1px solid rgba(255,255,255,.07); border-radius: 14px; }
        .nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 10px; font-size: 14px; font-weight: 500; color: #6b7280; transition: all .2s; }
        .nav-link:hover { color: #fff; background: rgba(255,255,255,.05); }
        .nav-link.active { color: #fff; background: rgba(99,102,241,.15); box-shadow: inset 3px 0 0 #6366f1; }
        .btn-primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; padding: 10px 20px; border-radius: 12px; font-weight: 600; box-shadow: 0 4px 15px rgba(99,102,241,.3); transition: all .2s; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.4); }
        .badge { padding: 3px 8px; border-radius: 99px; font-size: 10px; font-weight: 700; letter-spacing: .02em; }
        .badge-green { background: rgba(16,185,129,.1); color: #34d399; }
        .badge-yellow { background: rgba(245,158,11,.1); color: #fbbf24; }
        .badge-blue { background: rgba(99,102,241,.1); color: #818cf8; }
        input, select, textarea { background: rgba(255,255,255,0.03) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: white !important; border-radius: 10px !important; }
        input:focus { border-color: #6366f1 !important; outline: none; box-shadow: 0 0 0 3px rgba(99,102,241,0.2) !important; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    </style>
</head>
<body class="antialiased flex min-h-screen">

    {{-- Sidebar --}}
    <aside id="sidebar" :class="sidebarOpen ? 'w-[260px]' : 'w-20'" class="hidden md:flex flex-col border-r border-white/5 bg-[#0f0f17] transition-all duration-300 flex-shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-white/5 overflow-hidden">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span x-show="sidebarOpen" class="ml-3 font-bold text-white tracking-tight truncate">Dealer Portal</span>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('dealer.dashboard') }}" class="nav-link {{ request()->routeIs('dealer.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span x-show="sidebarOpen">Dashboard</span>
            </a>
            <div class="px-6 pt-6 pb-2 text-[10px] font-black text-gray-500 uppercase tracking-widest italic" x-show="sidebarOpen">Support Logic</div>
            <a href="{{ route('dealer.services.create') }}" class="nav-link {{ request()->routeIs('dealer.services.create') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span x-show="sidebarOpen">Book Service</span>
            </a>
            <a href="{{ route('dealer.services.index') }}" class="nav-link {{ request()->routeIs('dealer.services.index') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span x-show="sidebarOpen">Track Devices</span>
            </a>

            <div class="px-6 pt-6 pb-2 text-[10px] font-black text-gray-500 uppercase tracking-widest italic" x-show="sidebarOpen">Supply Chain</div>
            <a href="{{ route('dealer.orders.create') }}" class="nav-link {{ request()->routeIs('dealer.orders.create') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span x-show="sidebarOpen">Procurement Hub</span>
            </a>
            <a href="{{ route('dealer.inventory.index') }}" class="nav-link {{ request()->routeIs('dealer.inventory.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span x-show="sidebarOpen">Local Inventory</span>
            </a>
            <a href="{{ route('dealer.orders.history') }}" class="nav-link {{ request()->routeIs('dealer.orders.history') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-show="sidebarOpen">Order History</span>
            </a>
            <a href="{{ route('dealer.invoices.index') }}" class="nav-link {{ request()->routeIs('dealer.invoices.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span x-show="sidebarOpen">My Invoices</span>
            </a>
        </nav>

        <div class="p-4 border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-full text-red-400 hover:text-red-300 hover:bg-red-500/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="sidebarOpen">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-16 flex items-center justify-between px-8 border-b border-white/5 bg-[#09090f]/80 backdrop-blur-xl sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="hidden md:block p-1.5 rounded-lg hover:bg-white/5 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
                <h1 class="text-sm font-semibold text-white">@yield('title')</h1>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-indigo-400 font-medium">{{ Auth::user()->dealer->business_name ?? 'Partner Dealer' }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&size=40" class="w-8 h-8 rounded-lg">
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto">
                {{-- Flash Session Messages --}}
                @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>
</body>
</html>

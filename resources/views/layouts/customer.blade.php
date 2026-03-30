<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(config('custom.company_favicon'))
        <link rel="icon" href="{{ asset('storage/' . config('custom.company_favicon')) }}" type="image/x-icon">
    @endif
    <title>@yield('title', 'Dashboard') — {{ config('custom.company_name', 'TC Service Center') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ═══════════════════════════════════════════════
           ICE OCEAN BLUE DESIGN SYSTEM
           TC Service Center — Professional SaaS UI
        ═══════════════════════════════════════════════ */
        :root {
            --primary:          #0ea5e9;
            --primary-hover:    #0284c7;
            --primary-dark:     #0369a1;
            --primary-50:       #f0f9ff;
            --primary-100:      #e0f2fe;
            --primary-200:      #bae6fd;
            --primary-300:      #7dd3fc;
            --primary-600:      #0284c7;
            --primary-700:      #0369a1;
            --bg-page:          #f0f9ff;
            --bg-card:          #ffffff;
            --bg-sidebar:       #ffffff;
            --border:           #e0f2fe;
            --border-strong:    #bae6fd;
            --text-primary:     #0c4a6e;
            --text-secondary:   #475569;
            --text-muted:       #94a3b8;
            --sidebar-w:        260px;
            --topbar-h:         64px;
            --radius-sm:        8px;
            --radius-md:        12px;
            --radius-lg:        16px;
            --radius-xl:        20px;
            --shadow-xs:        0 1px 2px rgba(14,165,233,0.04);
            --shadow-sm:        0 1px 3px rgba(14,165,233,0.08), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:        0 4px 12px rgba(14,165,233,0.10), 0 2px 4px rgba(0,0,0,0.04);
            --shadow-lg:        0 8px 24px rgba(14,165,233,0.12), 0 4px 8px rgba(0,0,0,0.04);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--primary-200); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-300); }

        /* ══ SIDEBAR ══ */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.25s cubic-bezier(.4,0,.2,1);
        }
        .sidebar-logo {
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid var(--border);
            gap: 12px;
            flex-shrink: 0;
        }
        .sidebar-logo-icon {
            width: 36px; height: 36px;
            border-radius: var(--radius-sm);
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-logo-text { font-size: 0.875rem; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
        .sidebar-logo-sub  { font-size: 0.7rem; color: var(--text-muted); font-weight: 500; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 16px 12px; }
        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            padding: 12px 10px 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
            margin-bottom: 1px;
        }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; color: var(--text-muted); transition: color 0.15s; }
        .nav-item:hover {
            background: var(--primary-50);
            color: var(--primary-dark);
        }
        .nav-item:hover svg { color: var(--primary); }
        .nav-item.active {
            background: var(--primary-100);
            color: var(--primary-dark);
            font-weight: 600;
        }
        .nav-item.active svg { color: var(--primary); }

        .sidebar-footer {
            border-top: 1px solid var(--border);
            padding: 12px;
            flex-shrink: 0;
        }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 10px;
            border-radius: var(--radius-sm);
            transition: background 0.15s;
            cursor: pointer;
        }
        .user-chip:hover { background: var(--primary-50); }
        .user-avatar {
            width: 34px; height: 34px;
            border-radius: var(--radius-sm);
            background: var(--primary);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .user-name  { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
        .user-email { font-size: 0.7rem; color: var(--text-muted); }

        /* ══ TOPBAR ══ */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 40;
            transition: left 0.25s cubic-bezier(.4,0,.2,1);
        }
        .topbar.sidebar-hidden { left: 0; }
        .topbar-left { display: flex; align-items: center; gap: 14px; }
        .topbar-right { display: flex; align-items: center; gap: 8px; }
        .topbar-title { font-size: 1rem; font-weight: 600; color: var(--text-primary); }
        .topbar-sub   { font-size: 0.75rem; color: var(--text-muted); }

        .icon-btn {
            width: 36px; height: 36px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            background: #fff;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            position: relative;
        }
        .icon-btn:hover { background: var(--primary-50); border-color: var(--primary-200); color: var(--primary); }
        .icon-btn svg { width: 18px; height: 18px; }

        .notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #ef4444;
            border: 2px solid #fff;
        }

        /* ══ MAIN CONTENT ══ */
        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            min-height: calc(100vh - var(--topbar-h));
            padding: 28px;
            transition: margin-left 0.25s cubic-bezier(.4,0,.2,1);
        }
        .main-content.sidebar-hidden { margin-left: 0; }

        /* ══ CARDS ══ */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }
        /* aliases used by pages */
        .super-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .super-card:hover { box-shadow: var(--shadow-md); }
        .glass {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }

        /* ══ STAT CARDS ══ */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 4px; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); line-height: 1; }
        .stat-sub   { font-size: 0.72rem; color: var(--text-muted); margin-top: 4px; }

        /* ══ BUTTONS ══ */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            gap: 8px; padding: 9px 18px;
            border-radius: var(--radius-sm);
            font-size: 0.875rem; font-weight: 600;
            cursor: pointer; border: none;
            text-decoration: none; transition: all 0.15s;
        }
        .btn-primary {
            background: var(--primary);
            color: #ffffff;
        }
        .btn-primary:hover { background: var(--primary-hover); box-shadow: var(--shadow-md); }

        .btn-secondary {
            background: var(--primary-50);
            color: var(--primary-dark);
            border: 1px solid var(--primary-200);
        }
        .btn-secondary:hover { background: var(--primary-100); }

        .btn-outline {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }
        .btn-outline:hover { background: var(--primary-50); color: var(--primary-dark); border-color: var(--primary-200); }

        .btn-sm { padding: 6px 14px; font-size: 0.8rem; }
        .btn-lg { padding: 12px 24px; font-size: 0.95rem; }

        /* ══ INPUTS ══ */
        .super-input, .form-input {
            width: 100%;
            background: #ffffff !important;
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            padding: 10px 14px !important;
            font-size: 0.875rem !important;
            font-family: 'Inter', sans-serif !important;
            color: var(--text-primary) !important;
            transition: border-color 0.15s, box-shadow 0.15s !important;
            outline: none !important;
        }
        .super-input::placeholder, .form-input::placeholder { color: var(--text-muted) !important; }
        .super-input:focus, .form-input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(14,165,233,0.10) !important;
        }
        .super-input option { background: #fff; color: var(--text-primary); }

        /* ══ STATUS BADGES ══ */
        .badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 3px 10px; border-radius: 99px;
            font-size: 0.72rem; font-weight: 600;
        }
        .badge-blue   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-sky    { background: var(--primary-50); color: var(--primary-dark); border: 1px solid var(--primary-200); }
        .badge-green  { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-amber  { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .badge-red    { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-gray   { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
        /* aliases from pages */
        .badge-cyan   { background: var(--primary-50); color: var(--primary-dark); border: 1px solid var(--primary-200); }

        /* ══ TABLES ══ */
        .pro-table { width: 100%; border-collapse: collapse; }
        .pro-table thead th {
            background: var(--primary-50);
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 11px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .pro-table tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; }
        .pro-table tbody tr:last-child { border-bottom: none; }
        .pro-table tbody tr:hover { background: var(--primary-50); }
        .pro-table tbody td { padding: 13px 16px; font-size: 0.875rem; color: var(--text-primary); vertical-align: middle; }

        /* legacy alias */
        .ice-table th { background: var(--primary-50); color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; padding: 11px 16px; }
        .ice-table tr { border-bottom: 1px solid var(--border); transition: background 0.12s; }
        .ice-table tr:hover { background: var(--primary-50); }
        .ice-table td { padding: 13px 16px; font-size: 0.875rem; color: var(--text-primary); }

        /* ══ SECTION HEADER ══ */
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .section-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 10px; }
        .section-title-accent { width: 4px; height: 18px; background: var(--primary); border-radius: 99px; }

        /* ══ PAGE HEADER ══ */
        .page-header { margin-bottom: 28px; }
        .page-title  { font-size: 1.375rem; font-weight: 700; color: var(--text-primary); }
        .page-sub    { font-size: 0.875rem; color: var(--text-secondary); margin-top: 4px; }

        /* ══ EMPTY STATE ══ */
        .empty-state {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; text-align: center;
            padding: 64px 32px; color: var(--text-muted);
        }
        .empty-state-icon {
            width: 64px; height: 64px;
            border-radius: var(--radius-lg);
            background: var(--primary-50);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
        }
        .empty-state-icon svg { width: 32px; height: 32px; color: var(--primary); }
        .empty-state-title { font-size: 1rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px; }
        .empty-state-text  { font-size: 0.875rem; max-width: 320px; line-height: 1.6; }

        /* ══ ANIMATIONS ══ */
        .animate-slide-up { animation: slideUp 0.35s ease both; }
        .animate-slide-up-delay { animation: slideUp 0.35s 0.05s ease both; }
        .animate-slide-up-delay-2 { animation: slideUp 0.35s 0.10s ease both; }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        [x-cloak] { display: none !important; }

        /* ══ DIVIDER ══ */
        .divider { border: none; border-top: 1px solid var(--border); margin: 0; }

        /* ══ TOGGLE (clean) ══ */
        .neon-toggle {
            width: 42px; height: 24px;
            border-radius: 99px;
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            position: relative; cursor: pointer;
            transition: all 0.2s;
        }
        .neon-toggle.on { background: var(--primary); border-color: var(--primary-hover); }
        .neon-toggle-thumb {
            position: absolute; top: 3px; left: 3px;
            width: 18px; height: 18px; border-radius: 50%;
            background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            transition: all 0.2s;
        }
        .neon-toggle.on .neon-toggle-thumb { transform: translateX(18px); }

        /* ══ ALERT BANNERS ══ */
        .alert { display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border-radius: var(--radius-md); margin-bottom: 20px; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-info    { background: var(--primary-50); border: 1px solid var(--primary-200); color: var(--primary-dark); }
        .alert svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; }
        .alert-text { font-size: 0.875rem; font-weight: 500; }

        /* ══ PROGRESS BAR ══ */
        .progress-bar { height: 6px; background: var(--primary-100); border-radius: 99px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 99px; background: var(--primary); transition: width 0.6s ease; }
        .progress-fill.danger { background: #ef4444; }

        /* ══ MOBILE ══ */
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .topbar { left: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body x-data="{
    sidebarOpen: window.innerWidth >= 1024,
    mobileMenuOpen: false
}" x-init="() => {
    window.addEventListener('resize', () => {
        if(window.innerWidth >= 1024) { sidebarOpen = true; mobileMenuOpen = false; }
    });
}">

    {{-- ══ SIDEBAR ══ --}}
    {{-- Mobile Backdrop --}}
    <div x-show="mobileMenuOpen" x-cloak x-transition:opacity
         @click="mobileMenuOpen = false"
         class="fixed inset-0 z-40 lg:hidden"
         style="background:rgba(15,23,42,0.4)"></div>

    <aside :class="mobileMenuOpen ? 'open' : ''" class="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                @if(config('custom.company_logo'))
                    <img src="{{ asset('storage/' . config('custom.company_logo')) }}" class="w-6 h-6 object-contain" alt="Logo">
                @else
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                @endif
            </div>
            <div>
                <div class="sidebar-logo-text">{{ config('custom.company_name', 'TC Service Center') }}</div>
                <div class="sidebar-logo-sub">Customer Portal</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            <div class="nav-section-label">Overview</div>
            <a href="{{ route('customer.dashboard') }}" class="nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <div class="nav-section-label">Services</div>
            <a href="{{ route('customer.service.book') }}" class="nav-item {{ request()->routeIs('customer.service.book') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Book Service
            </a>
            <a href="{{ route('customer.orders.index') }}" class="nav-item {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                My Repairs
            </a>
            <a href="{{ route('customer.warranty.index') }}" class="nav-item {{ request()->routeIs('customer.warranty.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Warranty
            </a>

            <div class="nav-section-label">Shop</div>
            <a href="{{ route('shop.index') }}" class="nav-item {{ request()->routeIs('shop.index') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                Spare Parts
            </a>
            <a href="{{ route('customer.shop.laptops') }}" class="nav-item {{ request()->routeIs('customer.shop.laptops') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Refurbished Laptops
            </a>
            <a href="{{ route('customer.cart.index') }}" class="nav-item {{ request()->routeIs('customer.cart.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Cart
                @if(isset($cartCount) && $cartCount > 0)
                    <span class="ml-auto text-xs font-bold px-1.5 py-0.5 rounded" style="background:var(--primary-100);color:var(--primary-dark)">{{ $cartCount }}</span>
                @endif
            </a>

            <div class="nav-section-label">More</div>
            <a href="{{ route('customer.service.custom-build') }}" class="nav-item {{ request()->routeIs('customer.service.custom-build') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                Custom Build
            </a>
            <a href="{{ route('customer.service.cctv') }}" class="nav-item {{ request()->routeIs('customer.service.cctv') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                CCTV Installation
            </a>
            <a href="{{ route('customer.chat') }}" class="nav-item {{ request()->routeIs('customer.chat') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                AI Chat Bot
            </a>
            <a href="{{ route('customer.settings') }}" class="nav-item {{ request()->routeIs('customer.settings') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
        </nav>

        {{-- User Footer --}}
        <div class="sidebar-footer">
            <div class="user-chip">
                <div class="user-avatar">{{ strtoupper(substr(auth('customer')->user()->name ?? 'U', 0, 1)) }}</div>
                <div style="flex:1;overflow:hidden">
                    <div class="user-name truncate">{{ auth('customer')->user()->name ?? 'Guest' }}</div>
                    <div class="user-email truncate">{{ auth('customer')->user()->email ?? '' }}</div>
                </div>
            </div>
            <form action="{{ route('customer.logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="nav-item w-full" style="border:none;background:none;cursor:pointer;color:#ef4444;font-size:0.8rem;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;color:#ef4444"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- ══ TOPBAR ══ --}}
    <header class="topbar" :class="!sidebarOpen ? 'sidebar-hidden' : ''">
        <div class="topbar-left">
            {{-- Mobile burger --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="icon-btn lg:hidden" id="mobile-menu-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            {{-- Desktop toggle --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="icon-btn hidden lg:flex" id="sidebar-toggle-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <div>
                <div class="topbar-title">@yield('title', 'Dashboard')</div>
                <div class="topbar-sub" style="display:none" id="topbar-breadcrumb">@yield('breadcrumb')</div>
            </div>
        </div>

        <div class="topbar-right">
            {{-- Spend chip --}}
            <div class="hidden xl:block" style="padding-right:16px;margin-right:8px;border-right:1px solid var(--border)">
                <div style="font-size:0.7rem;color:var(--text-muted)">Total Spent</div>
                <div style="font-size:0.875rem;font-weight:700;color:var(--text-primary)">₹{{ number_format(auth('customer')->check() ? auth('customer')->user()->productOrders()->sum('total_price') : 0) }}</div>
            </div>

            {{-- Settings --}}
            <a href="{{ route('customer.settings') }}" class="icon-btn" id="topbar-settings-link" title="Settings">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </a>

            {{-- Notifications --}}
            <button class="icon-btn" id="notifications-btn" title="Notifications">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if($unreadCount > 0)
                    <span class="notif-dot"></span>
                @endif
            </button>

            {{-- Avatar chip --}}
            <div style="display:flex;align-items:center;gap:10px;padding:6px 12px 6px 6px;border-radius:var(--radius-sm);border:1px solid var(--border);background:#fff;">
                <div class="user-avatar" style="width:30px;height:30px;font-size:0.75rem;">
                    {{ strtoupper(substr(auth('customer')->user()->name ?? 'U', 0, 1)) }}
                </div>
                <span style="font-size:0.8rem;font-weight:600;color:var(--text-primary);white-space:nowrap" class="hidden sm:inline">
                    {{ explode(' ', auth('customer')->user()->name ?? 'User')[0] }}
                </span>
            </div>
        </div>
    </header>

    {{-- ══ MAIN ══ --}}
    <main class="main-content" :class="!sidebarOpen ? 'sidebar-hidden' : ''">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success animate-slide-up">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span class="alert-text">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error animate-slide-up">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                <span class="alert-text">{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')

        {{-- Footer --}}
        <footer style="margin-top:40px;padding-top:20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <span style="font-size:0.75rem;color:var(--text-muted)">&copy; {{ date('Y') }} {{ config('custom.company_name', 'Thambu Computers') }}. All rights reserved.</span>
            <div style="display:flex;gap:20px">
                <a href="#" style="font-size:0.75rem;color:var(--text-muted);text-decoration:none" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">Privacy</a>
                <a href="#" style="font-size:0.75rem;color:var(--text-muted);text-decoration:none" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">Terms</a>
                <a href="{{ route('customer.settings') }}" style="font-size:0.75rem;color:var(--text-muted);text-decoration:none" onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-muted)'">Settings</a>
            </div>
        </footer>
    </main>

    {{-- ══ FLOATING AI CHATBOT WIDGET ══ --}}
    @php $chatCustomerName = auth('customer')->user()->name ?? 'Customer'; @endphp

    <div id="ai-chat-widget"
         data-name="{{ e($chatCustomerName) }}"
         data-endpoint="{{ route('customer.chat.message') }}"
         data-token="{{ csrf_token() }}"
         data-fullscreen="{{ route('customer.chat') }}"
         x-data="aiChatWidget()"
         style="position:fixed;bottom:24px;right:24px;z-index:9999">

        {{-- Toggle Button --}}
        <button @click="open = !open"
                style="width:54px;height:54px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(79,70,229,0.4);transition:transform 0.2s;position:relative"
                onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
            <svg x-show="!open" style="width:26px;height:26px" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <svg x-show="open" x-cloak style="width:22px;height:22px" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span style="position:absolute;top:-3px;right:-3px;width:13px;height:13px;border-radius:50%;background:#22c55e;border:2px solid #fff"></span>
        </button>

        {{-- Chat Window --}}
        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="position:absolute;bottom:66px;right:0;width:340px;height:480px;background:#fff;border-radius:18px;box-shadow:0 20px 60px rgba(0,0,0,0.18);display:flex;flex-direction:column;overflow:hidden;border:1px solid rgba(79,70,229,0.15)">

            {{-- Header --}}
            <div style="padding:14px 18px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;gap:12px;flex-shrink:0">
                <div style="width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:1.2rem">🤖</div>
                <div style="flex:1">
                    <div style="font-size:0.875rem;font-weight:700;color:#fff">Thambu AI</div>
                    <div style="font-size:0.7rem;color:rgba(255,255,255,0.8);display:flex;align-items:center;gap:5px">
                        <span style="width:6px;height:6px;border-radius:50%;background:#4ade80;display:inline-block"></span>
                        Online · Ready to help
                    </div>
                </div>
                <a :href="fullscreen" style="font-size:0.68rem;color:rgba(255,255,255,0.8);text-decoration:none;background:rgba(255,255,255,0.18);padding:4px 10px;border-radius:20px">Full Screen</a>
            </div>

            {{-- Messages --}}
            <div id="widget-chat-window" style="flex:1;overflow-y:auto;padding:14px;background:#f8fafc;display:flex;flex-direction:column;gap:10px">
                <template x-for="(msg, i) in messages" :key="i">
                    <div :style="msg.from === 'user' ? 'display:flex;justify-content:flex-end' : 'display:flex;justify-content:flex-start;gap:8px;align-items:flex-end'">
                        <span x-show="msg.from === 'bot'" style="font-size:1rem;flex-shrink:0;margin-bottom:2px">🤖</span>
                        <div :style="msg.from === 'user'
                            ? 'background:linear-gradient(135deg,#4f46e5,#6d28d9);color:#fff;border-radius:16px 16px 4px 16px;padding:9px 14px;font-size:0.8rem;line-height:1.5;max-width:220px;word-break:break-word'
                            : 'background:#fff;color:#1e293b;border:1px solid #e2e8f0;border-radius:16px 16px 16px 4px;padding:9px 14px;font-size:0.8rem;line-height:1.5;max-width:240px;word-break:break-word;box-shadow:0 1px 3px rgba(0,0,0,0.05)'"
                             x-text="msg.text"></div>
                    </div>
                </template>
                {{-- Typing dots --}}
                <div x-show="isTyping" style="display:flex;gap:8px;align-items:flex-end">
                    <span style="font-size:1rem">🤖</span>
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px 16px 16px 4px;padding:10px 14px;display:flex;gap:4px;align-items:center">
                        <span class="tc-dot"></span>
                        <span class="tc-dot" style="animation-delay:0.2s"></span>
                        <span class="tc-dot" style="animation-delay:0.4s"></span>
                    </div>
                </div>
            </div>

            {{-- Input --}}
            <div style="padding:12px;border-top:1px solid #e8ecf0;background:#fff;flex-shrink:0">
                <form @submit.prevent="send" style="display:flex;gap:8px;align-items:center">
                    <input type="text" x-model="msg" @keydown.enter.prevent="send" placeholder="Ask me anything..."
                           :disabled="isTyping"
                           style="flex:1;border:1.5px solid #e2e8f0;border-radius:50px;padding:9px 16px;font-size:0.8rem;outline:none;font-family:inherit;color:#1e293b;background:#f8fafc;transition:border-color 0.15s"
                           onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    <button type="submit"
                            :disabled="!msg.trim() || isTyping"
                            style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0"
                            :style="!msg.trim() || isTyping ? 'opacity:0.45' : 'opacity:1'">
                        <svg style="width:15px;height:15px" fill="none" stroke="#fff" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
    .tc-dot { display:inline-block;width:7px;height:7px;border-radius:50%;background:#a5b4fc;animation:tc-bounce 0.8s infinite; }
    @keyframes tc-bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)} }
    </style>

    <script>
    (function() {
        var el = document.getElementById('ai-chat-widget');
        var customerName  = el ? el.dataset.name       : 'Customer';
        var chatEndpoint  = el ? el.dataset.endpoint   : '';
        var csrfToken     = el ? el.dataset.token      : '';
        var fullscreenUrl = el ? el.dataset.fullscreen : '';

        document.addEventListener('alpine:init', function () {
            Alpine.data('aiChatWidget', function () {
                return {
                    open: false,
                    messages: [{ from: 'bot', text: 'Hi ' + customerName + '! \uD83D\uDC4B How can I help you today? Ask me about your repairs, orders, or services.' }],
                    msg: '',
                    isTyping: false,
                    fullscreen: fullscreenUrl,

                    send: function () {
                        if (!this.msg.trim() || this.isTyping) return;
                        var text = this.msg.trim();
                        this.msg = '';
                        this.messages.push({ from: 'user', text: text });
                        this.scrollDown();
                        this.isTyping = true;
                        var self = this;
                        fetch(chatEndpoint, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify({ message: text })
                        })
                        .then(function(r){ return r.json(); })
                        .then(function(d){
                            self.isTyping = false;
                            self.messages.push({ from: 'bot', text: d.reply });
                            self.scrollDown();
                        })
                        .catch(function(){
                            self.isTyping = false;
                            self.messages.push({ from: 'bot', text: 'Sorry, I cannot connect right now. Please try again.' });
                            self.scrollDown();
                        });
                    },
                    scrollDown: function () {
                        var self = this;
                        this.$nextTick(function () {
                            var w = document.getElementById('widget-chat-window');
                            if (w) w.scrollTop = w.scrollHeight;
                        });
                    }
                };
            });
        });
    })();
    </script>


    <script>
        // Sync sidebar margin with Alpine toggle on desktop
        document.addEventListener('alpine:init', () => {});
    </script>

    @stack('scripts')

</body>
</html>

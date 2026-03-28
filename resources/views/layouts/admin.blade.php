<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: true, mobileOpen: false, darkMode: true, profileOpen: false, notifOpen: false }" :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(config('custom.company_favicon'))
        <link rel="icon" href="{{ asset('storage/' . config('custom.company_favicon')) }}" type="image/x-icon">
    @endif
    <title>@yield('title', 'Admin') — {{ config('custom.company_name', 'TC Service Center') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, body { font-family: 'Inter', sans-serif; }

        /* ── Core tokens ── */
        :root {
            --accent: {{ config('custom.theme_color', '#6366f1') }};
            --sidebar-w: 260px;
            --sidebar-collapsed: 68px;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(99,102,241,.3); border-radius: 99px; }

        /* ── Sidebar ── */
        #sidebar {
            width: var(--sidebar-w);
            transition: width .22s cubic-bezier(.4,0,.2,1);
            background: #0f0f17;
            border-right: 1px solid rgba(255,255,255,.06);
        }
        #sidebar.collapsed { width: var(--sidebar-collapsed); }
        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .nav-section,
        #sidebar.collapsed .nav-badge,
        #sidebar.collapsed .brand-name,
        #sidebar.collapsed .user-meta { opacity: 0; width: 0; overflow: hidden; }
        #sidebar.collapsed .brand-logo { margin: 0 auto; }

        /* ── Nav link ── */
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px; font-size: 13px; font-weight: 500;
            color: #6b7280; transition: all .15s ease; white-space: nowrap; overflow: hidden;
        }
        .nav-link:hover { color: #e5e7eb; background: rgba(255,255,255,.05); }
        .nav-link.active { color: #fff; background: rgba(99,102,241,.18); box-shadow: inset 2px 0 0 #6366f1; }
        .nav-link svg { flex-shrink: 0; width: 18px; height: 18px; opacity: .7; }
        .nav-link.active svg { opacity: 1; color: #818cf8; }

        /* ── Card / glass ── */
        .card {
            background: #13131f;
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 14px;
        }

        /* ── Table override ── */
        table thead th {
            background: #0d0d16 !important;
            color: #6b7280 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            letter-spacing: .06em !important;
            text-transform: uppercase !important;
            border-bottom: 1px solid rgba(255,255,255,.06) !important;
            padding: 12px 16px !important;
        }
        table tbody tr { border-bottom: 1px solid rgba(255,255,255,.04) !important; }
        table tbody tr:hover { background: rgba(99,102,241,.05) !important; }
        table td { padding: 12px 16px !important; color: #d1d5db !important; font-size: 13px !important; }

        /* ── Inputs ── */
        input:not([type=checkbox]):not([type=radio]), select, textarea {
            background: rgba(255,255,255,.04) !important;
            border: 1px solid rgba(255,255,255,.09) !important;
            color: #e5e7eb !important;
            border-radius: 8px !important;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99,102,241,.15) !important;
            outline: none !important;
        }

        /* ── Status badges ── */
        .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:99px; font-size:11px; font-weight:600; letter-spacing:.04em; }
        .badge-green  { background:rgba(16,185,129,.12); color:#34d399; }
        .badge-yellow { background:rgba(245,158,11,.12);  color:#fbbf24; }
        .badge-red    { background:rgba(239,68,68,.12);   color:#f87171; }
        .badge-blue   { background:rgba(99,102,241,.15);  color:#a5b4fc; }
        .badge-gray   { background:rgba(255,255,255,.07); color:#9ca3af; }

        /* ── Button ── */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white; font-weight: 600; font-size: 13px;
            padding: 8px 18px; border-radius: 9px;
            box-shadow: 0 4px 14px rgba(99,102,241,.35);
            transition: all .2s; border: none;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.45); }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(12px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up { animation: fadeUp .3s ease forwards; }
        .stagger-1 { animation-delay: 40ms; }
        .stagger-2 { animation-delay: 80ms; }
        .stagger-3 { animation-delay: 120ms; }
        .stagger-4 { animation-delay: 160ms; }

        /* ── Main content bg ── */
        body { background: #09090f; color: #e5e7eb; overflow-x: hidden; }

        /* ── White/glass polyfill ── */
        .glass-panel, .bg-white {
            background: #13131f !important;
            border: 1px solid rgba(255,255,255,.07) !important;
            border-radius: 12px !important;
            color: #e5e7eb !important;
        }
        .bg-[#1E1E2D], .bg-[#151521], .bg-[#1B1B29] { background: #13131f !important; }
        .border-\[#2B2B40\] { border-color: rgba(255,255,255,.07) !important; }
        .text-\[#A1A5B7\], .text-gray-500, .text-gray-400 { color: #6b7280 !important; }
        .text-white, .text-gray-100 { color: #f9fafb !important; }

        /* ── Top bar ── */
        #topbar {
            background: rgba(9,9,15,.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        /* ── Search ── */
        .search-box {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 9px;
            padding: 7px 14px;
            display: flex; align-items: center; gap: 8px;
            transition: border-color .15s;
        }
        .search-box:focus-within { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        .search-box input { background: transparent !important; border: none !important; outline: none !important; color: #e5e7eb !important; font-size: 13px !important; width: 220px; box-shadow: none !important; }

        /* ── Notification dropdown ── */
        .dropdown-panel {
            position: absolute; right: 0; top: calc(100% + 10px);
            width: 320px; background: #13131f;
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,.6);
            z-index: 100; overflow: hidden;
        }

        /* ── Mobile overlay ── */
        #sidebarOverlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.6);
            backdrop-filter: blur(4px); z-index: 40;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex">

{{-- Mobile overlay --}}
<div id="sidebarOverlay" @click="mobileOpen = false" x-show="mobileOpen" style="display:none"></div>

{{-- ═══════════════════════════════════════════ SIDEBAR ═══════ --}}
<aside id="sidebar"
    :class="{ 'collapsed': !sidebarOpen }"
    class="flex-shrink-0 flex flex-col min-h-screen z-50 fixed md:relative">

    {{-- Brand --}}
    <div class="h-16 flex items-center px-4 border-b border-white/5 gap-3 overflow-hidden flex-shrink-0">
        @if(config('custom.company_logo'))
            <img src="{{ asset('storage/' . config('custom.company_logo')) }}" class="brand-logo w-8 h-8 rounded-lg object-contain bg-white/5 p-1 flex-shrink-0" alt="Logo">
        @else
            <div class="brand-logo w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-indigo-500/30">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        @endif
        <div class="brand-name transition-all">
            <span class="text-sm font-bold text-white tracking-tight block leading-tight">{{ config('custom.company_name', 'TC Service') }}</span>
            <span class="text-[10px] text-gray-500 font-medium">Admin Panel</span>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden px-3 space-y-0.5">

        {{-- Section: Core --}}
        <div class="nav-section text-[10px] font-semibold text-gray-600 uppercase tracking-widest px-2 pt-2 pb-2 transition-all">Core</div>

        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span class="nav-label">Dashboard</span>
        </a>
        <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span class="nav-label">Service Orders</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="nav-label">Dealer Orders</span>
        </a>
        <a href="{{ route('admin.employees.index') }}" class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span class="nav-label">Staff Directory</span>
        </a>
        <a href="{{ route('admin.dealers.index') }}" class="nav-link {{ request()->routeIs('admin.dealers.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span class="nav-label">Dealers</span>
        </a>
        <a href="{{ route('admin.delivery-partners.index') }}" class="nav-link {{ request()->routeIs('admin.delivery-partners.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span class="nav-label">Delivery Partners</span>
        </a>
        <a href="{{ route('admin.delivery.live-map') }}" class="nav-link {{ request()->routeIs('admin.delivery.live-map') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            <span class="nav-label">Live Delivery Map</span>
            <span class="nav-badge ml-auto text-[9px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-1.5 py-0.5 rounded-full">LIVE</span>
        </a>

        {{-- Section: Inventory --}}
        <div class="nav-section text-[10px] font-semibold text-gray-600 uppercase tracking-widest px-2 pt-5 pb-2">Inventory</div>
        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
             <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
             <span class="nav-label">Global Catalog</span>
        </a>
        <a href="{{ route('admin.inventory.logs') }}" class="nav-link {{ request()->routeIs('admin.inventory.logs') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span class="nav-label">Audit Logs</span>
        </a>
        <a href="{{ route('admin.logistics.index') }}" class="nav-link {{ request()->routeIs('admin.logistics.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span class="nav-label">Logistics Tracking</span>
        </a>
        <a href="{{ route('admin.parts.index') }}" class="nav-link {{ request()->routeIs('admin.parts.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            <span class="nav-label">Spare Parts</span>
        </a>
        <a href="{{ route('admin.devices.index') }}" class="nav-link {{ request()->routeIs('admin.devices.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="nav-label">Devices DB</span>
        </a>
        <a href="{{ route('admin.laptops.index') }}" class="nav-link {{ request()->routeIs('admin.laptops.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="nav-label">Used Laptops</span>
        </a>
        <a href="{{ route('admin.warranty.index') }}" class="nav-link {{ request()->routeIs('admin.warranty.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span class="nav-label">Warranty</span>
        </a>

        <div class="nav-section text-[10px] font-semibold text-gray-600 uppercase tracking-widest px-2 pt-5 pb-2">Revenue & Billing</div>
        <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="nav-label">All Invoices</span>
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="nav-label">Payments Hub</span>
        </a>
        <a href="{{ route('admin.finance.expenses') }}" class="nav-link {{ request()->routeIs('admin.finance.expenses') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="nav-label">Expense Tracking</span>
        </a>
        <a href="{{ route('admin.billings.index') }}" class="nav-link {{ request()->routeIs('admin.billings.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="nav-label">Billings</span>
        </a>
        <a href="{{ route('admin.finance.dashboard') }}" class="nav-link {{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span class="nav-label">Finance</span>
        </a>
        <a href="{{ route('admin.purchase-orders.index') }}" class="nav-link {{ request()->routeIs('admin.purchase-orders.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span class="nav-label">Purchase Orders</span>
        </a>
        <a href="{{ route('admin.hrm.payroll') }}" class="nav-link {{ request()->routeIs('admin.hrm.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span class="nav-label">Payroll</span>
        </a>
        <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="nav-label">Attendance</span>
        </a>

        {{-- Section: Intelligence --}}
        <div class="nav-section text-[10px] font-semibold text-gray-600 uppercase tracking-widest px-2 pt-5 pb-2">Intelligence</div>
        <a href="{{ route('admin.analytics.index') }}" class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="nav-label">Analytics</span>
        </a>
        <a href="{{ route('admin.ai.index') }}" class="nav-link {{ request()->routeIs('admin.ai.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            <span class="nav-label">AI Assistant</span>
            <span class="nav-badge ml-auto text-[9px] font-bold bg-yellow-500/20 text-yellow-400 px-1.5 py-0.5 rounded-full">AI</span>
        </a>
        <a href="{{ route('admin.crm.index') }}" class="nav-link {{ request()->routeIs('admin.crm.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span class="nav-label">CRM</span>
        </a>
        <a href="{{ route('admin.marketing.index') }}" class="nav-link {{ request()->routeIs('admin.marketing.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            <span class="nav-label">Marketing</span>
        </a>

        {{-- Section: Settings --}}
        <div class="nav-section text-[10px] font-semibold text-gray-600 uppercase tracking-widest px-2 pt-5 pb-2">Settings</div>
        <a href="{{ route('admin.customization.index') }}" class="nav-link {{ request()->routeIs('admin.customization.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            <span class="nav-label">Customization</span>
        </a>
        <a href="{{ route('admin.invoice-settings.index') }}" class="nav-link {{ request()->routeIs('admin.invoice-settings.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="nav-label">Invoice Settings</span>
        </a>
        <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span class="nav-label">Notifications</span>
        </a>
        <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span class="nav-label">Roles & Access</span>
        </a>
        <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
            <span class="nav-label">Page Builder</span>
        </a>
        <a href="{{ route('admin.forms.index') }}" class="nav-link {{ request()->routeIs('admin.forms.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            <span class="nav-label">Form Builder</span>
        </a>
        <a href="{{ route('admin.branches.index') }}" class="nav-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span class="nav-label">Branches</span>
        </a>
        <a href="{{ route('admin.offers.index') }}" class="nav-link {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            <span class="nav-label">Offers / Emails</span>
        </a>

    </nav>

    {{-- Bottom: User + Logout --}}
    <div class="p-3 border-t border-white/5 flex-shrink-0">
        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition cursor-pointer overflow-hidden">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&size=40" class="w-8 h-8 rounded-lg flex-shrink-0">
            <div class="user-meta min-w-0 transition-all">
                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-gray-500">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-1">
            @csrf
            <button type="submit" class="nav-link w-full text-red-400 hover:text-red-300 hover:bg-red-500/10">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span class="nav-label">Sign Out</span>
            </button>
        </form>
    </div>
</aside>

{{-- ═══════════════════════════════════════════ MAIN ═══════════ --}}
<main class="flex-1 flex flex-col min-h-screen overflow-hidden" style="margin-left: 0;">

    {{-- TOP BAR --}}
    <header id="topbar" class="h-14 flex items-center justify-between px-5 sticky top-0 z-30 flex-shrink-0">

        {{-- Left: Sidebar toggle + breadcrumb --}}
        <div class="flex items-center gap-4">
            {{-- Desktop collapse --}}
            <button @click="sidebarOpen = !sidebarOpen; document.getElementById('sidebar').classList.toggle('collapsed')"
                class="hidden md:flex p-1.5 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            </button>
            {{-- Mobile open --}}
            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-1.5 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            </button>

            {{-- Search --}}
            <div class="search-box hidden lg:flex">
                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search orders, customers…" autocomplete="off">
                <kbd class="text-[10px] text-gray-600 bg-white/5 px-1.5 py-0.5 rounded border border-white/10 ml-2">⌘K</kbd>
            </div>
        </div>

        {{-- Right: Actions --}}
        <div class="flex items-center gap-2">

            {{-- Dark mode toggle --}}
            <button @click="darkMode = !darkMode" class="p-2 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition">
                <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>

            {{-- Notifications --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative p-2 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-[#09090f]"></span>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="dropdown-panel">
                    <div class="px-4 py-3 border-b border-white/6 flex items-center justify-between">
                        <span class="text-sm font-semibold text-white">Notifications</span>
                        <span class="text-[10px] text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded-full">3 new</span>
                    </div>
                    <div class="divide-y divide-white/5">
                        <div class="px-4 py-3 hover:bg-white/3 transition">
                            <p class="text-xs font-semibold text-white">New service order received</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">2 minutes ago</p>
                        </div>
                        <div class="px-4 py-3 hover:bg-white/3 transition">
                            <p class="text-xs font-semibold text-white">Low stock alert: NVMe SSD</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">14 minutes ago</p>
                        </div>
                        <div class="px-4 py-3 hover:bg-white/3 transition">
                            <p class="text-xs font-semibold text-white">Invoice #INV-2026-041 paid</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">1 hour ago</p>
                        </div>
                    </div>
                    <div class="px-4 py-2.5 border-t border-white/5">
                        <a href="{{ route('admin.notifications.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">View all notifications →</a>
                    </div>
                </div>
            </div>

            {{-- Profile --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-white/5 transition">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&size=40" class="w-7 h-7 rounded-lg">
                    <span class="hidden md:block text-xs font-semibold text-gray-300">{{ auth()->user()->name }}</span>
                    <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="dropdown-panel" style="width:200px;">
                    <div class="px-4 py-3 border-b border-white/6">
                        <p class="text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('admin.customization.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Settings
                        </a>
                    </div>
                    <div class="py-1 border-t border-white/5">
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="flex items-center gap-2.5 w-full px-4 py-2.5 text-xs text-red-400 hover:text-red-300 hover:bg-red-500/10 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </header>

    {{-- CONTENT --}}
    <div class="flex-1 overflow-y-auto">
        <div class="p-5 md:p-7 max-w-screen-2xl mx-auto">

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="mb-5 flex items-start gap-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium px-4 py-3 rounded-xl fade-up" x-data x-init="setTimeout(() => $el.remove(), 4000)">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-5 flex items-start gap-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium px-4 py-3 rounded-xl fade-up" x-data x-init="setTimeout(() => $el.remove(), 5000)">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </div>

</main>

@stack('scripts')
<script>
// Sync sidebar state with AlpineJS on load
document.addEventListener('DOMContentLoaded', () => {
    // Mobile: overlay sidebar
    document.getElementById('sidebarOverlay')?.addEventListener('click', () => {
        document.getElementById('sidebar').style.display = 'none';
    });
});
</script>
</body>
</html>
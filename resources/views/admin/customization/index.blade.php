@extends('layouts.admin')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-3">
        <div class="p-2 bg-indigo-600/20 rounded-xl border border-indigo-500/30">
            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
        </div>
        Super Customization Hub
    </h2>
    <span class="text-xs font-medium bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-3 py-1.5 rounded-full">
        ⚙️ Platform Config Center
    </span>
</div>
@endsection

@section('content')
<div class="max-w-screen-2xl mx-auto px-2 py-6" x-data="{ tab: '{{ request('tab', 'global') }}' }">

    {{-- ════ Tab Navigation Bar ════ --}}
    <div class="flex flex-wrap gap-2 mb-8 p-1.5 bg-gray-900/70 backdrop-blur rounded-2xl border border-gray-700/50 shadow-inner">
        @php
        $tabs = [
            ['id' => 'global',    'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Global Settings'],
            ['id' => 'theme',     'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'label' => 'Theme & Branding'],
            ['id' => 'features',  'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'label' => 'Feature Toggles'],
            ['id' => 'forms',     'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'label' => 'Form Builder'],
            ['id' => 'pages',     'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4', 'label' => 'Page Builder'],
            ['id' => 'roles',     'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'label' => 'Roles & Permissions'],
            ['id' => 'notifications', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'label' => 'Notifications'],
        ];
        @endphp
        @foreach($tabs as $t)
        <button @click="tab = '{{ $t['id'] }}'"
            :class="tab === '{{ $t['id'] }}' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 border-indigo-500/50' : 'text-gray-400 hover:text-white hover:bg-gray-800/80 border-transparent'"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 border">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $t['icon'] }}"/>
            </svg>
            <span>{{ $t['label'] }}</span>
        </button>
        @endforeach
    </div>

    {{-- Success / Error Alert --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 bg-emerald-500/15 border border-emerald-500/40 text-emerald-400 px-5 py-3.5 rounded-xl shadow-sm backdrop-blur">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 flex items-center gap-3 bg-red-500/15 border border-red-500/40 text-red-400 px-5 py-3.5 rounded-xl shadow-sm backdrop-blur">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════ --}}
    {{-- TAB 1: GLOBAL SETTINGS                          --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div x-show="tab === 'global'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-700/50 bg-gradient-to-r from-indigo-900/20 to-transparent flex items-center gap-3">
                <div class="p-2 bg-indigo-500/20 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-100">Business Information</h3>
                    <p class="text-xs text-gray-400">Core company details used across all platform pages and documents.</p>
                </div>
            </div>
            <form action="{{ route('admin.customization.settings') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Company Name --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span> Company Name
                        </label>
                        <input type="text" name="company_name" value="{{ $settings['company_name']->value ?? '' }}"
                            placeholder="e.g. TC Service Center"
                            class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-3 text-gray-200 placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition-all">
                    </div>

                    {{-- GST Number --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span> GST / Tax Number
                        </label>
                        <input type="text" name="company_gst" value="{{ $settings['company_gst']->value ?? '' }}"
                            placeholder="e.g. GSTIN1234567890"
                            class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-3 text-gray-200 placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition-all">
                    </div>

                    {{-- Support Phone --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Support Phone
                        </label>
                        <input type="text" name="support_phone" value="{{ $settings['support_phone']->value ?? '' }}"
                            placeholder="+1 800 000 0000"
                            class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-3 text-gray-200 placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition-all">
                    </div>

                    {{-- Support Email --}}
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Support Email
                        </label>
                        <input type="email" name="support_email" value="{{ $settings['support_email']->value ?? '' }}"
                            placeholder="support@company.com"
                            class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-3 text-gray-200 placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition-all">
                    </div>

                    {{-- Company Address --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> Headquarters Address
                        </label>
                        <textarea name="company_address" rows="3" placeholder="123 Tech Avenue, City, Country"
                            class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-3 text-gray-200 placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 transition-all resize-none">{{ $settings['company_address']->value ?? '' }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-700/50">
                    <p class="text-xs text-gray-500">Changes apply platform-wide immediately after saving.</p>
                    <button type="submit"
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:scale-105 hover:shadow-indigo-500/50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Business Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- TAB 2: THEME & BRANDING                         --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div x-show="tab === 'theme'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
        <form action="{{ route('admin.customization.settings') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Logo Upload --}}
                <div class="bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
                    <h4 class="text-base font-bold text-gray-100 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Company Logo
                    </h4>
                    <p class="text-xs text-gray-500 mb-4">Appears in sidebar, invoices, and emails.</p>
                    @if(config('custom.company_logo'))
                        <div class="mb-4 p-4 bg-gray-800 rounded-xl border border-gray-700 flex justify-center">
                            <img src="{{ asset('storage/' . config('custom.company_logo')) }}" alt="Logo" class="h-16 object-contain">
                        </div>
                    @else
                        <div class="mb-4 p-4 bg-gray-800/50 rounded-xl border border-dashed border-gray-700 flex items-center justify-center h-20 text-gray-600">
                            <span class="text-sm">No logo uploaded</span>
                        </div>
                    @endif
                    <label class="block w-full cursor-pointer group">
                        <input type="file" name="logo" accept="image/*" class="sr-only">
                        <div class="flex items-center justify-center gap-2 border-2 border-dashed border-gray-600 hover:border-indigo-500 rounded-xl p-4 transition-colors group-hover:bg-indigo-500/5">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="text-sm text-gray-400 group-hover:text-indigo-400 transition">Upload Logo</span>
                        </div>
                    </label>
                </div>

                {{-- Favicon Upload --}}
                <div class="bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
                    <h4 class="text-base font-bold text-gray-100 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        Favicon
                    </h4>
                    <p class="text-xs text-gray-500 mb-4">Browser tab icon (ICO, PNG, 32×32 recommended).</p>
                    @if(config('custom.company_favicon'))
                        <div class="mb-4 p-4 bg-gray-800 rounded-xl border border-gray-700 flex justify-center">
                            <img src="{{ asset('storage/' . config('custom.company_favicon')) }}" alt="Favicon" class="h-8 w-8 object-contain">
                        </div>
                    @else
                        <div class="mb-4 p-4 bg-gray-800/50 rounded-xl border border-dashed border-gray-700 flex items-center justify-center h-20 text-gray-600">
                            <span class="text-sm">No favicon uploaded</span>
                        </div>
                    @endif
                    <label class="block w-full cursor-pointer group">
                        <input type="file" name="favicon" accept="image/*" class="sr-only">
                        <div class="flex items-center justify-center gap-2 border-2 border-dashed border-gray-600 hover:border-yellow-500 rounded-xl p-4 transition-colors group-hover:bg-yellow-500/5">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="text-sm text-gray-400 group-hover:text-yellow-400 transition">Upload Favicon</span>
                        </div>
                    </label>
                </div>

                {{-- Theme Color + Mode --}}
                <div class="bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
                    <h4 class="text-base font-bold text-gray-100 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        Colors & Mode
                    </h4>
                    <p class="text-xs text-gray-500 mb-5">Primary accent color and platform display mode.</p>

                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-wider">Primary Brand Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="theme_color" value="{{ config('custom.theme_color', '#4f46e5') }}"
                                class="w-14 h-14 rounded-xl cursor-pointer border-0 bg-transparent p-0 shadow-lg">
                            <div>
                                <p class="text-sm text-gray-300 font-medium">Accent Color</p>
                                <p class="text-xs text-gray-500">Used for buttons, highlights, and active states</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-wider">Display Mode</label>
                        <select name="dark_mode" class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-2.5 text-gray-200 focus:border-indigo-500 outline-none text-sm">
                            <option value="enabled" {{ (config('custom.dark_mode') === 'enabled') ? 'selected' : '' }}>🌑 Dark Mode</option>
                            <option value="disabled" {{ (config('custom.dark_mode') === 'disabled') ? 'selected' : '' }}>☀️ Light Mode</option>
                        </select>
                    </div>
                </div>

                {{-- Homepage Banner --}}
                <div class="lg:col-span-3 bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-xl p-6">
                    <h4 class="text-base font-bold text-gray-100 mb-1 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Homepage Hero Banner
                    </h4>
                    <p class="text-xs text-gray-500 mb-4">Displayed on the public shop/homepage. Recommended: 1200×400px, max 2MB.</p>
                    @if(config('custom.homepage_banner'))
                        <div class="mb-4 rounded-xl overflow-hidden border border-gray-700">
                            <img src="{{ asset('storage/' . config('custom.homepage_banner')) }}" alt="Banner" class="w-full h-32 object-cover">
                        </div>
                    @endif
                    <label class="block w-full cursor-pointer group">
                        <input type="file" name="homepage_banner" accept="image/*" class="sr-only">
                        <div class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-gray-600 hover:border-emerald-500 rounded-xl p-8 transition-colors group-hover:bg-emerald-500/5">
                            <svg class="w-8 h-8 text-gray-500 group-hover:text-emerald-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-sm text-gray-400 group-hover:text-emerald-400 font-medium transition">Click to Upload Hero Banner</span>
                            <span class="text-xs text-gray-600">PNG, JPG, WEBP up to 5MB</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Apply Theme Changes
                </button>
            </div>
        </form>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- TAB 3: FEATURE TOGGLES                          --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div x-show="tab === 'features'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-700/50 bg-gradient-to-r from-purple-900/20 to-transparent flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-500/20 rounded-lg"><svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg></div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-100">Module Management</h3>
                        <p class="text-xs text-gray-400">Enable or disable platform modules without touching code.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">{{ $toggles->where('is_active', true)->count() }}/{{ $toggles->count() }} Active</span>
                    <div class="h-2 w-24 bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full transition-all"
                            style="width: {{ $toggles->count() > 0 ? ($toggles->where('is_active', true)->count() / $toggles->count() * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.customization.toggles') }}" method="POST" class="p-8">
                @csrf
                @php
                $toggleIcons = [
                    'store_module'     => ['icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'blue', 'label' => 'Spare Parts Store'],
                    'repair_module'    => ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'color' => 'yellow', 'label' => 'Repair Service Booking'],
                    'warranty_module'  => ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'color' => 'green', 'label' => 'Warranty Management'],
                    'delivery_module'  => ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'orange', 'label' => 'Delivery System'],
                    'crm_module'       => ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'pink', 'label' => 'CRM System'],
                    'hrm_module'       => ['icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'purple', 'label' => 'HRM & Payroll'],
                    'marketing_module' => ['icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'color' => 'red', 'label' => 'Marketing System'],
                    'analytics_module' => ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'color' => 'indigo', 'label' => 'Business Analytics'],
                ];
                $colorMap = [
                    'blue'   => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                    'yellow' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                    'green'  => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                    'orange' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
                    'pink'   => 'bg-pink-500/20 text-pink-400 border-pink-500/30',
                    'purple' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                    'red'    => 'bg-red-500/20 text-red-400 border-red-500/30',
                    'indigo' => 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30',
                ];
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    @foreach($toggles as $toggle)
                    @php
                        $meta  = $toggleIcons[$toggle->name] ?? ['icon' => 'M4 6h16M4 12h16M4 18h16', 'color' => 'indigo', 'label' => ucfirst(str_replace('_', ' ', $toggle->name))];
                        $color = $colorMap[$meta['color']] ?? $colorMap['indigo'];
                        $inputName = 'toggle_' . $toggle->name;
                    @endphp
                    <div class="group relative bg-gray-800/40 border border-gray-700/50 rounded-2xl p-5 transition-all duration-300 hover:border-gray-500/80 hover:bg-gray-800/70 hover:shadow-xl {{ $toggle->is_active ? 'ring-1 ring-emerald-500/20' : '' }}">
                        <div class="flex items-start justify-between mb-4">
                            <div class="p-2.5 rounded-xl border {{ $color }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/>
                                </svg>
                            </div>
                            {{-- Toggle Switch --}}
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="{{ $inputName }}" value="1" class="sr-only peer"
                                    {{ $toggle->is_active ? 'checked' : '' }}>
                                <div class="relative w-12 h-6 bg-gray-700 rounded-full peer-checked:bg-emerald-500 transition-all duration-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6 shadow-inner">
                                </div>
                            </label>
                        </div>
                        <h4 class="font-semibold text-gray-100 text-sm mb-1">{{ $meta['label'] }}</h4>
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full {{ $toggle->is_active ? 'bg-emerald-500' : 'bg-gray-600' }}"></div>
                            <span class="text-xs {{ $toggle->is_active ? 'text-emerald-400' : 'text-gray-500' }}">
                                {{ $toggle->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-700/50">
                    <p class="text-xs text-gray-500">Disabled modules hide menu items and restrict API access automatically.</p>
                    <button type="submit"
                        class="flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-purple-500/30 transition-all duration-200 hover:scale-105">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Module Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- TAB 4: FORM BUILDER                             --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div x-show="tab === 'forms'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-2xl p-8 text-center">
            <div class="w-20 h-20 bg-indigo-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-indigo-500/30">
                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-100 mb-2">Dynamic Form Builder</h3>
            <p class="text-gray-400 max-w-md mx-auto mb-8">Create custom forms for service requests, warranty claims, and customer feedback — all without writing a single line of code.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('admin.forms.index') }}"
                    class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-8 py-3 rounded-xl shadow-lg shadow-indigo-500/30 transition-all hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Open Form Builder
                </a>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- TAB 5: PAGE BUILDER                             --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div x-show="tab === 'pages'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-2xl p-8 text-center">
            <div class="w-20 h-20 bg-emerald-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-emerald-500/30">
                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-100 mb-2">Dynamic Page Builder</h3>
            <p class="text-gray-400 max-w-md mx-auto mb-8">Build landing pages, offer pages, help pages, and homepage sections with block-based content — live preview included.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('admin.pages.index') }}"
                    class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold px-8 py-3 rounded-xl shadow-lg shadow-emerald-500/30 transition-all hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Open Page Builder
                </a>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- TAB 6: ROLES & PERMISSIONS                      --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div x-show="tab === 'roles'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-2xl p-8 text-center">
            <div class="w-20 h-20 bg-yellow-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-yellow-500/30">
                <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-100 mb-2">Role & Permission Management</h3>
            <p class="text-gray-400 max-w-md mx-auto mb-8">Create custom roles with fine-grained access control for orders, services, inventory, customers, reports, and settings.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('admin.roles.index') }}"
                    class="flex items-center gap-2 bg-yellow-600 hover:bg-yellow-500 text-white font-semibold px-8 py-3 rounded-xl shadow-lg shadow-yellow-500/30 transition-all hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Manage Roles & Access
                </a>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{-- TAB 7: NOTIFICATION TEMPLATES                   --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div x-show="tab === 'notifications'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-gray-900/60 backdrop-blur-xl rounded-2xl border border-gray-700/50 shadow-2xl overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-700/50 bg-gradient-to-r from-pink-900/20 to-transparent flex items-center gap-3">
                <div class="p-2 bg-pink-500/20 rounded-lg">
                    <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-100">Notification Templates</h3>
                    <p class="text-xs text-gray-400">Configure email, SMS, and WhatsApp messages for each platform event.</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                @foreach($templates as $tpl)
                <div class="group flex items-center justify-between p-5 bg-gray-800/40 border border-gray-700/50 rounded-xl hover:border-gray-600 hover:bg-gray-800/70 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="p-2.5 rounded-xl bg-pink-500/15 border border-pink-500/25">
                            <svg class="w-4 h-4 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-100">{{ $tpl->name }}</p>
                            <p class="text-xs text-gray-500 font-mono mt-0.5">Trigger: {{ $tpl->event_trigger }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Channel badges --}}
                        <div class="flex gap-1.5">
                            @if($tpl->email_body)
                                <span class="text-xs bg-blue-500/20 text-blue-400 border border-blue-500/30 px-2 py-0.5 rounded-full">EMAIL</span>
                            @endif
                            @if($tpl->sms_body)
                                <span class="text-xs bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded-full">SMS</span>
                            @endif
                            @if($tpl->whatsapp_body)
                                <span class="text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded-full">WhatsApp</span>
                            @endif
                        </div>

                        {{-- Active status + toggle --}}
                        <form action="{{ route('admin.notifications.toggle', $tpl->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition text-xs font-medium {{ $tpl->is_active ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-red-500/20 hover:text-red-400 hover:border-red-500/30' : 'bg-gray-700/50 text-gray-500 border border-gray-600 hover:bg-emerald-500/20 hover:text-emerald-400 hover:border-emerald-500/30' }}">
                                <div class="w-1.5 h-1.5 rounded-full {{ $tpl->is_active ? 'bg-emerald-400' : 'bg-gray-500' }}"></div>
                                {{ $tpl->is_active ? 'Active' : 'Disabled' }}
                            </button>
                        </form>

                        <a href="{{ route('admin.notifications.edit', $tpl->id) }}"
                            class="flex items-center gap-1.5 bg-indigo-500/20 hover:bg-indigo-500/40 text-indigo-400 border border-indigo-500/30 px-3 py-1.5 rounded-lg transition text-xs font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                    </div>
                </div>
                @endforeach

                @if($templates->isEmpty())
                <div class="text-center py-16">
                    <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <p class="text-gray-400">No notification templates found. Run the seeder to populate defaults.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Auto-switch to tab from URL param
    (function() {
        const url = new URL(window.location);
        const tab = url.searchParams.get('tab');
        if (tab) {
            document.addEventListener('alpine:init', () => {
                Alpine.store('customTab', tab);
            });
        }
    })();
</script>
@endpush
@endsection

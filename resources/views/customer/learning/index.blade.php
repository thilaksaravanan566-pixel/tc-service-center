@extends('layouts.customer')

@section('title', 'Tech Learning')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Tech Learning Center</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Level up your hardware knowledge with interactive games and expert guides from Thambu Computers.</p>
    </div>

    <!-- Games Selection -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Hardware Builder Game -->
        <div class="super-card overflow-hidden group">
            <div class="h-48 bg-gradient-to-br from-indigo-900 via-purple-900 to-black relative flex items-center justify-center overflow-hidden">
                <!-- Decorative elements -->
                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-blue-500 via-transparent to-transparent"></div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-purple-500 rounded-full blur-3xl opacity-30 group-hover:opacity-50 transition-opacity"></div>
                
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-4xl shadow-2xl transform group-hover:scale-110 transition-transform duration-300">
                        💻
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded-full uppercase tracking-wider">Mini Game</span>
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-full uppercase tracking-wider">Popular</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Build Your PC</h3>
                <p class="text-gray-600 text-sm mb-6">Master hardware assembly in our futuristic 3D simulation lab. Learn about slots, cooling, and performance.</p>
                <a href="{{ route('customer.tech-lab.dashboard') }}" class="btn-primary w-full py-3">
                    Enter Tech Lab
                </a>
            </div>
        </div>

        <!-- Coming Soon: Diagnostic Quiz -->
        <div class="super-card overflow-hidden opacity-75 grayscale hover:grayscale-0 transition-all">
            <div class="h-48 bg-gray-800 relative flex items-center justify-center">
                <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-4xl">
                    🔧
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="bg-black/50 text-white px-4 py-1 rounded-full text-xs font-bold tracking-widest uppercase">Coming Soon</span>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-400 mb-2">Hardware Diagnostic Quiz</h3>
                <p class="text-gray-500 text-sm mb-6">Test your troubleshooting skills with real-world scenarios handled by our technicians.</p>
                <button disabled class="w-full py-3 rounded-full bg-gray-200 text-gray-400 font-bold cursor-not-allowed">
                    Locked
                </button>
            </div>
        </div>

        <!-- Coming Soon: Maintenance Guide -->
        <div class="super-card overflow-hidden opacity-75 grayscale hover:grayscale-0 transition-all">
            <div class="h-48 bg-gray-800 relative flex items-center justify-center">
                <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-4xl">
                    📘
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="bg-black/50 text-white px-4 py-1 rounded-full text-xs font-bold tracking-widest uppercase">Coming Soon</span>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-400 mb-2">Interactive Maintenance</h3>
                <p class="text-gray-500 text-sm mb-6">Learn how to keep your PC running at peak performance with our step-by-step guides.</p>
                <button disabled class="w-full py-3 rounded-full bg-gray-200 text-gray-400 font-bold cursor-not-allowed">
                    Locked
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Add New Dealer')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.dealers.index') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white transition gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Dealers
    </a>
    <h2 class="text-xl font-bold text-white tracking-tight mt-2">Add New Dealer</h2>
    <p class="text-sm text-gray-400 mt-1">Register a new partner dealer and create their login credentials.</p>
</div>

<div class="max-w-4xl">

    {{-- Session / DB errors --}}
    @if(session('error'))
    <div class="mb-5 flex items-start gap-3 bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
    <div class="mb-5 bg-red-500/10 border border-red-500/20 text-red-400 text-sm px-4 py-3 rounded-xl">
        <p class="font-semibold mb-1">Please fix the following errors:</p>
        <ul class="list-disc pl-4 space-y-0.5">
            @foreach($errors->all() as $error)
                <li class="text-xs">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.dealers.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-white mb-6 border-b border-white/5 pb-2">Business Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Dealer Name --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Dealer Name (Contact Person)</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full @error('name') border-red-500 @enderror" placeholder="John Doe">
                    @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Business Name --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Business / Shop Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name') }}" required
                        class="w-full @error('business_name') border-red-500 @enderror" placeholder="Tech Hub Solutions">
                    @error('business_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Phone --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        class="w-full @error('phone') border-red-500 @enderror" placeholder="+91 9876543210">
                    @error('phone') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- GST Number --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">GST Number (Optional)</label>
                    <input type="text" name="gst_number" value="{{ old('gst_number') }}"
                        class="w-full @error('gst_number') border-red-500 @enderror" placeholder="GSTIN123456789">
                    @error('gst_number') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Address --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="text-sm font-medium text-gray-300">Business Address</label>
                    <textarea name="address" rows="3" required
                        class="w-full @error('address') border-red-500 @enderror" placeholder="Full shop address...">{{ old('address') }}</textarea>
                    @error('address') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="text-lg font-semibold text-white mb-6 border-b border-white/5 pb-2">Login Credentials</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Email / Username --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Email Address (Username)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-10 @error('email') border-red-500 @enderror" placeholder="dealer@example.com">
                    </div>
                    @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-2" x-data="{ show: false }">
                    <label class="text-sm font-medium text-gray-300">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="w-full pl-10 @error('password') border-red-500 @enderror" placeholder="Min 8 characters">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-white transition">
                            <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m4.533-4.533A9.06 9.06 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-2.101-2.101L3 3m5.898 5.898a3 3 0 114.243 4.243"/></svg>
                        </button>
                    </div>
                    @error('password') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.dealers.index') }}" class="px-6 py-2 rounded-xl text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 transition">Cancel</a>
            <button type="submit" class="btn-primary px-10">Create Dealer Account</button>
        </div>
    </form>
</div>
@endsection

@extends('layouts.dealer')

@section('title', 'Book New Service')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold text-white tracking-tight">Register Hardware</h2>
        <p class="text-gray-400 mt-2">Book a new repair or service order on behalf of your customer.</p>
    </div>
    <a href="{{ route('dealer.dashboard') }}" class="text-sm font-medium text-gray-400 hover:text-white transition">Cancel & Exit</a>
</div>

<div class="max-w-4xl">
    <form action="{{ route('dealer.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="card p-8">
            <h3 class="text-lg font-bold text-white mb-6 border-b border-white/5 pb-3">Device Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Device Type --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Device Type</label>
                    <select name="type" class="w-full" required>
                        <option value="laptop">Laptop Computer</option>
                        <option value="desktop">Desktop / Workstation</option>
                        <option value="printer">Printer / Copier</option>
                        <option value="repair">General Hardware Repair</option>
                    </select>
                </div>

                {{-- Brand --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Brand Name</label>
                    <input type="text" name="brand" placeholder="HP, Dell, Apple..." class="w-full" required>
                </div>

                {{-- Model --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Model Name / Number</label>
                    <input type="text" name="model" placeholder="Pavilion x360, XPS 15..." class="w-full" required>
                </div>

                {{-- Serial --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Serial Number (Optional)</label>
                    <input type="text" name="serial_number" placeholder="SN-XXXX-XXXX" class="w-full">
                </div>

                {{-- Problem --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Problem Description (Fault Details)</label>
                    <textarea name="problem" rows="4" class="w-full" placeholder="Ex: Screen flicker, Blue screen, Hinges broken, Slow performance..." required></textarea>
                </div>
            </div>
        </div>

        <div class="card p-8">
            <h3 class="text-lg font-bold text-white mb-6 border-b border-white/5 pb-3">Delivery & Attachments</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Delivery Mode --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Delivery Mode</label>
                    <select name="delivery_type" class="w-full" required>
                        <option value="take_away">In-Person Drop/Pickup</option>
                        <option value="delivery">Request Logistics Pickup</option>
                    </select>
                    <p class="text-[10px] text-gray-500 mt-1">If using logistics, our delivery partner will contact your shop.</p>
                </div>

                {{-- Photos --}}
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-400">Damage Photos (Max 5)</label>
                    <input type="file" name="photos[]" multiple class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end pt-4">
            <button type="submit" class="btn-primary px-12 text-sm uppercase tracking-widest font-bold">Register Order</button>
        </div>
    </form>
</div>
@endsection

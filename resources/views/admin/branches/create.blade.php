@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.branches.index') }}" class="text-gray-400 hover:text-white">← Back</a>
        <h1 class="text-2xl font-bold text-white">Add New Branch</h1>
    </div>
    <div class="bg-white/5 border border-white/10 rounded-xl p-6">
        <form action="{{ route('admin.branches.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-400 text-xs mb-1">Branch Name *</label>
                <input type="text" name="name" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2" required placeholder="e.g., TC Service Center - Velachery">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">City</label>
                    <input type="text" name="city" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2" placeholder="Chennai">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Phone</label>
                    <input type="text" name="phone" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2" placeholder="+91 XXXXXXXXXX">
                </div>
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Address</label>
                <textarea name="address" rows="2" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 resize-none" placeholder="Full address"></textarea>
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Email</label>
                <input type="email" name="email" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2" placeholder="branch@tcservice.com">
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Branch Manager</label>
                <select name="manager_id" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                    <option value="">-- No Manager Assigned --</option>
                    @foreach($managers as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->role }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Notes</label>
                <textarea name="notes" rows="2" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 resize-none"></textarea>
            </div>
            <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-500 text-white font-semibold py-2.5 rounded-lg transition-all">Create Branch</button>
        </form>
    </div>
</div>
@endsection

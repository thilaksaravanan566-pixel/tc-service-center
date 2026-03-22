@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.branches.index') }}" class="text-gray-400 hover:text-white">← Back</a>
        <h1 class="text-2xl font-bold text-white">Edit Branch: {{ $branch->name }}</h1>
    </div>
    <div class="bg-white/5 border border-white/10 rounded-xl p-6">
        <form action="{{ route('admin.branches.update', $branch) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-gray-400 text-xs mb-1">Branch Name *</label>
                <input type="text" name="name" value="{{ $branch->name }}" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">City</label>
                    <input type="text" name="city" value="{{ $branch->city }}" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ $branch->phone }}" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Address</label>
                <textarea name="address" rows="2" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2 resize-none">{{ $branch->address }}</textarea>
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Email</label>
                <input type="email" name="email" value="{{ $branch->email }}" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-400 text-xs mb-1">Branch Manager</label>
                <select name="manager_id" class="w-full bg-black/30 border border-white/10 text-white rounded-lg px-3 py-2">
                    <option value="">-- No Manager --</option>
                    @foreach($managers as $manager)
                    <option value="{{ $manager->id }}" {{ $branch->manager_id == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $branch->is_active ? 'checked' : '' }} class="w-4 h-4">
                <label for="is_active" class="text-gray-300 text-sm">Branch is Active</label>
            </div>
            <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-500 text-white font-semibold py-2.5 rounded-lg transition-all">Update Branch</button>
        </form>
    </div>
</div>
@endsection

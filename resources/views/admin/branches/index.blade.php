@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">🏢 Branch Management</h1>
            <p class="text-gray-400 text-sm mt-1">Multi-location service center branches</p>
        </div>
        <a href="{{ route('admin.branches.create') }}" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white text-sm rounded-lg transition-all font-medium flex items-center gap-2">
            + Add Branch
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @if(!empty($branches)) @foreach($branches as $branch)
        <div class="bg-white/5 border {{ $branch->is_active ? 'border-yellow-500/20' : 'border-white/10' }} rounded-xl p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="text-white font-bold">{{ $branch->name }}</h3>
                    <p class="text-gray-500 text-xs">{{ $branch->city }}</p>
                </div>
                <span class="px-2 py-1 rounded text-xs {{ $branch->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                    {{ $branch->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            @if($branch->address)
            <p class="text-gray-400 text-sm mb-2">📍 {{ $branch->address }}</p>
            @endif
            @if($branch->phone)
            <p class="text-gray-400 text-sm mb-2">📞 {{ $branch->phone }}</p>
            @endif
            @if($branch->manager)
            <p class="text-gray-400 text-sm mb-4">👤 Manager: {{ $branch->manager->name }}</p>
            @endif
            <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                <div class="bg-black/20 rounded-lg p-2">
                    <p class="text-white font-bold">{{ $branch->service_orders_count }}</p>
                    <p class="text-gray-500 text-xs">Services</p>
                </div>
                <div class="bg-black/20 rounded-lg p-2">
                    <p class="text-white font-bold">{{ $branch->product_orders_count }}</p>
                    <p class="text-gray-500 text-xs">Orders</p>
                </div>
                <div class="bg-black/20 rounded-lg p-2">
                    <p class="text-white font-bold">{{ $branch->employees_count }}</p>
                    <p class="text-gray-500 text-xs">Staff</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.branches.edit', $branch) }}" class="flex-1 text-center py-1.5 bg-blue-500/20 text-blue-400 text-sm rounded-lg hover:opacity-80">Edit</a>
                <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Delete branch?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 bg-red-500/20 text-red-400 text-sm rounded-lg hover:opacity-80">Del</button>
                </form>
            </div>
        </div>
        @endforeach @else
        <div class="col-span-3 text-center py-16 text-gray-500">
            <div class="text-5xl mb-4">🏢</div>
            <p class="text-lg">No branches yet. Create your first branch!</p>
            <a href="{{ route('admin.branches.create') }}" class="inline-block mt-4 px-6 py-2 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg transition-all">Add Branch</a>
        </div>
        @endif
    </div>

</div>
@endsection

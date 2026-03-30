@extends('layouts.admin')

@section('title', 'Dealer Management')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-white tracking-tight">Dealers</h2>
        <p class="text-sm text-gray-400 mt-1">Manage partner dealers and their access.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.dealers.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Dealer
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th>Dealer Name</th>
                    <th>Business Name</th>
                    <th>Phone</th>
                    <th>Total Orders</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dealers as $dealer)
                <tr class="group">
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($dealer->user->name ?? $dealer->business_name) }}&background=6366f1&color=fff&size=40" class="w-8 h-8 rounded-lg">
                            <div>
                                <p class="text-sm font-semibold text-white group-hover:text-indigo-400 transition">{{ $dealer->user->name ?? 'Unknown User' }}</p>
                                <p class="text-xs text-gray-500">{{ $dealer->user->email ?? 'No email' }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-sm font-medium text-gray-300">{{ $dealer->business_name }}</span>
                    </td>
                    <td>
                        <span class="text-sm text-gray-400">{{ $dealer->phone }}</span>
                    </td>
                    <td>
                        <div class="inline-flex px-2 py-1 bg-white/5 border border-white/10 rounded text-xs font-semibold text-gray-300">
                            {{ optional($dealer->user)->serviceOrders ? $dealer->user->serviceOrders->count() : 0 }}
                        </div>
                    </td>
                    <td>
                        @if($dealer->status === 'active')
                            <span class="badge badge-green">Active</span>
                        @else
                            <span class="badge badge-gray">Inactive</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2 text-gray-400">
                            <a href="{{ route('admin.dealers.show', $dealer) }}" class="p-1.5 hover:text-white hover:bg-white/10 rounded transition" title="View Dealer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.dealers.edit', $dealer) }}" class="p-1.5 hover:text-indigo-400 hover:bg-white/10 rounded transition" title="Edit Dealer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <form action="{{ route('admin.dealers.destroy', $dealer) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to deactivate this dealer?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 hover:text-red-400 hover:bg-white/10 rounded transition" title="Deactivate Dealer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-white/10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <p>No dealers found.</p>
                        <a href="{{ route('admin.dealers.create') }}" class="text-indigo-400 hover:text-indigo-300 text-sm mt-1 inline-block">Add your first dealer</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($dealers->hasPages())
        <div class="px-5 py-3 border-t border-white/5">
            {{ $dealers->links() }}
        </div>
    @endif
</div>
@endsection

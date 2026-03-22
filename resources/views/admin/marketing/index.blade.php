@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">📣 Marketing Campaigns</h1>
            <p class="text-gray-400 text-sm mt-1">Promotional campaigns, banners, and customer notifications</p>
        </div>
        <a href="{{ route('admin.marketing.create') }}" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white text-sm rounded-lg transition-all font-medium flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Campaign
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
            <p class="text-gray-400 text-xs mt-1">Total Campaigns</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-emerald-400">{{ $stats['active'] }}</p>
            <p class="text-gray-400 text-xs mt-1">Active</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-blue-400">{{ number_format($stats['sent']) }}</p>
            <p class="text-gray-400 text-xs mt-1">Total Sent</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-yellow-400">{{ number_format($stats['clicks']) }}</p>
            <p class="text-gray-400 text-xs mt-1">Total Clicks</p>
        </div>
    </div>

    {{-- Campaigns Table --}}
    <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left py-3 px-4">Campaign</th>
                    <th class="text-left py-3 px-4">Type</th>
                    <th class="text-left py-3 px-4">Target</th>
                    <th class="text-center py-3 px-4">Sent</th>
                    <th class="text-center py-3 px-4">Clicks</th>
                    <th class="text-center py-3 px-4">Status</th>
                    <th class="text-center py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($campaigns)) @foreach($campaigns as $campaign)
                <tr class="border-t border-white/5">
                    <td class="py-3 px-4">
                        <p class="text-white font-medium text-sm">{{ $campaign->name }}</p>
                        <p class="text-gray-500 text-xs">{{ str($campaign->description)->limit(50) }}</p>
                        @if($campaign->discount_code)
                        <span class="text-xs bg-yellow-500/20 text-yellow-400 px-2 py-0.5 rounded font-mono">{{ $campaign->discount_code }}</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-xs {{ match($campaign->type) {
                            'push' => 'bg-purple-500/20 text-purple-400',
                            'discount' => 'bg-yellow-500/20 text-yellow-400',
                            'banner' => 'bg-blue-500/20 text-blue-400',
                            default => 'bg-gray-500/20 text-gray-400'
                        } }} uppercase">{{ $campaign->type }}</span>
                    </td>
                    <td class="py-3 px-4 text-gray-400 text-sm capitalize">{{ str_replace('_', ' ', $campaign->target_audience) }}</td>
                    <td class="py-3 px-4 text-center text-white">{{ $campaign->sent_count }}</td>
                    <td class="py-3 px-4 text-center text-blue-400">{{ $campaign->click_count }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="px-2 py-1 rounded text-xs {{ $campaign->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-500/20 text-gray-400' }}">
                            {{ $campaign->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <form action="{{ route('admin.marketing.toggle', $campaign->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs px-2 py-1 {{ $campaign->is_active ? 'bg-gray-500/20 text-gray-400' : 'bg-emerald-500/20 text-emerald-400' }} rounded hover:opacity-80">
                                    {{ $campaign->is_active ? 'Pause' : 'Activate' }}
                                </button>
                            </form>
                            <a href="{{ route('admin.marketing.edit', $campaign->id) }}" class="text-xs px-2 py-1 bg-blue-500/20 text-blue-400 rounded hover:opacity-80">Edit</a>
                            <form action="{{ route('admin.marketing.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('Delete campaign?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs px-2 py-1 bg-red-500/20 text-red-400 rounded hover:opacity-80">Del</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach @else
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-500">
                        <div class="text-4xl mb-3">📣</div>
                        <p>No campaigns yet. Create your first campaign!</p>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-white/5">{{ $campaigns->links() }}</div>
    </div>

</div>
@endsection

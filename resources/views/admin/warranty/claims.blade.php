@extends('layouts.admin')

@section('content')
<div class="p-6 md:p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Warranty Claims</h1>
            <p class="text-gray-400 text-sm mt-1">Manage customer warranty claim tickets</p>
        </div>
        <a href="{{ route('admin.warranty.certificates') }}" class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold px-5 py-2.5 rounded-lg text-sm flex items-center gap-2 transition-colors">
            🛡️ Manage Certificates
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-bold">✅ {{ session('success') }}</div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
            <p class="text-3xl font-black text-yellow-400">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Pending</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
            <p class="text-3xl font-black text-blue-400">{{ $stats['reviewing'] }}</p>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Reviewing</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
            <p class="text-3xl font-black text-green-400">{{ $stats['approved'] }}</p>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Approved</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 text-center">
            <p class="text-3xl font-black text-red-400">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Rejected</p>
        </div>
    </div>

    <!-- Claims Table -->
    <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/10">
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Customer</th>
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Product / Service</th>
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Description</th>
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Status</th>
                    <th class="text-left px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Submitted</th>
                    <th class="text-right px-6 py-4 text-gray-400 font-black uppercase tracking-widest text-[10px]">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($claims)) @foreach($claims as $claim)
                <tr class="border-b border-white/5 hover:bg-white/3 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-white font-bold">{{ $claim->customer?->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $claim->customer?->email }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-300 text-sm">
                        {{ $claim->certificate?->sparePart?->name ?? 'Service: ' . $claim->certificate?->serviceOrder?->tc_job_id ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs max-w-xs">
                        {{ str($claim->description)->limit(80) }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                            @if($claim->status === 'approved' || $claim->status === 'resolved') bg-green-500/10 text-green-400
                            @elseif($claim->status === 'rejected') bg-red-500/10 text-red-400
                            @elseif($claim->status === 'reviewing') bg-blue-500/10 text-blue-400
                            @else bg-yellow-500/10 text-yellow-400 @endif">
                            {{ ucfirst($claim->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $claim->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.warranty.claims.show', $claim->id) }}"
                            class="bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-400 border border-yellow-500/20 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-colors">
                            Review
                        </a>
                    </td>
                </tr>
                @endforeach @else
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">No warranty claims found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $claims->links() }}</div>
</div>
@endsection

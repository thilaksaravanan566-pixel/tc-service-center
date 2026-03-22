@extends('layouts.admin')

@section('title', 'Field Visit Operations')

@section('content')
<div class="mb-10 flex items-end justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic uppercase italic underline underline-offset-8 decoration-gray-200">Field Partner Surveillance</h1>
        <p class="text-gray-500 mt-2 font-medium">Monitoring scheduled technician visits, facility audits, and live GPS tracking.</p>
    </div>
    <div class="flex gap-4">
        <a href="{{ route('admin.logistics.createVisit') }}" class="btn btn-primary bg-indigo-600 px-8 py-4 rounded-2xl shadow-xl shadow-indigo-600/30 font-black text-white flex items-center gap-3 cursor-pointer hover:scale-105 active:scale-95 transition-all">
            <i class="fas fa-calendar-plus text-lg"></i>
            SCHEDULE VISIT
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10 leading-none">
    <div class="card p-8 border-0 bg-white shadow-2xl rounded-3xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-32 h-32 bg-indigo-50 rounded-full blur-3xl group-hover:bg-indigo-100 transition duration-700"></div>
        <div class="relative">
            <p class="text-[10px] uppercase font-extrabold text-gray-500 tracking-widest mb-4">Total Scheduled Visits</p>
            <p class="text-5xl font-black italic tracking-tighter text-gray-900 leading-none">{{ $visits->count() }}</p>
            <p class="text-[10px] font-bold text-indigo-600 mt-4 uppercase flex items-center gap-1">
                <i class="fas fa-user-clock"></i> Active Fleet Tracking
            </p>
        </div>
    </div>
    
    <div class="card p-8 border-0 bg-emerald-600 text-white shadow-2xl rounded-3xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition duration-700"></div>
        <div class="relative">
            <p class="text-[10px] uppercase font-extrabold text-white/50 tracking-widest mb-4">Successful Closures</p>
            <p class="text-5xl font-black italic tracking-tighter text-white leading-none">{{ $visits->where('status', 'completed')->count() }}</p>
            <p class="text-[10px] font-bold text-emerald-300 mt-4 uppercase flex items-center gap-1">
                <i class="fas fa-check-double rotate-12"></i> Verification Protocol
            </p>
        </div>
    </div>

    <div class="md:col-span-2 card p-8 border-0 bg-gray-900 text-white shadow-2xl rounded-3xl relative overflow-hidden group flex items-center justify-between">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-white/10 rounded-full blur-3xl transition duration-1000 group-hover:scale-150"></div>
        <div class="relative">
            <h3 class="text-2xl font-black italic uppercase tracking-tighter mb-2">Live <br> Geo Surveillance</h3>
            <p class="text-[11px] text-gray-400 font-medium leading-relaxed max-w-xs">Tracking technician check-ins via mobile GPS tokens. Authorized staff only.</p>
        </div>
        <i class="fas fa-map-marked-alt text-7xl text-white/10 transition duration-700 group-hover:scale-110"></i>
    </div>
</div>

<div class="card p-0 border-0 bg-white shadow-2xl rounded-[3rem] overflow-hidden group">
    <div class="px-10 py-8 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-black italic uppercase tracking-tighter text-gray-900 leading-none">Visit Activity Stream</h3>
        <div class="flex gap-4">
            <a href="{{ route('admin.logistics.index') }}" class="btn btn-secondary border border-gray-200 bg-white shadow-sm font-bold text-[10px] uppercase tracking-widest px-6 py-3 rounded-2xl">
               <i class="fas fa-box-open mr-2"></i> Logistics Dashboard
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Assigned Technician</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Partner Facility</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Visit Meta Logs</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Check-In Status</th>
                    <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right leading-none">Surveillance</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100/50">
                @forelse($visits as $visit)
                <tr class="hover:bg-indigo-50/20 transition group/row">
                    <td class="px-10 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-lg shadow-sm border border-indigo-100 group-hover/row:scale-110 transition duration-500">
                                {{ substr($visit->assignedTo->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-base font-black text-gray-900 tracking-tighter leading-none mb-1">{{ $visit->assignedTo->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic tracking-tighter">ID: TECH-{{ $visit->assignedTo->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-10 py-6">
                        <p class="text-sm font-black text-gray-900 leading-none mb-1 italic tracking-tighter">{{ $visit->dealer->business_name }}</p>
                        <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest">{{ $visit->dealer->user->name }}</p>
                    </td>
                    <td class="px-10 py-6 leading-none">
                        <div class="flex items-center gap-2 mb-2">
                             <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full"></span>
                             <p class="text-[10px] font-bold text-gray-700 uppercase tracking-widest italic">{{ $visit->purpose }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                             <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest shadow-none">{{ $visit->visit_date->format('d M, Y') }}</p>
                        </div>
                    </td>
                    <td class="px-10 py-6">
                         @if($visit->check_in_at)
                         <div class="leading-none">
                             <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest leading-none mb-1 flex items-center gap-2 italic">
                                 <i class="fas fa-check-circle"></i> VIRTUAL CHECK-IN
                             </p>
                             <p class="text-[10px] text-gray-400 font-bold font-mono tracking-widest">{{ $visit->check_in_at->format('H:i A') }}</p>
                         </div>
                         @else
                         <span class="px-3 py-1.5 bg-yellow-100 text-yellow-700 text-[9px] font-black uppercase rounded-lg border border-yellow-100 shadow-sm tracking-widest italic">AWAITING ENTRY</span>
                         @endif
                    </td>
                    <td class="px-10 py-6 text-right">
                        <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border shadow-sm
                            @if($visit->status === 'completed') bg-emerald-100 text-emerald-700 border-emerald-100 @elseif($visit->status === 'in_progress') bg-blue-100 text-blue-700 border-blue-100 @else bg-gray-100 text-gray-700 border-gray-100 @endif">
                            {{ strtoupper($visit->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-20 text-center text-gray-400 font-bold uppercase tracking-widest italic opacity-40">Zero Surveillance Records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-10 py-8 bg-gray-50/50 border-t border-gray-100">
        {{ $visits->links() }}
    </div>
</div>
@endsection

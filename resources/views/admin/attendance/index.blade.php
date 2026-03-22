@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto animate-fade-in-up">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Biometric <span class="text-green-600">Attendance</span></h1>
                <p class="text-sm text-slate-500 font-medium">ZKTeco hardware synchronization & logs</p>
            </div>
            
            <form action="{{ route('admin.attendance.sync') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="device_ip" value="192.168.1.201" placeholder="ZKTeco IP Address" class="px-4 py-2 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-green-100 text-sm font-bold text-slate-600 w-40">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-green-200 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Sync Device
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl font-bold flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl font-bold">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-slate-500 text-[11px] uppercase font-black tracking-widest">
                        <th class="p-5">Employee / User</th>
                        <th class="p-5">ZKTeco ID</th>
                        <th class="p-5 text-center">Event Type</th>
                        <th class="p-5 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @if(!empty($attendances)) @foreach($attendances as $att)
                    <tr class="hover:bg-green-50/30 transition-colors group">
                        <td class="p-5">
                            @if($att->user)
                                <div class="font-bold text-slate-800">{{ $att->user->name }}</div>
                                <div class="text-[10px] text-slate-400 uppercase tracking-widest">{{ $att->user->role }}</div>
                            @else
                                <span class="text-red-500 font-bold text-xs italic">Unmapped Biometric User</span>
                            @endif
                        </td>
                        <td class="p-5">
                            <span class="font-mono text-slate-500 bg-slate-100 px-2 py-1 rounded-md text-xs font-black">{{ $att->biometric_id }}</span>
                        </td>
                        <td class="p-5 text-center">
                            @if(in_array($att->type, ['check_in', 'overtime_in']))
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">IN: {{ str_replace('_', ' ', $att->type) }}</span>
                            @elseif(in_array($att->type, ['check_out', 'overtime_out']))
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">OUT: {{ str_replace('_', ' ', $att->type) }}</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">{{ str_replace('_', ' ', $att->type) }}</span>
                            @endif
                        </td>
                        <td class="p-5 text-right font-black text-slate-700">
                            {{ $att->timestamp->format('d M Y, H:i A') }}
                        </td>
                    </tr>
                    @endforeach @else
                    <tr>
                        <td colspan="4" class="p-10 text-center text-slate-400 font-bold italic">
                            No attendance logs synced. Connect ZKTeco device to populate data.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 flex justify-between items-center">
            <div>
                <form action="{{ route('admin.attendance.clear') }}" method="POST" onsubmit="return confirm('WARNING: This will permanently wipe attendance data off the physical ZKTeco device! Continue?');">
                    @csrf
                    <input type="hidden" name="device_ip" value="192.168.1.201">
                    <button type="submit" class="text-red-400 hover:text-red-600 text-[10px] font-black uppercase tracking-widest transition-colors">Clear Device Memory ⚠️</button>
                </form>
            </div>
            
            {{ $attendances ? $attendances->links() : '' }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="p-8 bg-[#050505] min-h-screen text-gray-200">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-black text-white">Warranty <span class="text-[#d4af37]">Tickets</span></h1>
                <p class="text-sm text-gray-400 font-medium">Verify claimed warranty tickets and track requested delivery modes.</p>
            </div>
        </div>

        <div class="glass-panel overflow-hidden border border-[#222]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#0a0a0a] border-b border-[#222]">
                    <tr class="text-gray-500 text-[11px] uppercase font-black tracking-widest">
                        <th class="p-5">Job ID & Details</th>
                        <th class="p-5">Customer Info</th>
                        <th class="p-5">Request Issue</th>
                        <th class="p-5">Ticket Status</th>
                        <th class="p-5 text-center">Drop-off / Delivery</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1a1a1a] text-sm">
                    @if(!empty($tickets)) @foreach($tickets as $ticket)
                    <tr class="hover:bg-[#111] transition-colors group">
                        <td class="p-5">
                            <span class="font-bold text-white block">{{ $ticket->tc_job_id }}</span>
                            <span class="text-[10px] text-gray-500 font-medium uppercase mt-1 break-all w-40 inline-block block">{{ $ticket->device->brand }} {{ $ticket->device->model }}</span>
                        </td>
                        
                        <td class="p-5">
                            <p class="font-bold text-white">{{ $ticket->customer->name }}</p>
                            <p class="text-xs text-gray-400">{{ $ticket->customer->mobile }}</p>
                        </td>

                        <td class="p-5">
                            <p class="text-xs text-gray-300 w-64 line-clamp-3 leading-relaxed">{{ $ticket->fault_details ?: 'No detailed problem described.' }}</p>
                        </td>

                        <td class="p-5">
                            <div class="space-y-1">
                                <p class="text-xs font-bold uppercase text-[#d4af37] tracking-wider">{{ str_replace('_', ' ', $ticket->status) }}</p>
                                <p class="text-[10px] text-gray-500">{{ $ticket->created_at->format('d M y, h:i A') }}</p>
                            </div>
                        </td>

                        <td class="p-5 text-center">
                            @if($ticket->delivery_type == 'delivery')
                                <span class="inline-block px-3 py-1 font-bold rounded-lg border border-blue-900 bg-blue-900/30 text-blue-400 text-[10px] uppercase tracking-widest">
                                    🚚 Home Delivery
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 font-bold rounded-lg border border-orange-900 bg-orange-900/30 text-orange-400 text-[10px] uppercase tracking-widest">
                                    🏬 Store Take Away
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach @else
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-600 font-bold italic">
                            No warranty tickets currently logged.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

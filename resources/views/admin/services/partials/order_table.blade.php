@if($orders->count() > 0)
    @foreach($orders as $order)
    <tr class="hover:bg-blue-50/30 transition-colors group">
        <td class="p-5">
            <div class="flex flex-col">
                <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100 flex self-start">
                    {{ $order->tc_job_id }}
                </span>
                <span class="text-[10px] text-slate-400 mt-1 uppercase font-bold">{{ $order->created_at->format('d M Y') }}</span>
            </div>
        </td>
        
        <td class="p-5">
            <div class="font-bold text-slate-800">{{ $order->device->customer->name ?? $order->dealer->name }}</div>
            <div class="text-xs text-slate-400 font-medium">{{ $order->device->customer->mobile ?? $order->dealer->mobile }}</div>
        </td>

        <td class="p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-slate-100 rounded-lg text-slate-500">
                    @if($order->device->type == 'laptop') 💻 @elseif($order->device->type == 'printer') 🖨️ @else 🖥️ @endif
                </div>
                <div>
                    <div class="text-sm font-bold text-slate-700">{{ $order->device->brand }}</div>
                    <div class="text-[11px] text-slate-400">{{ $order->device->model }}</div>
                </div>
            </div>
        </td>

        <td class="p-5">
            <div class="flex flex-col gap-1">
                @php
                    $typeColors = [
                        'dealer' => 'bg-blue-100 text-blue-700',
                        'online' => 'bg-emerald-100 text-emerald-700',
                        'walkin' => 'bg-amber-100 text-amber-700',
                    ];
                    $priorityColors = [
                        'low' => 'bg-slate-100 text-slate-600',
                        'medium' => 'bg-blue-100 text-blue-600',
                        'high' => 'bg-red-100 text-red-600',
                    ];
                @endphp
                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider {{ $typeColors[$order->order_type] ?? 'bg-slate-100' }} inline-flex self-start">
                    {{ $order->order_type }}
                </span>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Priority: <span class="{{ $order->priority == 'high' ? 'text-red-500' : '' }}">{{ $order->priority }}</span></span>
            </div>
        </td>

        <td class="p-5 text-center">
            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tighter 
                {{ in_array($order->status, ['received', 'pending']) ? 'bg-amber-100 text-amber-700' : ($order->status == 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700') }}">
                {{ str_replace('_', ' ', $order->status) }}
            </span>
        </td>

        <td class="p-5 text-right">
            <a href="{{ route('admin.services.show', $order->id) }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 group-hover:text-blue-600 transition-colors">
                View <span>→</span>
            </a>
        </td>
    </tr>
    @endforeach
@else
<tr>
    <td colspan="6" class="p-20 text-center">
        <div class="text-slate-300 mb-2">No service orders found</div>
        <a href="{{ route('admin.services.create') }}" class="text-blue-600 font-bold hover:underline">Register your first job</a>
    </td>
</tr>
@endif

@if($orders->hasPages())
<tr>
    <td colspan="6" class="p-5">
        <div class="ajax-pagination">
            {{ $orders->links() }}
        </div>
    </td>
</tr>
@endif

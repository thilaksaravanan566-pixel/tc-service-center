@extends('layouts.admin')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-black text-slate-900 mb-8">Service <span class="text-blue-600">Payments</span></h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                    <tr>
                        <th class="p-6">Transaction ID</th>
                        <th class="p-6">Customer & Order</th>
                        <th class="p-6">Amount</th>
                        <th class="p-6">Status</th>
                        <th class="p-6 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="text-sm border-t border-slate-100 divide-y divide-slate-100 font-medium">
                    @if(!empty($payments)) @foreach($payments as $payment)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-6 font-mono text-xs">{{ $payment->transaction_id ?? 'PENDING-' . $payment->id }}</td>
                        <td class="p-6">
                            <a href="{{ route('admin.services.show', $payment->service_order_id) }}" class="text-blue-600 font-bold hover:underline block mb-1">
                                {{ $payment->serviceOrder->tc_job_id }}
                            </a>
                            <div class="text-[10px] text-slate-400 uppercase tracking-widest">{{ $payment->serviceOrder->customer->name ?? 'N/A' }}</div>
                        </td>
                        <td class="p-6 text-slate-900 font-black">₹{{ number_format($payment->amount, 2) }}</td>
                        <td class="p-6">
                            @if($payment->status == 'completed')
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Paid</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Pending</span>
                            @endif
                        </td>
                        <td class="p-6 text-right text-slate-500 text-xs font-bold uppercase tracking-widest">
                            {{ $payment->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach @else
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-400 font-bold">No payment records found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <div class="mt-8">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection

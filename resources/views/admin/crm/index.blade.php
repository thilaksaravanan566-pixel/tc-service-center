@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">👥 Customer CRM</h1>
            <p class="text-gray-400 text-sm mt-1">Customer database, purchase history, and follow-up management</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
            <p class="text-gray-400 text-xs mt-1">Total Customers</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-emerald-400">{{ $stats['new_this_month'] }}</p>
            <p class="text-gray-400 text-xs mt-1">New This Month</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-blue-400">{{ $stats['with_repairs'] }}</p>
            <p class="text-gray-400 text-xs mt-1">Used Repair Service</p>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-xl p-4 text-center">
            <p class="text-3xl font-bold text-yellow-400">{{ $stats['with_purchases'] }}</p>
            <p class="text-gray-400 text-xs mt-1">Made Purchases</p>
        </div>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, mobile, email..." class="flex-1 bg-black/30 border border-white/10 text-white rounded-lg px-4 py-2 text-sm">
        <select name="sort" class="bg-black/30 border border-white/10 text-white rounded-lg px-4 py-2 text-sm">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
            <option value="most_orders" {{ request('sort') == 'most_orders' ? 'selected' : '' }}>Most Repairs</option>
            <option value="most_purchases" {{ request('sort') == 'most_purchases' ? 'selected' : '' }}>Most Purchases</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white text-sm rounded-lg transition-all">Search</button>
    </form>

    {{-- Customer Table --}}
    <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left py-3 px-4">Customer</th>
                    <th class="text-left py-3 px-4">Contact</th>
                    <th class="text-center py-3 px-4">Repairs</th>
                    <th class="text-center py-3 px-4">Purchases</th>
                    <th class="text-center py-3 px-4">Warranty Claims</th>
                    <th class="text-left py-3 px-4">Joined</th>
                    <th class="text-center py-3 px-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($customers)) @foreach($customers as $customer)
                <tr class="border-t border-white/5 hover:bg-white/3">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=1a1a2e&color=ca8a04&size=36" class="w-9 h-9 rounded-full">
                            <div>
                                <p class="text-white font-medium text-sm">{{ $customer->name }}</p>
                                <p class="text-gray-500 text-xs">#{{ $customer->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <p class="text-gray-300 text-sm">{{ $customer->mobile }}</p>
                        <p class="text-gray-500 text-xs">{{ $customer->email }}</p>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="text-blue-400 font-semibold">{{ $customer->service_orders_count }}</span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="text-emerald-400 font-semibold">{{ $customer->product_orders_count }}</span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="text-yellow-400 font-semibold">{{ $customer->warranty_claims_count }}</span>
                    </td>
                    <td class="py-3 px-4 text-gray-500 text-xs">{{ $customer->created_at->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-center">
                        <a href="{{ route('admin.crm.show', $customer->id) }}" class="px-3 py-1 bg-blue-600/20 hover:bg-blue-600/40 text-blue-400 text-xs rounded-lg transition-all">
                            View Profile
                        </a>
                    </td>
                </tr>
                @endforeach @else
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-500">No customers found.</td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-white/5">{{ $customers->withQueryString()->links() }}</div>
    </div>

</div>
@endsection

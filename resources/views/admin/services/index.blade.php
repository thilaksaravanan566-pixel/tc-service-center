@extends('layouts.admin')

@section('content')
<div class="px-5 py-5 sm:px-8 sm:py-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                    Service <span class="text-blue-600 bg-blue-50 px-2 rounded-lg">Orders</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1 font-bold uppercase tracking-widest leading-none">
                    Admin <span class="text-slate-200mx-1">/</span> Management <span class="text-slate-200 mx-1">/</span> Dashboard
                </p>
            </div>
            
            <a href="{{ route('admin.services.create') }}" class="group bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black shadow-xl shadow-blue-200 transition-all flex items-center gap-3 active:scale-95">
                <span class="text-xl group-hover:rotate-90 transition-transform">+</span> New Service Job
            </a>
        </div>

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-lg transition-shadow cursor-pointer filter-btn active" data-type="">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 font-black text-xl">
                        {{ $counts['all'] }}
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Orders</div>
                        <div class="text-lg font-black text-slate-800">All Jobs</div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-lg transition-shadow cursor-pointer filter-btn" data-type="dealer">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 font-black text-xl">
                        {{ $counts['dealer'] }}
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Partner Orders</div>
                        <div class="text-lg font-black text-slate-800">Dealers</div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-lg transition-shadow cursor-pointer filter-btn" data-type="online">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 font-black text-xl">
                        {{ $counts['online'] }}
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Digital Bookings</div>
                        <div class="text-lg font-black text-slate-800">Online</div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-lg transition-shadow cursor-pointer filter-btn" data-type="walkin">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 font-black text-xl">
                        {{ $counts['walkin'] }}
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Shop Customers</div>
                        <div class="text-lg font-black text-slate-800">Walk-in</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6 flex flex-col md:flex-row gap-4 items-center">
            <div class="relative w-full md:w-96">
                <input type="text" id="order-search" placeholder="Search ID, Customer, or Device..." class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-3 pl-12 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium text-slate-700">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
            </div>
            
            <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 no-scrollbar">
                @foreach(['received', 'pending', 'completed', 'delivered'] as $status)
                <button class="status-filter-btn whitespace-nowrap px-5 py-2.5 rounded-xl border border-slate-200 bg-white font-bold text-xs text-slate-600 hover:bg-slate-50 transition-all" data-status="{{ $status }}">
                    {{ ucfirst($status) }}
                </button>
                @endforeach
                <button id="clear-filters" class="whitespace-nowrap px-5 py-2.5 rounded-xl border border-red-100 bg-red-50 font-bold text-xs text-red-600 hover:bg-red-100 transition-all">
                    Reset
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden glass-card">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr class="text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <th class="p-6">Job Identity</th>
                            <th class="p-6">Entity Details</th>
                            <th class="p-6">Device Profile</th>
                            <th class="p-6">Type / Priority</th>
                            <th class="p-6 text-center">Current Status</th>
                            <th class="p-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="order-table-body" class="divide-y divide-slate-50">
                        @include('admin.services.partials.order_table')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .filter-btn.active {
        border-color: #3b82f6;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1);
        background: #f8faff;
    }

    .status-filter-btn.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let currentType = '';
        let currentStatus = '';
        let currentSearch = '';

        function fetchOrders(page = 1) {
            $('#order-table-body').css('opacity', '0.5');
            $.ajax({
                url: "{{ route('admin.services.index') }}",
                data: {
                    page: page,
                    type: currentType,
                    status: currentStatus,
                    search: currentSearch
                },
                success: function(response) {
                    $('#order-table-body').html(response).css('opacity', '1');
                }
            });
        }

        // Search with Debounce
        let searchTimer;
        $('#order-search').on('keyup', function() {
            clearTimeout(searchTimer);
            currentSearch = $(this).val();
            searchTimer = setTimeout(() => fetchOrders(), 300);
        });

        // Type Filter (Cards)
        $('.filter-btn').on('click', function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            currentType = $(this).data('type');
            fetchOrders();
        });

        // Status Filter (Pills)
        $('.status-filter-btn').on('click', function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                currentStatus = '';
            } else {
                $('.status-filter-btn').removeClass('active');
                $(this).addClass('active');
                currentStatus = $(this).data('status');
            }
            fetchOrders();
        });

        // Clear Filters
        $('#clear-filters').on('click', function() {
            $('#order-search').val('');
            $('.filter-btn').removeClass('active').first().addClass('active');
            $('.status-filter-btn').removeClass('active');
            currentType = '';
            currentStatus = '';
            currentSearch = '';
            fetchOrders();
        });

        // AJAX Pagination
        $(document).on('click', '.ajax-pagination a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetchOrders(page);
        });
    });
</script>
@endsection
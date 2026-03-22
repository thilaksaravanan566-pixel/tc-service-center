@extends('layouts.dealer')

@section('title', 'Partner B2B Shop')

@section('content')
<div class="mb-10 flex items-end justify-between">
    <div>
        <h1 class="text-4xl font-black text-white tracking-tighter uppercase italic">Partner <span class="text-indigo-500">Procurement</span> Hub</h1>
        <p class="text-gray-400 mt-2 font-medium tracking-tight">Access exclusive partner-only wholesale pricing for your retail inventory.</p>
    </div>
    <div class="flex gap-4">
        <a href="{{ route('dealer.orders.history') }}" class="btn btn-secondary border border-white/5 bg-white/5 text-gray-300 hover:text-white transition">
            <i class="fas fa-history mr-2"></i> Order History
        </a>
        <div class="h-10 w-px bg-white/10 mx-2"></div>
        <div id="cart-counter" class="bg-indigo-600 px-6 py-2 rounded-2xl shadow-2xl shadow-indigo-600/30 font-black text-white flex items-center gap-3 cursor-pointer hover:scale-105 active:scale-95 transition-all">
            <i class="fas fa-shopping-cart text-lg"></i>
            <span id="total-items">0</span> ITEMS
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
    <!-- Category Sidebar -->
    <div class="lg:col-span-1 space-y-8">
        <div class="card p-8 border-0 bg-white shadow-2xl rounded-3xl relative overflow-hidden group">
             <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full blur-2xl group-hover:bg-indigo-100 transition duration-700"></div>
             <div class="relative">
                <h3 class="text-xs uppercase font-extrabold text-indigo-600 tracking-widest mb-6 border-b border-indigo-50/50 pb-3">Department Selection</h3>
                <div class="space-y-2">
                    @php $categories = ['SSD', 'RAM', 'Display', 'Battery', 'Accessories']; @endphp
                    @foreach($categories as $cat)
                    <div class="flex items-center justify-between p-3 rounded-2xl transition hover:bg-indigo-50 cursor-pointer group/item">
                        <span class="text-sm font-bold text-gray-700 group-hover/item:text-indigo-600">{{ $cat }}</span>
                        <span class="text-[10px] px-2 py-0.5 bg-gray-100 rounded-lg font-black text-gray-400 group-hover/item:bg-indigo-600 group-hover/item:text-white transition">AUTO</span>
                    </div>
                    @endforeach
                </div>
             </div>
        </div>

        <div class="card p-8 border-0 bg-indigo-600 text-white shadow-2xl rounded-3xl relative overflow-hidden group">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/5 rounded-full blur-3xl transition duration-1000 group-hover:scale-150"></div>
            <div class="relative">
                <h3 class="text-lg font-black italic uppercase tracking-tighter mb-4">Partner <br> Benefits Active</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-xl bg-white/10 border border-white/5 flex items-center justify-center shrink-0">
                            <i class="fas fa-percent text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-indigo-200 uppercase leading-none mb-1">Price Protection</p>
                            <p class="text-[10px] text-indigo-100/60 leading-tight">Wholesale rates exclusively for our registered partners.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-xl bg-white/10 border border-white/5 flex items-center justify-center shrink-0">
                            <i class="fas fa-truck-loading text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-indigo-200 uppercase leading-none mb-1">Bulk Logistics</p>
                            <p class="text-[10px] text-indigo-100/60 leading-tight">Priority door-step delivery for all partner orders.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="lg:col-span-3">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($products as $product)
            <div class="card p-0 border-0 bg-white shadow-xl rounded-3xl overflow-hidden group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 cursor-default">
                <div class="h-44 bg-gray-100 relative overflow-hidden border-b border-gray-50">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent"></div>
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg shadow-lg shadow-indigo-600/20">{{ $product->category }}</span>
                    </div>
                    @if($product->stock_quantity < 5)
                    <div class="absolute top-4 right-4 animate-bounce">
                        <span class="px-3 py-1 bg-red-500 text-white text-[9px] font-black uppercase tracking-widest rounded-lg shadow-lg shadow-red-500/20">Critical Stock</span>
                    </div>
                    @endif
                    <div class="w-full h-full flex items-center justify-center text-gray-200 text-6xl opacity-50 transition duration-500 group-hover:scale-110">
                        <i class="fas fa-box-open"></i>
                    </div>
                </div>

                <div class="p-6">
                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">{{ $product->brand }}</p>
                    <h4 class="text-lg font-black text-gray-900 leading-tight tracking-tighter mb-2 group-hover:text-indigo-600 transition">{{ $product->name }}</h4>
                    <p class="text-[11px] text-gray-400 font-medium leading-relaxed mb-6">{{ Str::limit($product->description, 60) }}</p>

                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
                        <div class="leading-none">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-2 italic line-through">MRP ₹{{ number_format($product->selling_price, 2) }}</p>
                            <p class="text-2xl font-black text-indigo-900 italic tracking-tighter">₹{{ number_format($product->dealer_price, 2) }}</p>
                        </div>
                        <div class="text-right">
                             <p class="text-[10px] font-extrabold text-blue-500 uppercase tracking-widest mb-1">Margin Potential</p>
                             <p class="text-xs font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded inline-block">+ ₹{{ number_format($product->selling_price - $product->dealer_price, 2) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                         <div class="flex items-center bg-gray-50 border border-gray-100 rounded-2xl p-1 gap-2">
                            <button onclick="decrement({{ $product->id }})" class="w-8 h-8 rounded-xl bg-white text-gray-400 hover:text-indigo-600 hover:shadow-sm transition font-black">-</button>
                            <input id="qty-{{ $product->id }}" type="number" value="1" min="1" class="w-10 text-center bg-transparent text-sm font-black text-gray-800 border-0 focus:ring-0">
                            <button onclick="increment({{ $product->id }})" class="w-8 h-8 rounded-xl bg-white text-gray-400 hover:text-indigo-600 hover:shadow-sm transition font-black">+</button>
                         </div>
                         <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->dealer_price }})" class="flex-1 btn btn-primary py-3 rounded-2xl shadow-xl shadow-indigo-600/20 active:scale-95 transition-all text-xs font-black uppercase tracking-widest">
                            Procure Item
                         </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 card py-20 bg-white/5 border-dashed border-2 border-white/10 flex flex-col items-center justify-center text-center">
                 <div class="w-20 h-20 rounded-full bg-white/10 flex items-center justify-center text-indigo-400 mb-6 border border-white/5">
                    <i class="fas fa-store-slash text-3xl"></i>
                 </div>
                 <h4 class="text-xl font-black text-white italic tracking-tighter uppercase mb-2">Inventory Sync In Progress</h4>
                 <p class="text-gray-400 max-w-xs font-medium">Global catalog is currently being updated by Admin. Check back in a few minutes.</p>
            </div>
            @endforelse
        </div>
        
        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Modal Cart Checkout -->
<div id="checkout-modal" class="fixed inset-0 z-[100] hidden bg-gray-900/90 backdrop-blur-3xl p-6 flex items-center justify-center animate-in fade-in duration-500">
    <div class="w-full max-w-2xl bg-white rounded-[3rem] shadow-2xl p-12 overflow-hidden relative border-0">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-10 pb-6 border-b border-gray-100">
                <div>
                    <h3 class="text-3xl font-black italic text-gray-900 tracking-tighter uppercase leading-none mb-2">Purchase <span class="text-indigo-600">Review</span></h3>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Review your procurement manifest before submission.</p>
                </div>
                <button onclick="toggleModal()" class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all active:scale-90">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="cart-items-container" class="max-h-[40vh] overflow-y-auto mb-10 space-y-4 pr-2 custom-scrollbar">
                <!-- Items injected via JS -->
            </div>

            <div class="p-10 bg-gray-900 rounded-[2rem] text-white flex justify-between items-center shadow-2xl relative group">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 to-transparent"></div>
                <div class="relative">
                    <p class="text-xs font-bold text-indigo-300 uppercase tracking-widest mb-1">Total Procurement Cost</p>
                    <p class="text-5xl font-black italic tracking-tighter text-white">₹<span id="modal-total-amount">0.00</span></p>
                </div>
                <div class="relative text-right">
                    <form action="{{ route('dealer.orders.store') }}" method="POST" id="order-form">
                        @csrf
                        <div id="form-items-container"></div>
                        <button type="submit" class="btn btn-primary px-12 py-5 text-sm font-black italic uppercase tracking-widest rounded-2xl shadow-2xl shadow-indigo-600/40 hover:scale-105 active:scale-95 transition-all">
                            Submit Order Request <i class="fas fa-bolt ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];

    function increment(id) {
        document.getElementById(`qty-${id}`).value++;
    }

    function decrement(id) {
        let input = document.getElementById(`qty-${id}`);
        if(input.value > 1) input.value--;
    }

    function addToCart(id, name, price) {
        let qty = parseInt(document.getElementById(`qty-${id}`).value);
        let existing = cart.find(i => i.id === id);
        
        if(existing) {
            existing.quantity += qty;
        } else {
            cart.push({ id, name, price, quantity: qty });
        }
        
        updateCartState();
        alert(`Successfully added ${qty} units of ${name} to Procurement Hub.`);
    }

    function updateCartState() {
        document.getElementById('total-items').innerText = cart.reduce((acc, i) => acc + i.quantity, 0);
    }

    function toggleModal() {
        let modal = document.getElementById('checkout-modal');
        if(modal.classList.contains('hidden')) {
            renderCart();
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    function renderCart() {
        let container = document.getElementById('cart-items-container');
        let formContainer = document.getElementById('form-items-container');
        let totalSpan = document.getElementById('modal-total-amount');
        let total = 0;

        container.innerHTML = '';
        formContainer.innerHTML = '';

        cart.forEach((item, index) => {
            let subtotal = item.price * item.quantity;
            total += subtotal;

            container.innerHTML += `
                <div class="flex items-center justify-between p-6 bg-gray-50/50 rounded-3xl border border-gray-100 group">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-indigo-400 text-xl font-black">
                            ${index + 1}
                        </div>
                        <div>
                            <p class="text-lg font-black text-gray-900 tracking-tighter leading-none mb-1">${item.name}</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest italic">Quantity: ${item.quantity} units</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter mb-1">Subtotal Valuation</p>
                        <p class="text-lg font-black text-indigo-600 tracking-tighter italic">₹${subtotal.toLocaleString()}</p>
                    </div>
                </div>
            `;

            formContainer.innerHTML += `
                <input type="hidden" name="items[${index}][id]" value="${item.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            `;
        });

        totalSpan.innerText = total.toLocaleString();
    }

    document.getElementById('cart-counter').addEventListener('click', toggleModal);
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e5e7eb;
        border-radius: 10px;
    }
</style>
@endsection

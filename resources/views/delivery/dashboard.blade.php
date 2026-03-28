<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delivery Portal — Thambu Computers</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        body { background: #030712; color: #f1f5f9; }
        .glass { background: rgba(17,24,39,0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.07); }
        .qbtn { transition: all .15s ease; }
        .qbtn:active { transform: scale(.95); opacity:.8; }
        .bottom-nav { position: fixed; bottom:0; left:0; right:0; padding-bottom: env(safe-area-inset-bottom); }
        .leaflet-container { background: #0f172a; }
        .status-dot { width:8px; height:8px; border-radius:50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }
        .slide-up { animation: slideUp .3s ease-out; }
        @keyframes slideUp { from{transform:translateY(100%);opacity:0} to{transform:translateY(0);opacity:1} }
    </style>
</head>
<body>

{{-- ── TOP BAR ──────────────────────────────────────────────── --}}
<header class="sticky top-0 z-40 glass border-b border-white/5 px-4 py-3">
    <div class="flex items-center justify-between max-w-2xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-lg shadow-lg shadow-indigo-500/30">🛵</div>
            <div>
                <p class="text-sm font-bold text-white leading-none">TC Delivery</p>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">{{ auth()->user()->name }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button id="onlineBtn" onclick="toggleOnline()"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition qbtn
                       bg-emerald-500/15 border border-emerald-500/30 text-emerald-400">
                <span class="status-dot bg-emerald-400"></span> ONLINE
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 hover:text-red-400 text-xs transition qbtn">✕</button>
            </form>
        </div>
    </div>
</header>

{{-- ── FLASH ────────────────────────────────────────────────── --}}
@if(session('success') || session('error'))
<div class="max-w-2xl mx-auto px-4 pt-3">
    <div id="flash" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium
        {{ session('success') ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-300' : 'bg-red-500/10 border border-red-500/30 text-red-300' }}">
        {{ session('success') ?? session('error') }}
    </div>
</div>
@endif

{{-- ── STATS ─────────────────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 pt-4">
    <div class="grid grid-cols-3 gap-3 mb-5">
        <div class="glass rounded-2xl p-4 text-center">
            <p class="text-2xl font-black text-indigo-400">{{ $locations->count() }}</p>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Assigned</p>
        </div>
        <div class="glass rounded-2xl p-4 text-center">
            <p class="text-2xl font-black text-emerald-400">{{ $completedToday }}</p>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Delivered</p>
        </div>
        <div class="glass rounded-2xl p-4 text-center">
            <p class="text-2xl font-black text-amber-400">{{ $totalAssigned }}</p>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Total</p>
        </div>
    </div>
</div>

{{-- ── MINI MAP ─────────────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 mb-5">
    <div class="glass rounded-3xl overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-white/5">
            <div class="flex items-center gap-2">
                <span class="status-dot bg-indigo-400"></span>
                <p class="text-sm font-bold">Live Map</p>
            </div>
            <span id="gpsStatus" class="text-[10px] text-slate-400">📡 Initializing...</span>
        </div>
        <div id="deliveryMap" style="height: 220px;"></div>
    </div>
</div>

{{-- ── ACTIVE DELIVERIES ────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 pb-28 space-y-4">
    <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest">Active Deliveries ({{ $locations->count() }})</h2>

    @forelse($locations as $location)
    @php
        $customer = $location->customer;
        $statusColor = match($location->delivery_status) {
            'assigned'   => 'indigo',
            'picked_up'  => 'amber',
            'in_transit' => 'blue',
            'delivered'  => 'emerald',
            default      => 'slate',
        };
        $statusEmoji = match($location->delivery_status) {
            'assigned'   => '📋',
            'picked_up'  => '📦',
            'in_transit' => '🚀',
            'delivered'  => '✅',
            default      => '•',
        };
    @endphp

    <div class="glass rounded-3xl overflow-hidden">
        {{-- Header --}}
        <div class="px-5 pt-5 pb-4">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span class="font-mono text-xs font-black text-indigo-400 bg-indigo-500/10 px-2.5 py-1 rounded-lg">#{{ $location->order_id }}</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                            bg-{{ $statusColor }}-500/15 text-{{ $statusColor }}-400 border border-{{ $statusColor }}-500/25">
                            {{ $statusEmoji }} {{ ucwords(str_replace('_', ' ', $location->delivery_status)) }}
                        </span>
                    </div>
                    <p class="text-sm font-bold text-white">{{ $customer?->name ?? 'Customer' }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    @if($customer?->mobile)
                    <a href="tel:{{ $customer->mobile }}"
                       class="flex items-center gap-2 bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 px-3 py-2 rounded-xl text-[11px] font-bold qbtn">
                        📞 Call
                    </a>
                    @endif
                    @if($customer?->mobile)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->mobile) }}"
                       target="_blank"
                       class="flex items-center gap-2 bg-green-500/15 border border-green-500/30 text-green-400 px-3 py-2 rounded-xl text-[11px] font-bold qbtn">
                        💬 WhatsApp
                    </a>
                    @endif
                </div>
            </div>

            {{-- Address Card --}}
            <div class="bg-indigo-500/5 border border-indigo-500/15 rounded-2xl p-3 mb-4">
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Delivery Address</p>
                <p class="text-sm text-slate-200 leading-relaxed">{{ $location->customer_address ?? 'Address not yet confirmed by customer' }}</p>
                @if($location->customer_lat && $location->customer_lng)
                <a href="https://maps.google.com/?q={{ $location->customer_lat }},{{ $location->customer_lng }}"
                   target="_blank"
                   class="mt-2 inline-flex items-center gap-2 text-[11px] font-bold text-indigo-400 hover:text-indigo-300 transition qbtn">
                    🗺️ Open in Google Maps →
                </a>
                @endif
            </div>

            {{-- Quick Status Buttons --}}
            <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-2">Update Status</p>
            <div class="grid grid-cols-3 gap-2 mb-4">
                @php
                    $statusOptions = [
                        'picked_up'  => ['emoji' => '📦', 'label' => 'Picked Up',  'color' => 'amber'],
                        'in_transit' => ['emoji' => '🚀', 'label' => 'In Transit', 'color' => 'blue'],
                        'delivered'  => ['emoji' => '✅', 'label' => 'Delivered',  'color' => 'emerald'],
                    ];
                @endphp
                @foreach($statusOptions as $sVal => $sOpt)
                <form action="{{ route('delivery.status', $location->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $sVal }}">
                    <button type="submit"
                        class="w-full py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider transition qbtn
                        {{ $location->delivery_status === $sVal
                            ? 'bg-white/20 text-white shadow-lg border border-white/30'
                            : 'bg-white/5 text-slate-400 hover:bg-white/10 border border-white/8' }}">
                        {{ $sOpt['emoji'] }}<br>{{ $sOpt['label'] }}
                    </button>
                </form>
                @endforeach
            </div>


            {{-- OTP Section --}}
            <div class="bg-purple-500/5 border border-purple-500/20 rounded-2xl p-4 mb-4">
                <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest mb-3">🔐 Delivery OTP Confirmation</p>
                <div class="flex gap-2 mb-3">
                    <form action="{{ route('delivery.otp.send', $location->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full py-2.5 bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/30 text-purple-300 rounded-xl text-[11px] font-bold transition qbtn">
                            📱 Send OTP to Customer
                        </button>
                    </form>
                </div>
                <form action="{{ route('delivery.otp.verify', $location->id) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="otp" maxlength="6" placeholder="Enter OTP"
                        class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm font-mono text-white text-center tracking-[.3em] outline-none focus:border-purple-500/50 transition">
                    <button type="submit"
                        class="bg-purple-500 hover:bg-purple-600 text-white px-4 rounded-xl text-[11px] font-black transition qbtn shadow-lg shadow-purple-500/25">
                        VERIFY
                    </button>
                </form>
            </div>

            {{-- Proof Photo Upload --}}
            <form action="{{ route('delivery.status', $location->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="status" value="{{ $location->delivery_status }}">
                <label class="flex items-center justify-center gap-3 border-2 border-dashed border-white/15 rounded-2xl py-4 cursor-pointer hover:border-indigo-500/40 transition qbtn">
                    <span class="text-2xl">📸</span>
                    <div>
                        <p class="text-sm font-bold text-slate-200">Upload Proof Photo</p>
                        <p class="text-[11px] text-slate-400">Capture delivery confirmation</p>
                    </div>
                    <input type="file" name="proof_photo" accept="image/*" capture="environment" class="sr-only"
                           onchange="this.closest('form').submit()">
                </label>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-20">
        <div class="text-6xl mb-4">🎉</div>
        <p class="text-slate-300 font-bold text-lg">All Deliveries Done!</p>
        <p class="text-slate-500 text-sm mt-1">No active deliveries assigned.</p>
    </div>
    @endforelse
</div>

{{-- ── BOTTOM NAV ────────────────────────────────────────────── --}}
<nav class="bottom-nav glass border-t border-white/5">
    <div class="grid grid-cols-3 max-w-2xl mx-auto px-4 py-2">
        <button class="flex flex-col items-center gap-1 py-2 text-indigo-400">
            <span class="text-lg">📦</span>
            <span class="text-[9px] font-black uppercase tracking-widest">Deliveries</span>
        </button>
        <button class="flex flex-col items-center gap-1 py-2 text-slate-400 qbtn" onclick="document.getElementById('deliveryMap').scrollIntoView({behavior:'smooth'})">
            <div class="w-12 h-12 -mt-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-xl shadow-indigo-500/40">
                <span class="text-xl">🗺️</span>
            </div>
        </button>
        <button class="flex flex-col items-center gap-1 py-2 text-slate-400">
            <span class="text-lg">📊</span>
            <span class="text-[9px] font-black uppercase tracking-widest">History</span>
        </button>
    </div>
</nav>

<script>
// ── MAP SETUP ────────────────────────────────────────────────────
const map = L.map('deliveryMap').setView([11.0168, 76.9558], 13); // Coimbatore default
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap', maxZoom: 19
}).addTo(map);

// Customer destination markers
@foreach($activeDeliveries as $loc)
@if($loc->customer_lat && $loc->customer_lng)
L.marker([{{ $loc->customer_lat }}, {{ $loc->customer_lng }}], {
    icon: L.divIcon({
        html: `<div style="background:linear-gradient(135deg,#6366f1,#8b5cf6);width:34px;height:34px;border-radius:50%;border:3px solid white;box-shadow:0 4px 15px rgba(99,102,241,0.5);display:flex;align-items:center;justify-content:center;font-size:15px">📍</div>`,
        className: '', iconSize: [34, 34], iconAnchor: [17, 34]
    })
}).addTo(map).bindPopup('<b>Order #{{ $loc->order_id }}</b><br>{{ addslashes($loc->customer_address ?? "Destination") }}');
@endif
@endforeach

let myMarker = null;
let isOnline  = true;

// ── GPS TRACKING ─────────────────────────────────────────────────
function startGPS() {
    if (!navigator.geolocation) { document.getElementById('gpsStatus').textContent = '❌ GPS unavailable'; return; }
    navigator.geolocation.watchPosition(pos => {
        const { latitude: lat, longitude: lng } = pos.coords;
        document.getElementById('gpsStatus').textContent = `📍 ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
        if (!myMarker) {
            myMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    html: `<div style="background:linear-gradient(135deg,#6366f1,#8b5cf6);width:42px;height:42px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 4px 15px rgba(99,102,241,0.6);display:flex;align-items:center;justify-content:center"><span style="transform:rotate(45deg);display:block;font-size:18px;line-height:36px">🛵</span></div>`,
                    className: '', iconSize: [42, 42], iconAnchor: [21, 42]
                })
            }).addTo(map).bindPopup('<b>You</b>');
            map.setView([lat, lng], 14);
        } else {
            myMarker.setLatLng([lat, lng]);
        }
        if (isOnline) pushLocation(lat, lng);
    }, err => {
        document.getElementById('gpsStatus').textContent = '⚠ GPS denied';
    }, { enableHighAccuracy: true, maximumAge: 5000 });
}

function pushLocation(lat, lng) {
    fetch('/api/delivery/location/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ lat, lng })
    }).catch(() => {});
}

function toggleOnline() {
    isOnline = !isOnline;
    const btn = document.getElementById('onlineBtn');
    if (isOnline) {
        btn.className = btn.className.replace('slate-500/15 border-slate-500/30 text-slate-400', 'emerald-500/15 border border-emerald-500/30 text-emerald-400');
        btn.innerHTML = '<span class="status-dot bg-emerald-400"></span> ONLINE';
        startGPS();
    } else {
        btn.classList.replace('text-emerald-400', 'text-slate-400');
        btn.innerHTML = '<span class="w-2 h-2 rounded-full bg-slate-500 inline-block mr-1.5"></span> OFFLINE';
        fetch('/api/delivery/location/offline', {
            method:'POST',
            headers:{'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type':'application/json'}
        });
    }
}

// Auto-dismiss flash
setTimeout(() => { const f = document.getElementById('flash'); if (f) f.remove(); }, 4000);

// Start tracking on page load
window.onload = () => startGPS();
window.addEventListener('beforeunload', () => navigator.sendBeacon('/api/delivery/location/offline'));
</script>
</body>
</html>

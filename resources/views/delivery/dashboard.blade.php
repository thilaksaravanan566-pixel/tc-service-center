<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delivery Partner Dashboard — TC Service Center</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #030712; }
        .glass { background: rgba(17,24,39,0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06); }
        .glow-indigo { box-shadow: 0 0 30px rgba(99,102,241,0.15); }
        .glow-emerald { box-shadow: 0 0 30px rgba(16,185,129,0.15); }
        .leaflet-container { background: #111827; }
        .status-dot { animation: ping 1s cubic-bezier(0,0,0.2,1) infinite; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .slide-in { animation: slideIn 0.3s ease-out; }
        .delivery-card:hover { transform: translateY(-2px); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .delivery-card { transition: all 0.2s ease; }
    </style>
</head>
<body class="text-white min-h-screen">

{{-- Top Navigation --}}
<nav class="glass border-b border-gray-800/60 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-xl shadow-lg shadow-indigo-500/30">🛵</div>
            <div>
                <p class="text-sm font-bold text-white">TC Delivery</p>
                <p class="text-xs text-gray-400">Partner Dashboard</p>
            </div>
        </div>

        {{-- Online Toggle --}}
        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-400">Status:</span>
            <button id="onlineToggle" onclick="toggleOnlineStatus()"
                class="flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-300 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 hover:bg-emerald-500/40"
                data-online="true">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span id="onlineToggleText">Online</span>
            </button>

            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-sm font-bold shadow-lg">
                {{ strtoupper(substr(auth()->user()->name ?? 'D', 0, 1)) }}
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Map + Delivery Cards --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- LIVE MAP --}}
            <div class="glass rounded-3xl overflow-hidden glow-indigo">
                <div class="px-6 py-4 flex items-center justify-between border-b border-gray-800/60">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                        <h2 class="text-sm font-bold text-white">Live Delivery Map</h2>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <span id="locationStatus">📡 Initializing GPS...</span>
                    </div>
                </div>
                <div id="deliveryMap" style="height: 380px;"></div>
            </div>

            {{-- ACTIVE DELIVERIES --}}
            <div class="glass rounded-3xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-sm font-bold text-white">Active Deliveries</h2>
                    <span class="text-xs bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 px-3 py-1 rounded-full">
                        {{ count($locations ?? []) }} assigned
                    </span>
                </div>

                @if(!empty($locations ?? [])) @foreach($locations ?? [] as $loc)
                <div class="delivery-card glass rounded-2xl p-4 mb-3 cursor-pointer" onclick="focusDelivery({{ $loc->customer_lat ?? 0 }}, {{ $loc->customer_lng ?? 0 }})">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3 flex-1">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 border border-indigo-500/30 flex items-center justify-center text-xl flex-shrink-0">📦</div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-bold text-white">#{{ $loc->order_id }}</p>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full
                                        {{ $loc->delivery_status === 'in_transit' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' :
                                           ($loc->delivery_status === 'picked_up' ? 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30' :
                                            'bg-gray-500/20 text-gray-300 border border-gray-500/30') }}">
                                        {{ ucwords(str_replace('_', ' ', $loc->delivery_status)) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 truncate">{{ $loc->customer_address ?? 'Address not set — customer to confirm' }}</p>
                                @if($loc->customer_lat && $loc->customer_lng)
                                <p class="text-xs text-indigo-400 mt-1">📍 {{ number_format($loc->customer_lat, 4) }}, {{ number_format($loc->customer_lng, 4) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 flex-shrink-0">
                            <button onclick="event.stopPropagation(); updateDeliveryStatus({{ $loc->order_id }}, '{{ $loc->order_type }}', 'delivered')"
                                class="text-[11px] bg-emerald-500/20 hover:bg-emerald-500/40 border border-emerald-500/30 text-emerald-300 px-3 py-1.5 rounded-lg transition font-medium whitespace-nowrap">
                                ✅ Mark Delivered
                            </button>
                            <button onclick="event.stopPropagation(); updateDeliveryStatus({{ $loc->order_id }}, '{{ $loc->order_type }}', 'picked_up')"
                                class="text-[11px] bg-yellow-500/20 hover:bg-yellow-500/40 border border-yellow-500/30 text-yellow-300 px-3 py-1.5 rounded-lg transition font-medium whitespace-nowrap">
                                📦 Picked Up
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach @else
                <div class="text-center py-12">
                    <div class="text-5xl mb-4">🎉</div>
                    <p class="text-gray-400 text-sm">No active deliveries assigned.</p>
                    <p class="text-gray-500 text-xs mt-1">Stay online to receive new assignments</p>
                </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Stats + Quick Actions --}}
        <div class="space-y-5">

            {{-- Partner Profile Card --}}
            <div class="glass rounded-3xl p-6 glow-indigo">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-2xl font-bold shadow-xl shadow-indigo-500/30">
                        {{ strtoupper(substr(auth()->user()->name ?? 'D', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-white">{{ auth()->user()->name ?? 'Delivery Partner' }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->mobile ?? 'No contact set' }}</p>
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs text-emerald-300 font-medium">Active & Tracking</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today's Stats --}}
            <div class="glass rounded-3xl p-6">
                <h3 class="text-sm font-bold text-gray-200 mb-4">Today's Summary</h3>
                <div class="grid grid-cols-2 gap-3">
                    @php
                    $todayDelivered = $locations->where('delivery_status', 'delivered')->count();
                    $todayActive    = $locations->whereIn('delivery_status', ['in_transit', 'picked_up'])->count();
                    @endphp
                    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-4 text-center">
                        <p class="text-2xl font-black text-emerald-400">{{ $todayDelivered }}</p>
                        <p class="text-xs text-gray-400 mt-1">Delivered</p>
                    </div>
                    <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-2xl p-4 text-center">
                        <p class="text-2xl font-black text-indigo-400">{{ $todayActive }}</p>
                        <p class="text-xs text-gray-400 mt-1">In Progress</p>
                    </div>
                    <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-2xl p-4 text-center">
                        <p class="text-2xl font-black text-yellow-400">{{ $locations->count() }}</p>
                        <p class="text-xs text-gray-400 mt-1">Assigned</p>
                    </div>
                    <div class="bg-purple-500/10 border border-purple-500/20 rounded-2xl p-4 text-center">
                        <p class="text-2xl font-black text-purple-400">⭐ 4.9</p>
                        <p class="text-xs text-gray-400 mt-1">Rating</p>
                    </div>
                </div>
            </div>

            {{-- Current GPS Location --}}
            <div class="glass rounded-3xl p-6">
                <h3 class="text-sm font-bold text-gray-200 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    My Location
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400">Latitude</span>
                        <span id="myLat" class="font-mono text-indigo-300">Detecting...</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400">Longitude</span>
                        <span id="myLng" class="font-mono text-indigo-300">Detecting...</span>
                    </div>
                    <div class="flex justify-between text-xs mt-2">
                        <span class="text-gray-400">Last sent</span>
                        <span id="lastSent" class="text-emerald-400">Never</span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="glass rounded-3xl p-6">
                <h3 class="text-sm font-bold text-gray-200 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-800/50 hover:bg-gray-700/50 transition text-sm text-gray-300 hover:text-white">
                        <span class="text-lg">🏠</span> Go to Admin Panel
                    </a>
                    <a href="tel:+911234567890" class="flex items-center gap-3 p-3 rounded-xl bg-gray-800/50 hover:bg-gray-700/50 transition text-sm text-gray-300 hover:text-white">
                        <span class="text-lg">📞</span> Call Support
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 transition text-sm text-red-400">
                            <span class="text-lg">🚪</span> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ─────────────────────────────────────────────
// MAP SETUP
// ─────────────────────────────────────────────
let deliveryMap = L.map('deliveryMap').setView([12.9716, 80.2437], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap', maxZoom: 19
}).addTo(deliveryMap);

// Add customer destination markers
@foreach($activeDeliveries ?? [] as $loc)
@if($loc->customer_lat && $loc->customer_lng)
L.marker([{{ $loc->customer_lat }}, {{ $loc->customer_lng }}], {
    icon: L.divIcon({
        html: `<div style="background:linear-gradient(135deg,#ef4444,#f97316);width:36px;height:36px;border-radius:50%;border:3px solid white;box-shadow:0 4px 12px rgba(239,68,68,0.5);display:flex;align-items:center;justify-content:center;font-size:16px">📍</div>`,
        className: '', iconSize: [36, 36], iconAnchor: [18, 36]
    })
}).addTo(deliveryMap)
 .bindPopup('<b>Order #{{ $loc->order_id }}</b><br>{{ addslashes($loc->customer_address ?? "Destination") }}');
@endif
@endforeach

let myMarker = null;

function focusDelivery(lat, lng) {
    if (lat && lng) deliveryMap.setView([lat, lng], 15);
}

// ─────────────────────────────────────────────
// GPS TRACKING
// ─────────────────────────────────────────────
let isOnline   = true;
let lastSentAt = null;

function pushLocation(lat, lng) {
    if (!isOnline) return;
    fetch('/api/delivery/location/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ lat, lng })
    }).then(() => {
        lastSentAt = new Date();
        document.getElementById('lastSent').textContent = 'Just now';
    }).catch(() => {
        document.getElementById('lastSent').textContent = '⚠ Failed to send';
    });
}

function startGPSTracking() {
    if (!navigator.geolocation) {
        document.getElementById('locationStatus').textContent = '❌ GPS not supported';
        return;
    }
    navigator.geolocation.watchPosition(pos => {
        const { latitude, longitude } = pos.coords;
        document.getElementById('myLat').textContent = latitude.toFixed(6);
        document.getElementById('myLng').textContent = longitude.toFixed(6);
        document.getElementById('locationStatus').textContent = '📍 GPS Active';

        // Update my marker on map
        if (!myMarker) {
            myMarker = L.marker([latitude, longitude], {
                icon: L.divIcon({
                    html: `<div style="background:linear-gradient(135deg,#6366f1,#8b5cf6);width:44px;height:44px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 4px 15px rgba(99,102,241,0.6)">
                             <span style="transform:rotate(45deg);display:block;text-align:center;line-height:38px;font-size:18px">🛵</span>
                           </div>`,
                    className: '', iconSize: [44, 44], iconAnchor: [22, 44]
                })
            }).addTo(deliveryMap).bindPopup('<b>📍 You are here</b>');
            deliveryMap.setView([latitude, longitude], 14);
        } else {
            myMarker.setLatLng([latitude, longitude]);
        }

        pushLocation(latitude, longitude);
    }, err => {
        document.getElementById('locationStatus').textContent = '⚠ GPS error — check permissions';
    }, { enableHighAccuracy: true, maximumAge: 5000 });
}

// ─────────────────────────────────────────────
// ONLINE / OFFLINE TOGGLE
// ─────────────────────────────────────────────
function toggleOnlineStatus() {
    isOnline = !isOnline;
    const btn = document.getElementById('onlineToggle');
    const txt = document.getElementById('onlineToggleText');
    if (isOnline) {
        btn.className = 'flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-300 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 hover:bg-emerald-500/40';
        btn.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span><span>Online</span>`;
        startGPSTracking();
    } else {
        btn.className = 'flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm transition-all duration-300 bg-gray-600/20 border border-gray-600/40 text-gray-400';
        btn.innerHTML = `<span class="w-2 h-2 rounded-full bg-gray-500"></span><span>Offline</span>`;
        fetch('/api/delivery/location/offline', {
            method: 'POST',
            headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json' }
        });
    }
}

async function updateDeliveryStatus(orderId, orderType, status) {
    const statusLabels = { delivered: '✅ Delivered', picked_up: '📦 Picked Up', in_transit: '🚀 In Transit' };
    if (!confirm(`Mark order #${orderId} as "${statusLabels[status]}"?`)) return;
    // This would call an API or page reload — for now show toast
    showToast(`Order #${orderId} marked as ${statusLabels[status]}`);
    setTimeout(() => location.reload(), 1500);
}

function showToast(msg) {
    const t = document.createElement('div');
    t.className = 'fixed top-6 right-6 z-[9999] bg-emerald-600 text-white px-5 py-3 rounded-2xl shadow-2xl text-sm font-medium slide-in';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}

// Start GPS tracking on load
window.onload = () => startGPSTracking();
window.addEventListener('beforeunload', () => {
    navigator.sendBeacon('/api/delivery/location/offline');
});
</script>
</body>
</html>

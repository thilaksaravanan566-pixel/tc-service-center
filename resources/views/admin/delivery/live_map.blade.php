@extends('layouts.admin')

@section('title', 'Live Delivery Map — Admin')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-black text-white flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-lg shadow-lg shadow-indigo-500/30">🗺️</span>
                Live Delivery Control
            </h1>
            <p class="text-sm text-gray-400 mt-1">Real-time map of all active deliveries and online partners</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs text-emerald-300 font-medium">Auto-refreshing every 8s</span>
            </div>
            <button onclick="refreshMapData()" class="px-4 py-2 bg-indigo-600/20 hover:bg-indigo-600/40 border border-indigo-500/30 text-indigo-300 text-sm font-medium rounded-xl transition">
                🔄 Refresh Now
            </button>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
        $totalActive  = $activeDeliveries->count();
        $totalOnline  = $onlinePartners->count();
        $inTransit    = $activeDeliveries->where('delivery_status', 'in_transit')->count();
        $withLocation = $activeDeliveries->filter(fn($d) => $d->partner_lat)->count();
        @endphp
        <div class="bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-xl">🛵</div>
                <div>
                    <p class="text-2xl font-black text-indigo-400">{{ $totalActive }}</p>
                    <p class="text-xs text-gray-400">Active Deliveries</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-xl">🟢</div>
                <div>
                    <p class="text-2xl font-black text-emerald-400">{{ $totalOnline }}</p>
                    <p class="text-xs text-gray-400">Online Partners</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-yellow-500/20 border border-yellow-500/30 flex items-center justify-center text-xl">🚀</div>
                <div>
                    <p class="text-2xl font-black text-yellow-400">{{ $inTransit }}</p>
                    <p class="text-xs text-gray-400">In Transit</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-5 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-500/30 flex items-center justify-center text-xl">📍</div>
                <div>
                    <p class="text-2xl font-black text-purple-400">{{ $withLocation }}</p>
                    <p class="text-xs text-gray-400">GPS Tracked</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Layout: Map + Sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LIVE MAP --}}
        <div class="lg:col-span-2 bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-3xl overflow-hidden shadow-2xl">
            <div class="px-5 py-4 border-b border-gray-800/60 flex items-center justify-between">
                <h2 class="text-sm font-bold text-white">🗺 Live Tracking Map</h2>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-indigo-500 inline-block"></span> Partners</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span> Destinations</span>
                </div>
            </div>
            <div id="adminMap" style="height: 500px;"></div>
        </div>

        {{-- DELIVERY LIST SIDEBAR --}}
        <div class="space-y-4">

            {{-- Online Partners --}}
            <div class="bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-3xl p-5 shadow-xl">
                <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Online Partners ({{ $onlinePartners->count() }})
                </h3>
                @if(!empty($onlinePartners)) @foreach($onlinePartners as $partner)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-800/40 hover:bg-gray-700/40 transition cursor-pointer mb-2"
                     onclick="focusPartner({{ $partner->current_lat ?? 0 }}, {{ $partner->current_lng ?? 0 }})">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-sm font-bold shadow">
                        {{ strtoupper(substr($partner->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ $partner->name }}</p>
                        @if($partner->location_updated_at)
                        <p class="text-xs text-gray-400">Updated {{ $partner->location_updated_at->diffForHumans() }}</p>
                        @endif
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse flex-shrink-0"></span>
                </div>
                @endforeach @else
                <p class="text-xs text-gray-500 text-center py-4">No partners online</p>
                @endif
            </div>

            {{-- Active Deliveries List --}}
            <div class="bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-3xl p-5 shadow-xl max-h-80 overflow-y-auto">
                <h3 class="text-sm font-bold text-white mb-4">Active Deliveries</h3>
                @if(!empty($activeDeliveries)) @foreach($activeDeliveries as $del)
                <div class="p-3 rounded-xl bg-gray-800/40 hover:bg-gray-700/40 transition mb-2 cursor-pointer"
                     onclick="@if($del->customer_lat) focusDelivery({{ $del->customer_lat }}, {{ $del->customer_lng }}) @endif">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-xs font-bold text-white">#{{ $del->order_id }}</p>
                        <span class="text-[10px] px-2 py-0.5 rounded-full
                            {{ $del->delivery_status === 'in_transit' ? 'bg-indigo-500/20 text-indigo-300' : 'bg-yellow-500/20 text-yellow-300' }}">
                            {{ ucwords(str_replace('_', ' ', $del->delivery_status)) }}
                        </span>
                    </div>
                    @if($del->customer_address)
                    <p class="text-xs text-gray-400 truncate">📍 {{ $del->customer_address }}</p>
                    @endif
                    @if($del->deliveryPartner)
                    <p class="text-xs text-indigo-400 mt-1">🛵 {{ $del->deliveryPartner->name }}</p>
                    @endif
                </div>
                @endforeach @else
                <p class="text-xs text-gray-500 text-center py-6">No active deliveries</p>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .leaflet-container { background: #111827; }
</style>

<script>
let adminMap = L.map('adminMap').setView([12.9716, 80.2437], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap', maxZoom: 19
}).addTo(adminMap);

const partnerMarkers   = {};
const deliveryMarkers  = {};

const partnerIcon = (name) => L.divIcon({
    html: `<div style="background:linear-gradient(135deg,#6366f1,#8b5cf6);width:40px;height:40px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 4px 15px rgba(99,102,241,0.5)">
             <span style="transform:rotate(45deg);display:block;text-align:center;line-height:34px;font-size:14px">🛵</span>
           </div>`,
    className: '', iconSize: [40, 40], iconAnchor: [20, 40]
});

const destIcon = L.divIcon({
    html: `<div style="background:linear-gradient(135deg,#ef4444,#f97316);width:36px;height:36px;border-radius:50%;border:3px solid white;box-shadow:0 4px 12px rgba(239,68,68,0.5);display:flex;align-items:center;justify-content:center;font-size:16px">📍</div>`,
    className: '', iconSize: [36, 36], iconAnchor: [18, 36]
});

async function refreshMapData() {
    try {
        const res  = await fetch('/api/admin/delivery/map-data');
        const data = await res.json();

        // Update partner markers
        data.partners.forEach(p => {
            if (!p.current_lat || !p.current_lng) return;
            if (partnerMarkers[p.id]) {
                partnerMarkers[p.id].setLatLng([p.current_lat, p.current_lng]);
            } else {
                partnerMarkers[p.id] = L.marker([p.current_lat, p.current_lng], { icon: partnerIcon(p.name) })
                    .addTo(adminMap)
                    .bindPopup(`<b>🛵 ${p.name}</b><br>Live Partner`);
            }
        });

        // Update delivery destination markers
        data.deliveries.forEach(d => {
            if (!d.customer_lat || !d.customer_lng) return;
            if (!deliveryMarkers[d.id]) {
                deliveryMarkers[d.id] = L.marker([d.customer_lat, d.customer_lng], { icon: destIcon })
                    .addTo(adminMap)
                    .bindPopup(`<b>Order #${d.order_id}</b><br>${d.customer_address || 'Destination'}<br><small>Partner: ${d.partner_name || '—'}</small>`);
            }
        });

    } catch(e) {
        console.error('Admin map refresh failed:', e);
    }
}

function focusPartner(lat, lng) {
    if (lat && lng) adminMap.setView([lat, lng], 15);
}
function focusDelivery(lat, lng) {
    if (lat && lng) adminMap.setView([lat, lng], 15);
}

// Initial load + auto-refresh
refreshMapData();
setInterval(refreshMapData, 8000);

// Pre-render existing markers from server data
@foreach($onlinePartners as $p)
@if($p->current_lat && $p->current_lng)
L.marker([{{ $p->current_lat }}, {{ $p->current_lng }}], { icon: partnerIcon('{{ $p->name }}') })
 .addTo(adminMap)
 .bindPopup('<b>🛵 {{ $p->name }}</b><br>Online Partner');
@endif
@endforeach

@foreach($activeDeliveries as $d)
@if($d->customer_lat && $d->customer_lng)
L.marker([{{ $d->customer_lat }}, {{ $d->customer_lng }}], { icon: destIcon })
 .addTo(adminMap)
 .bindPopup('<b>Order #{{ $d->order_id }}</b><br>{{ addslashes($d->customer_address ?? "Destination") }}');
@endif
@endforeach
</script>
@endsection

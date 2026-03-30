@extends('layouts.dealer')

@section('content')
<div class="animate-slide-up max-w-6xl mx-auto pb-24">

    <!-- Header Matrix -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 px-4">
        <div class="flex items-center gap-8">
            <div class="w-20 h-20 rounded-3xl bg-slate-950 border border-white/5 flex items-center justify-center text-indigo-400 shadow-2xl relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <svg class="w-10 h-10 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">Real-time Logistics</h1>
                <p class="text-slate-500 font-medium mt-2 leading-relaxed">Interfacing with Order <span class="text-indigo-400">#ORD-{{ str_pad($orderId, 5, '0', STR_PAD_LEFT) }}</span> · {{ ucfirst($orderType) }} node synchronization active.</p>
            </div>
        </div>
        <div id="statusBadge" class="flex items-center gap-4 px-8 py-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 shadow-2xl">
            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse shadow-[0_0_10px_rgba(99,102,241,0.5)]"></span>
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-300">Synchronizing...</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 px-4">
        
        {{-- Map Interface Matrix --}}
        <div class="lg:col-span-8 space-y-10">
            <div class="super-card p-3 overflow-hidden aspect-video lg:aspect-auto lg:h-[600px] relative group rounded-[3rem] bg-slate-950 border-white/5 shadow-2xl">
                <div id="trackingMap" class="w-full h-full rounded-[2.5rem] z-0 grayscale-[0.8] hover:grayscale-0 transition-all duration-700"></div>

                {{-- Live Node Indicator --}}
                <div id="liveIndicator" class="absolute top-10 right-10 flex items-center gap-4 bg-slate-950/90 backdrop-blur-3xl rounded-2xl px-6 py-3 border border-indigo-500/20 hidden z-10 shadow-2xl">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span>
                    <span class="text-[10px] text-emerald-400 font-black uppercase tracking-[0.3em]">Live Stream Active</span>
                </div>

                {{-- Refresh sequence --}}
                <div class="absolute bottom-10 right-10 bg-slate-950/90 backdrop-blur-3xl rounded-2xl px-6 py-3 border border-white/10 text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 z-10 shadow-2xl">
                    Re-Sync in <span id="countdown" class="text-white">5</span>s
                </div>
            </div>

            {{-- Fleet Agent Card --}}
            <div id="partnerCard" class="super-card p-10 group hidden rounded-[2.5rem] bg-indigo-500/[0.03] border-indigo-500/20">
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] mb-8 flex items-center gap-4"><span class="w-10 h-px bg-indigo-500/30"></span> Assigned Fleet Agent</h3>
                <div class="flex items-center gap-8">
                    <div class="w-20 h-20 rounded-3xl bg-slate-950 flex items-center justify-center text-3xl shadow-inner border border-white/5 relative overflow-hidden">
                        <div class="absolute inset-0 bg-indigo-500/5 group-hover:bg-indigo-500/10 transition-colors"></div>
                        🛵
                    </div>
                    <div class="flex-1">
                        <p id="partnerName" class="text-2xl font-black text-white tracking-tight">—</p>
                        <p id="partnerPhone" class="text-sm text-slate-500 mt-2 font-black uppercase tracking-[0.2em]">—</p>
                        <p id="lastUpdated" class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.3em] mt-3 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            —
                        </p>
                    </div>
                    <a id="partnerCallBtn" href="#" class="w-16 h-16 bg-emerald-500 text-white rounded-[1.5rem] flex items-center justify-center transition-all shadow-2xl shadow-emerald-500/30 hover:bg-emerald-400 group-hover:scale-105 hidden">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Sidebar Pipeline --}}
        <div class="lg:col-span-4 space-y-10">
            {{-- Logistics Pipeline --}}
            <div class="super-card p-10 rounded-[2.5rem] bg-slate-950/40 backdrop-blur-3xl border-white/5">
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] mb-12 flex items-center gap-4">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]"></span>
                    Fulfillment Pipeline
                </h3>
                <div class="relative ml-4">
                    {{-- Timeline Vector --}}
                    <div class="absolute left-[19px] top-4 bottom-4 w-1 bg-white/[0.03] rounded-full"></div>

                    @php
                    $steps = [
                        ['status' => 'pending',   'icon' => '📦', 'label' => 'Order Received',     'desc' => 'Entry recorded in hub system'],
                        ['status' => 'assigned',  'icon' => '🛵', 'label' => 'Agent Designated',  'desc' => 'Fleet courier assigned to node'],
                        ['status' => 'picked_up', 'icon' => '🏪', 'label' => 'Entity Extraction',   'desc' => 'Package has exited origin hub'],
                        ['status' => 'in_transit','icon' => '🚀', 'label' => 'Transference Phase',  'desc' => 'Active orbital movement to target'],
                        ['status' => 'delivered', 'icon' => '✅', 'label' => 'Final Validation', 'desc' => 'Nodal handoff confirmed/verified'],
                    ];
                    $currentStatus = $location?->delivery_status ?? 'pending';
                    $statusOrder = ['pending','assigned','picked_up','in_transit','delivered'];
                    $currentIdx = array_search($currentStatus, $statusOrder);
                    @endphp

                    <div class="space-y-12 pl-12">
                        @foreach($steps as $idx => $step)
                        @php
                            $stepIdx = array_search($step['status'], $statusOrder);
                            $done    = $stepIdx < $currentIdx;
                            $active  = $stepIdx === $currentIdx;
                        @endphp
                        <div class="flex items-start gap-6 relative group/step" data-step="{{ $step['status'] }}">
                            <div class="absolute -left-[45px] w-10 h-10 rounded-2xl flex items-center justify-center text-lg border-2 transition-all duration-700 z-10
                                {{ $done   ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.2)]' :
                                   ($active ? 'bg-indigo-500 border-indigo-400 text-white shadow-[0_0_40px_rgba(99,102,241,0.5)] scale-110' :
                                              'bg-slate-950 border-white/5 text-slate-800') }}">
                                {{ $step['icon'] }}
                            </div>
                            <div class="pt-1">
                                <p class="text-[11px] font-black uppercase tracking-[0.2em] {{ $active ? 'text-white' : ($done ? 'text-slate-300' : 'text-slate-700') }} transition-colors">{{ $step['label'] }}</p>
                                <p class="text-[10px] mt-2 font-black uppercase tracking-[0.1em] {{ $active ? 'text-indigo-400' : 'text-slate-600' }} leading-relaxed">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Target Node Coordinates --}}
            @if($location?->customer_address)
            <div class="super-card p-10 rounded-[2.5rem] bg-slate-950 border-white/5 shadow-2xl relative overflow-hidden group/addr">
                <div class="absolute inset-0 bg-rose-500/[0.01] pointer-events-none group-hover/addr:bg-rose-500/[0.03] transition-colors"></div>
                <div class="flex items-start gap-6 relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-2xl shadow-2xl group-hover/addr:rotate-6 transition-all">📍</div>
                    <div class="flex-1">
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.4em] mb-3">Target Node (Destination)</p>
                        <p class="text-sm font-black text-white leading-relaxed tracking-tight">{{ $location->customer_address }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Leaflet Protocol --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .leaflet-container { background: #020617; }
    .leaflet-tile { filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%); }
    .custom-partner-icon { filter: drop-shadow(0 0 15px rgba(99,102,241,0.8)); }
    .custom-customer-icon { filter: drop-shadow(0 0 15px rgba(239,68,68,0.8)); }
</style>

<script>
const ORDER_ID   = {{ $orderId }};
const ORDER_TYPE = '{{ $orderType }}';
const POLL_INTERVAL = 5000;

let map = L.map('trackingMap', { zoomControl: false }).setView([12.9716, 80.2437], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OS',
    maxZoom: 19
}).addTo(map);

const partnerIcon = L.divIcon({
    html: `<div style="width:48px;height:48px;background:linear-gradient(135deg,#6366f1,#7c3aed);border-radius:24px 24px 24px 0;transform:rotate(-45deg);border:4px solid #fff;box-shadow:0 10px 30px rgba(99,102,241,0.6)">
             <span style="transform:rotate(45deg);display:block;text-align:center;line-height:40px;font-size:22px">🛵</span>
           </div>`,
    className: '', iconSize: [48, 48], iconAnchor: [24, 48]
});
const customerIcon = L.divIcon({
    html: `<div style="width:44px;height:44px;background:linear-gradient(135deg,#f43f5e,#fb7185);border-radius:22px;border:4px solid #fff;box-shadow:0 10px 30px rgba(244,63,94,0.6);display:flex;align-items:center;justify-content:center;font-size:20px">📍</div>`,
    className: '', iconSize: [44, 44], iconAnchor: [22, 22]
});

let partnerMarker = null;
let customerMarker = null;
let routeLine = null;
let countdown = 5;
let countdownEl = document.getElementById('countdown');

function drawRoute(fromLat, fromLng, toLat, toLng) {
    if (routeLine) map.removeLayer(routeLine);
    routeLine = L.polyline([[fromLat, fromLng], [toLat, toLng]], {
        color: '#6366f1', weight: 4, opacity: 0.8,
        dashArray: '1, 12', lineCap: 'round', animate: true
    }).addTo(map);
}

function updateStatusBadge(status) {
    const badge = document.getElementById('statusBadge');
    const statusMap = {
        'pending':    ['Phase: Pending',       'bg-slate-500/10 border-slate-500/20 text-slate-400'],
        'assigned':   ['Node: Assigned',      'bg-blue-500/10 border-blue-500/20 text-blue-400'],
        'picked_up':  ['Signal: Picked Up',   'bg-amber-500/10 border-amber-500/20 text-amber-400'],
        'in_transit': ['Vector: In Transit',  'bg-indigo-500/10 border-indigo-500/20 text-indigo-400'],
        'delivered':  ['Registry: Delivered', 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400'],
    };
    const [label, cls] = statusMap[status] || ['Unknown','bg-slate-500/10 border-slate-500/20 text-slate-400'];
    badge.className = `flex items-center gap-4 px-8 py-4 rounded-2xl ${cls} shadow-2xl`;
    badge.innerHTML = `<span class="w-2 h-2 rounded-full ${status === 'delivered' ? 'bg-emerald-400' : 'bg-indigo-400 animate-pulse underline shadow-[0_0_10px_rgba(99,102,241,0.5)]'}"></span><span class="text-[10px] font-black uppercase tracking-[0.3em]">${label}</span>`;
}

async function pollLocation() {
    try {
        const res = await fetch(`/api/tracking/${ORDER_ID}/status?type=${ORDER_TYPE}`);
        const data = await res.json();
        if (!data.found) return;

        document.getElementById('liveIndicator').classList.remove('hidden');
        updateStatusBadge(data.delivery_status);

        if (data.partner_lat && data.partner_lng) {
            if (!partnerMarker) {
                partnerMarker = L.marker([data.partner_lat, data.partner_lng], {icon: partnerIcon})
                    .addTo(map).bindPopup(`<b style="font-weight:900;text-transform:uppercase;letter-spacing:1px">🛵 ${data.partner_name || 'Fleet Agent'}</b>`);
            } else {
                partnerMarker.setLatLng([data.partner_lat, data.partner_lng]);
            }
        }

        if (data.customer_lat && data.customer_lng) {
            if (!customerMarker) {
                customerMarker = L.marker([data.customer_lat, data.customer_lng], {icon: customerIcon})
                    .addTo(map).bindPopup(`<b style="font-weight:900;text-transform:uppercase;letter-spacing:1px">📍 Target Node</b>`);
            }
        }

        if (data.partner_lat && data.customer_lat) {
            drawRoute(data.partner_lat, data.partner_lng, data.customer_lat, data.customer_lng);
            map.fitBounds([[data.partner_lat, data.partner_lng], [data.customer_lat, data.customer_lng]], { padding: [100, 100], animate: true });
        } else if (data.customer_lat) {
            map.setView([data.customer_lat, data.customer_lng], 15);
        }

        if (data.partner_name) {
            document.getElementById('partnerCard').classList.remove('hidden');
            document.getElementById('partnerName').textContent = data.partner_name;
            document.getElementById('partnerPhone').textContent = data.partner_phone || 'COMMS SECURE';
            document.getElementById('lastUpdated').textContent = data.last_updated ? `Neural Update: ${data.last_updated}` : '';
            if (data.partner_phone) {
                const callBtn = document.getElementById('partnerCallBtn');
                callBtn.href = `tel:${data.partner_phone}`;
                callBtn.classList.remove('hidden');
            }
        }
    } catch (e) { console.error('Poll Aborted:', e); }
}

function startCountdown() {
    countdown = 5;
    const timer = setInterval(() => {
        countdown--;
        countdownEl.textContent = countdown;
        if (countdown <= 0) {
            clearInterval(timer);
            pollLocation().then(() => startCountdown());
        }
    }, 1000);
}

pollLocation();
startCountdown();
</script>
@endsection

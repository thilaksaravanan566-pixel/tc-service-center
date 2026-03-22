{{--
    REUSABLE LOCATION PICKER COMPONENT
    Usage: include('customer.partials.location-picker', ['orderId' => $id, 'orderType' => 'product'])
    Adds a floating button + modal for the customer to pick their delivery location.
--}}

{{-- Floating Location Button --}}
<button
    id="openLocationPicker"
    onclick="openLocationModal()"
    class="fixed bottom-6 right-6 z-50 flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold px-5 py-3 rounded-2xl shadow-2xl shadow-indigo-500/40 hover:shadow-indigo-500/60 transition-all duration-300 hover:scale-105 group">
    <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    <span>Set Delivery Location</span>
</button>

{{-- Location Picker Modal --}}
<div id="locationModal"
    class="fixed inset-0 z-[999] flex items-end sm:items-center justify-center hidden"
    style="background: rgba(0,0,0,0.75); backdrop-filter: blur(8px);">

    <div class="w-full sm:max-w-2xl bg-gray-900 rounded-t-3xl sm:rounded-3xl border border-gray-700/60 shadow-2xl overflow-hidden animate-slideUp">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-800">
            <div>
                <h2 class="text-lg font-bold text-white">📍 Set Delivery Location</h2>
                <p class="text-xs text-gray-400 mt-0.5">Drag the pin or enter your address below</p>
            </div>
            <button onclick="closeLocationModal()" class="p-2 rounded-xl bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- GPS Button --}}
        <div class="px-6 pt-5 pb-3">
            <button onclick="detectGPS()" id="gpsBtn"
                class="w-full flex items-center justify-center gap-3 bg-indigo-600/20 hover:bg-indigo-600/40 border border-indigo-500/40 text-indigo-300 font-semibold px-4 py-3 rounded-2xl transition-all duration-300 hover:scale-[1.02]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="gpsBtnText">📡 Detect My Current Location (GPS)</span>
            </button>

            <div class="flex items-center gap-3 my-4">
                <div class="flex-1 h-px bg-gray-700"></div>
                <span class="text-xs text-gray-500">or drag pin on map</span>
                <div class="flex-1 h-px bg-gray-700"></div>
            </div>
        </div>

        {{-- Map --}}
        <div id="pickerMap" style="height: 280px; width: 100%;"></div>

        {{-- Address Input + Save --}}
        <div class="px-6 py-5 space-y-4">
            <div>
                <label class="text-xs text-gray-400 font-medium mb-1 block">Full Address</label>
                <textarea id="pickerAddress" rows="2"
                    placeholder="e.g. Plot 12, Annanagar East, Chennai - 600040"
                    class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-3 text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 resize-none transition-all"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs text-gray-400">
                <div class="bg-gray-800/50 rounded-xl px-3 py-2">
                    <span class="block text-gray-500">Latitude</span>
                    <span id="displayLat" class="font-mono text-indigo-300">—</span>
                </div>
                <div class="bg-gray-800/50 rounded-xl px-3 py-2">
                    <span class="block text-gray-500">Longitude</span>
                    <span id="displayLng" class="font-mono text-indigo-300">—</span>
                </div>
            </div>

            <button onclick="saveLocation()"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:scale-[1.02]">
                ✅ Confirm & Save Location
            </button>
        </div>
    </div>
</div>

{{-- Leaflet.js (loaded once) --}}
@once
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endonce

<style>
@keyframes slideUp {
    from { transform: translateY(100px); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
}
.animate-slideUp { animation: slideUp 0.3s ease-out; }
</style>

<script>
const PICKER_ORDER_ID   = {{ $orderId ?? 0 }};
const PICKER_ORDER_TYPE = '{{ $orderType ?? "product" }}';
let pickerMap = null;
let pickerMarker = null;
let selectedLat = null;
let selectedLng = null;

function openLocationModal() {
    document.getElementById('locationModal').classList.remove('hidden');
    // Initialize map lazily
    if (!pickerMap) {
        setTimeout(() => {
            const defaultLat = 12.9716, defaultLng = 80.2437;
            pickerMap = L.map('pickerMap').setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19, attribution: '© OpenStreetMap'
            }).addTo(pickerMap);

            // Draggable marker
            pickerMarker = L.marker([defaultLat, defaultLng], { draggable: true })
                .addTo(pickerMap)
                .bindPopup('📍 Drag me to your location!').openPopup();

            pickerMarker.on('dragend', e => {
                const pos = e.target.getLatLng();
                updateCoords(pos.lat, pos.lng);
                reverseGeocode(pos.lat, pos.lng);
            });

            // Click to move marker
            pickerMap.on('click', e => {
                pickerMarker.setLatLng(e.latlng);
                updateCoords(e.latlng.lat, e.latlng.lng);
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });
        }, 100);
    } else {
        pickerMap.invalidateSize();
    }
}

function closeLocationModal() {
    document.getElementById('locationModal').classList.add('hidden');
}

function updateCoords(lat, lng) {
    selectedLat = lat;
    selectedLng = lng;
    document.getElementById('displayLat').textContent = lat.toFixed(6);
    document.getElementById('displayLng').textContent = lng.toFixed(6);
}

async function reverseGeocode(lat, lng) {
    try {
        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
        const data = await res.json();
        if (data.display_name) {
            document.getElementById('pickerAddress').value = data.display_name;
        }
    } catch(e) { /* silently fail */ }
}

function detectGPS() {
    const btn = document.getElementById('gpsBtn');
    const txt = document.getElementById('gpsBtnText');
    if (!navigator.geolocation) {
        txt.textContent = '❌ GPS not supported by your browser';
        return;
    }
    txt.textContent = '🔄 Detecting location...';
    btn.disabled = true;
    navigator.geolocation.getCurrentPosition(pos => {
        const { latitude, longitude } = pos.coords;
        updateCoords(latitude, longitude);
        if (pickerMap) {
            pickerMap.setView([latitude, longitude], 16);
            pickerMarker.setLatLng([latitude, longitude]);
        }
        reverseGeocode(latitude, longitude);
        txt.textContent = '✅ Location detected!';
        setTimeout(() => { txt.textContent = '📡 Detect My Current Location (GPS)'; btn.disabled = false; }, 2000);
    }, err => {
        txt.textContent = '❌ Could not detect location. Drag pin manually.';
        btn.disabled = false;
        setTimeout(() => txt.textContent = '📡 Detect My Current Location (GPS)', 3000);
    }, { enableHighAccuracy: true, timeout: 10000 });
}

async function saveLocation() {
    if (!selectedLat || !selectedLng) {
        alert('Please select a location on the map first.');
        return;
    }
    const address = document.getElementById('pickerAddress').value;
    try {
        const res = await fetch('/customer/location/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                order_type: PICKER_ORDER_TYPE,
                order_id:   PICKER_ORDER_ID,
                lat:        selectedLat,
                lng:        selectedLng,
                address:    address
            })
        });
        const data = await res.json();
        if (data.success) {
            closeLocationModal();
            // Show success toast
            showToast('📍 Delivery location saved successfully!', 'success');
        }
    } catch(e) {
        showToast('Failed to save location. Please try again.', 'error');
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const color = type === 'success' ? 'bg-emerald-600' : 'bg-red-600';
    toast.className = `fixed top-6 right-6 z-[9999] px-5 py-3 ${color} text-white text-sm font-medium rounded-2xl shadow-2xl transition-all duration-500`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Technician Portal — Thambu Computers</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; -webkit-tap-highlight-color: transparent; }
        :root {
            --bg: #0f172a;
            --surface: #1e293b;
            --surface2: #263148;
            --border: rgba(255,255,255,0.07);
            --accent: #f43f5e;
            --accent2: #fb923c;
            --text: #f1f5f9;
            --muted: #64748b;
        }
        body { background: var(--bg); color: var(--text); min-height: 100vh; }

        /* Mobile bottom nav */
        .bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; background: var(--surface); border-top: 1px solid var(--border); z-index: 50; padding-bottom: env(safe-area-inset-bottom); }

        /* Status badges */
        .badge-received    { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
        .badge-diagnosing  { background: rgba(234,179,8,0.15);  color: #facc15; border: 1px solid rgba(234,179,8,0.3); }
        .badge-repairing   { background: rgba(249,115,22,0.15); color: #fb923c; border: 1px solid rgba(249,115,22,0.3); }
        .badge-ready       { background: rgba(34,197,94,0.15);  color: #4ade80; border: 1px solid rgba(34,197,94,0.3); }
        .badge-completed   { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
        .badge-packing     { background: rgba(20,184,166,0.15); color: #2dd4bf; border: 1px solid rgba(20,184,166,0.3); }

        /* Priority */
        .priority-high   { color: #f43f5e; }
        .priority-medium { color: #fb923c; }
        .priority-low    { color: #4ade80; }

        /* Quick-action button */
        .qbtn { transition: all .15s ease; }
        .qbtn:active { transform: scale(.95); opacity:.8; }

        /* Card hover glow */
        .job-card { transition: box-shadow .2s; }
        .job-card:hover { box-shadow: 0 0 0 1px rgba(244,63,94,.3), 0 8px 24px rgba(0,0,0,.4); }

        /* Pulse for assigned badge */
        .pulse { animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.6} }

        /* Scan animation */
        @keyframes scanline { 0%{top:0} 100%{top:100%} }
    </style>
</head>
<body>

{{-- ── TOP BAR ──────────────────────────────────────────────── --}}
<header class="sticky top-0 z-40 bg-[#0f172a]/95 backdrop-blur border-b border-white/5 px-4 py-3">
    <div class="flex items-center justify-between max-w-2xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-rose-500 to-orange-500 flex items-center justify-center text-lg shadow-lg shadow-rose-500/30">🔧</div>
            <div>
                <p class="text-sm font-bold text-white leading-none">TC Technician</p>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">{{ auth()->user()->name }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-[10px] font-bold text-emerald-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 pulse"></span>ON DUTY
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-9 h-9 rounded-xl bg-white/5 hover:bg-red-500/20 border border-white/10 flex items-center justify-center text-slate-400 hover:text-red-400 transition qbtn">
                    <i class="fas fa-sign-out-alt text-xs"></i>
                </button>
            </form>
        </div>
    </div>
</header>

{{-- ── FLASH MESSAGES ─────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 pt-4">
@if(session('success'))
    <div id="flash" class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-2xl text-sm font-medium mb-4">
        <i class="fas fa-check-circle text-emerald-400"></i> {{ session('success') }}
    </div>
@elseif(session('error'))
    <div id="flash" class="flex items-center gap-3 bg-red-500/10 border border-red-500/30 text-red-300 px-4 py-3 rounded-2xl text-sm font-medium mb-4">
        <i class="fas fa-exclamation-circle text-red-400"></i> {{ session('error') }}
    </div>
@endif
</div>

{{-- ── STATS ROW ────────────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 pt-2">
    <div class="grid grid-cols-3 gap-3 mb-5">
        <div class="bg-[#1e293b] rounded-2xl p-4 text-center border border-white/5">
            <p class="text-2xl font-black text-rose-400">{{ $orders->whereIn('status',['received','diagnosing','repairing'])->count() }}</p>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Active</p>
        </div>
        <div class="bg-[#1e293b] rounded-2xl p-4 text-center border border-white/5">
            <p class="text-2xl font-black text-amber-400">{{ $orders->where('priority','high')->count() }}</p>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Urgent</p>
        </div>
        <div class="bg-[#1e293b] rounded-2xl p-4 text-center border border-white/5">
            <p class="text-2xl font-black text-emerald-400">{{ $orders->where('status','ready')->count() }}</p>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest">Ready</p>
        </div>
    </div>
</div>

{{-- ── SEARCH BAR ─────────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 mb-4">
    <div class="relative">
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
        <input type="text" id="searchInput" oninput="filterJobs(this.value)"
            placeholder="Search job ID, customer, device..."
            class="w-full bg-[#1e293b] border border-white/8 rounded-2xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-rose-500/50 transition">
    </div>
</div>

{{-- ── JOB CARDS ────────────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 pb-28 space-y-4" id="jobList">

    @forelse($orders as $order)
    @php
        $customer = $order->device?->customer ?? $order->customer;
        $device   = $order->device;
        $badgeClass = match($order->status) {
            'received'  => 'badge-received',
            'diagnosing'=> 'badge-diagnosing',
            'repairing' => 'badge-repairing',
            'ready'     => 'badge-ready',
            'packing'   => 'badge-packing',
            'completed' => 'badge-completed',
            default     => 'badge-received',
        };
        $priorityClass = match($order->priority ?? 'medium') {
            'high'   => 'priority-high',
            'medium' => 'priority-medium',
            default  => 'priority-low',
        };
    @endphp
    <div class="job-card bg-[#1e293b] rounded-3xl border border-white/5 overflow-hidden"
         data-search="{{ strtolower($order->tc_job_id . ' ' . ($customer?->name ?? '') . ' ' . ($device?->brand ?? '') . ' ' . ($device?->model ?? '')) }}">

        {{-- Card Header --}}
        <div class="px-5 pt-5 pb-4">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-mono text-xs font-black text-rose-400 bg-rose-500/10 px-2.5 py-1 rounded-lg">{{ $order->tc_job_id }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-lg {{ $badgeClass }}">{{ $order->status }}</span>
                        @if(($order->priority ?? '') === 'high')
                            <span class="text-[10px] font-black text-red-400 animate-pulse">🔴 URGENT</span>
                        @endif
                    </div>
                    <p class="text-base font-bold text-white mt-2 leading-tight">
                        {{ $device?->brand }} {{ $device?->model }}
                        <span class="text-sm font-normal text-slate-400">({{ $device?->type }})</span>
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-[10px] text-slate-400">Customer</p>
                    <p class="text-sm font-bold text-white">{{ $customer?->name ?? 'Walk-in' }}</p>
                    @if($customer?->mobile)
                    <a href="tel:{{ $customer->mobile }}"
                       class="mt-1 flex items-center justify-end gap-1 text-[11px] text-emerald-400 font-medium qbtn">
                        <i class="fas fa-phone-alt text-[9px]"></i> {{ $customer->mobile }}
                    </a>
                    @endif
                </div>
            </div>

            {{-- Fault / Problem --}}
            <div class="bg-rose-500/5 border border-rose-500/15 rounded-xl px-3 py-2 mb-4">
                <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Reported Fault</p>
                <p class="text-sm text-slate-300 leading-relaxed">{{ $order->fault_details }}</p>
            </div>

            {{-- Device Specs Row --}}
            @if($device)
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="bg-white/3 rounded-xl px-3 py-2 text-center">
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest">CPU</p>
                    <p class="text-[11px] font-bold text-slate-200 truncate mt-0.5">{{ $device->processor ?? '—' }}</p>
                </div>
                <div class="bg-white/3 rounded-xl px-3 py-2 text-center">
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest">RAM</p>
                    <p class="text-[11px] font-bold text-slate-200 truncate mt-0.5">{{ $device->ram_old ?? '—' }}</p>
                </div>
                <div class="bg-white/3 rounded-xl px-3 py-2 text-center">
                    <p class="text-[9px] text-slate-500 uppercase tracking-widest">Storage</p>
                    <p class="text-[11px] font-bold text-slate-200 truncate mt-0.5">{{ $device->storage_old ?? '—' }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- ── QUICK STATUS BUTTONS ─── --}}
        <div class="px-5 pb-4">
            <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-2">Quick Update</p>
            <div class="grid grid-cols-4 gap-2">
                @foreach(['diagnosing' => '🔍', 'repairing' => '🔧', 'ready' => '✅', 'packing' => '📦'] as $s => $icon)
                <form action="{{ route('technician.services.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $s }}">
                    <button type="submit"
                        class="w-full py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition qbtn
                        {{ $order->status === $s
                            ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/30'
                            : 'bg-white/5 text-slate-400 hover:bg-white/10 border border-white/8' }}">
                        {{ $icon }}<br>{{ ucfirst($s) }}
                    </button>
                </form>
                @endforeach
            </div>
        </div>

        {{-- ── PARTS USED ────────────────────────────────── --}}
        @php $usedParts = $order->parts_used ?? []; @endphp
        @if(count($usedParts) > 0)
        <div class="px-5 pb-4">
            <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-2">Parts Used ({{ count($usedParts) }})</p>
            <div class="space-y-1.5">
                @foreach($usedParts as $part)
                <div class="flex items-center justify-between bg-white/3 border border-white/5 rounded-xl px-3 py-2">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0"></span>
                        <span class="text-[11px] font-semibold text-slate-200">{{ $part['name'] }}</span>
                        <span class="text-[10px] text-slate-500">× {{ $part['quantity'] }}</span>
                    </div>
                    <form action="{{ route('technician.services.removePart', [$order->id, $part['id']]) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-[10px] text-red-400 hover:text-red-300 font-bold qbtn">Remove</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── ADD PART ──────────────────────────────────── --}}
        <div class="px-5 pb-4" x-data="{ open: false }">
            <button onclick="toggleSection('part-{{ $order->id }}')"
                class="w-full text-[10px] font-black text-slate-400 uppercase tracking-widest py-2 bg-white/3 rounded-xl border border-white/5 hover:border-white/15 transition qbtn">
                <i class="fas fa-plus-circle mr-1"></i> Log Part / Component
            </button>
            <div id="part-{{ $order->id }}" class="hidden mt-3">
                <form action="{{ route('technician.services.usePart', $order->id) }}" method="POST"
                      class="bg-white/3 rounded-2xl border border-white/5 p-3 flex gap-2">
                    @csrf
                    <select name="product_id" required
                        class="flex-1 bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-xs font-medium text-white outline-none">
                        <option value="" disabled selected>Select part...</option>
                        @foreach(\App\Models\Product::where('status', 'active')->get() as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (Stock: {{ $p->stock_quantity }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity" value="1" min="1" max="50"
                        class="w-14 bg-white/5 border border-white/10 rounded-xl px-2 py-2 text-xs text-center font-bold text-white outline-none">
                    <button type="submit"
                        class="bg-rose-500 hover:bg-rose-600 text-white px-4 rounded-xl text-[10px] font-black uppercase tracking-wider transition shadow-lg shadow-rose-500/25 qbtn">
                        LOG
                    </button>
                </form>
            </div>
        </div>

        {{-- ── NOTES + PHOTO UPLOAD ────────────────────────── --}}
        <div class="px-5 pb-5 border-t border-white/5 pt-4">
            <button onclick="toggleSection('note-{{ $order->id }}')"
                class="w-full text-[10px] font-black text-slate-400 uppercase tracking-widest py-2 bg-white/3 rounded-xl border border-white/5 hover:border-white/15 transition qbtn mb-3">
                <i class="fas fa-comment-alt mr-1"></i> Add Note / Upload Photo
            </button>
            <div id="note-{{ $order->id }}" class="hidden">
                <form action="{{ route('technician.services.updateStatus', $order->id) }}" method="POST"
                      enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="hidden" name="status" value="{{ $order->status }}">
                    <textarea name="engineer_comment" rows="2"
                        placeholder="Technician observations, diagnosis notes..."
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-rose-500/40 transition resize-none">{{ $order->engineer_comment }}</textarea>
                    <div class="flex gap-2">
                        <label class="flex-1 flex items-center justify-center gap-2 bg-white/5 border-2 border-dashed border-white/15 rounded-xl py-3 cursor-pointer hover:border-rose-500/40 transition text-[11px] text-slate-400 qbtn">
                            <i class="fas fa-camera text-rose-400"></i> Upload Proof Photo
                            <input type="file" name="proof_photo" accept="image/*" capture="environment" class="sr-only">
                        </label>
                        <button type="submit"
                            class="bg-slate-700 hover:bg-slate-600 text-white px-5 rounded-xl text-[10px] font-black uppercase tracking-wider transition qbtn">
                            SAVE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-20">
        <div class="text-6xl mb-4">🎉</div>
        <p class="text-slate-300 font-bold text-lg">All Clear!</p>
        <p class="text-slate-500 text-sm mt-1">No active service jobs in the queue.</p>
    </div>
    @endforelse
</div>

{{-- ── BOTTOM NAVIGATION ────────────────────────────────────── --}}
<nav class="bottom-nav">
    <div class="grid grid-cols-3 max-w-2xl mx-auto px-4 py-2">
        <button class="flex flex-col items-center gap-1 py-2 text-rose-400">
            <i class="fas fa-clipboard-list text-lg"></i>
            <span class="text-[9px] font-black uppercase tracking-widest">Jobs</span>
        </button>
        <button onclick="document.getElementById('scanModal').classList.remove('hidden')"
            class="flex flex-col items-center gap-1 py-2 text-slate-400 hover:text-rose-400 transition qbtn">
            <div class="w-12 h-12 -mt-6 rounded-full bg-gradient-to-br from-rose-500 to-orange-500 flex items-center justify-center shadow-xl shadow-rose-500/40">
                <i class="fas fa-qrcode text-white text-xl"></i>
            </div>
        </button>
        <button class="flex flex-col items-center gap-1 py-2 text-slate-400">
            <i class="fas fa-microchip text-lg"></i>
            <span class="text-[9px] font-black uppercase tracking-widest">Stock</span>
        </button>
    </div>
</nav>

{{-- ── BARCODE SCAN MODAL ────────────────────────────────────── --}}
<div id="scanModal" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-end">
    <div class="w-full bg-[#0f172a] rounded-t-3xl p-6 border-t border-white/10">
        <div class="w-12 h-1 bg-white/20 rounded-full mx-auto mb-6"></div>
        <h3 class="text-base font-black text-white mb-4">Scan Job ID</h3>
        <p class="text-sm text-slate-400 mb-6">Type or scan the barcode to open a specific job.</p>
        <form action="{{ route('technician.dashboard') }}" method="GET" class="flex gap-3">
            <input type="text" name="scan" placeholder="TC-2026-000001"
                class="flex-1 bg-white/5 border border-white/15 rounded-2xl px-4 py-3 text-sm font-mono text-white outline-none focus:border-rose-500/50"
                autofocus>
            <button type="submit"
                class="bg-rose-500 text-white px-5 rounded-2xl font-bold text-sm qbtn shadow-lg shadow-rose-500/30">
                Find
            </button>
        </form>
        <button onclick="document.getElementById('scanModal').classList.add('hidden')"
            class="w-full mt-4 py-3 rounded-2xl bg-white/5 text-slate-400 text-sm font-medium qbtn">
            Cancel
        </button>
    </div>
</div>

<script>
// Auto-dismiss flash after 4s
setTimeout(() => {
    const f = document.getElementById('flash');
    if (f) f.remove();
}, 4000);

// Toggle collapsible sections
function toggleSection(id) {
    const el = document.getElementById(id);
    if (el) el.classList.toggle('hidden');
}

// Live search
function filterJobs(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#jobList [data-search]').forEach(card => {
        card.style.display = (!q || card.dataset.search.includes(q)) ? '' : 'none';
    });
}

// Show scan modal and focus
document.addEventListener('keydown', e => {
    if (e.key === '/') {
        e.preventDefault();
        document.getElementById('scanModal').classList.remove('hidden');
    }
});
</script>
</body>
</html>

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
            --bg: #050b14; /* Deep icy cave background */
            --surface: rgba(16, 28, 48, 0.65); /* Glassmorphic surface */
            --border: rgba(56, 189, 248, 0.15); /* Icy blue border */
            --border-glow: rgba(56, 189, 248, 0.4);
            --accent: #38bdf8; /* Icy blue text */
            --text: #f1f5f9;
        }
        body { 
            background: var(--bg); 
            color: var(--text); 
            min-height: 100vh; 
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(56, 189, 248, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(14, 165, 233, 0.05) 0%, transparent 50%);
            background-attachment: fixed;
        }

        /* Glassmorphism Utilities */
        .glass-panel {
            background: var(--surface);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        /* Mobile bottom nav */
        .bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; background: rgba(5, 11, 20, 0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-top: 1px solid var(--border); z-index: 50; padding-bottom: env(safe-area-inset-bottom); }

        /* Status badges */
        .badge-received    { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); box-shadow: 0 0 10px rgba(59,130,246,0.1); }
        .badge-diagnosing  { background: rgba(234,179,8,0.15);  color: #facc15; border: 1px solid rgba(234,179,8,0.3); box-shadow: 0 0 10px rgba(234,179,8,0.1); }
        .badge-repairing   { background: rgba(249,115,22,0.15); color: #fb923c; border: 1px solid rgba(249,115,22,0.3); box-shadow: 0 0 10px rgba(249,115,22,0.1); }
        .badge-ready       { background: rgba(34,197,94,0.15);  color: #4ade80; border: 1px solid rgba(34,197,94,0.3); box-shadow: 0 0 10px rgba(34,197,94,0.1); }
        .badge-completed   { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); box-shadow: 0 0 10px rgba(16,185,129,0.1); }
        .badge-packing     { background: rgba(6,182,212,0.15); color: #22d3ee; border: 1px solid rgba(6,182,212,0.3); box-shadow: 0 0 10px rgba(6,182,212,0.1); }

        /* Priority */
        .priority-high   { color: #f87171; }
        .priority-medium { color: #fbbf24; }
        .priority-low    { color: #34d399; }

        /* Quick-action button */
        .qbtn { transition: all .2s cubic-bezier(0.4, 0, 0.2, 1); }
        .qbtn:active { transform: scale(.95); opacity:.8; }

        /* Card hover glow */
        .job-card { transition: all .3s cubic-bezier(0.4, 0, 0.2, 1); }
        .job-card:hover { 
            border-color: var(--border-glow);
            box-shadow: 0 0 25px rgba(56, 189, 248, 0.15), 0 8px 32px rgba(0,0,0,.5); 
            transform: translateY(-2px);
        }

        /* Pulse for assigned badge */
        .pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%,100%{opacity:1; transform:scale(1)} 50%{opacity:.5; transform:scale(0.8)} }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(56, 189, 248, 0.2); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(56, 189, 248, 0.4); }
    </style>
</head>
<body>

{{-- ── TOP BAR ──────────────────────────────────────────────── --}}
<header class="sticky top-0 z-40 bg-[#050b14]/80 backdrop-blur-xl border-b border-sky-500/10 px-4 py-3 shadow-[0_4px_30px_rgba(0,0,0,0.5)]">
    <div class="flex items-center justify-between max-w-2xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-lg shadow-[0_0_15px_rgba(56,189,248,0.4)] border border-sky-300/30">
                <i class="fas fa-microchip text-white text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-white leading-none tracking-wide">TC Technician</p>
                <p class="text-[10px] text-sky-200/70 leading-none mt-1 font-medium">{{ auth()->user()->name }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="flex items-center gap-1.5 px-2.5 py-1 bg-sky-500/10 border border-sky-500/30 rounded-full text-[10px] font-bold text-sky-400 shadow-[0_0_10px_rgba(56,189,248,0.2)]">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 pulse shadow-[0_0_5px_#38bdf8]"></span>ONLINE
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-9 h-9 rounded-xl bg-white/5 hover:bg-rose-500/20 border border-white/10 flex items-center justify-center text-slate-400 hover:text-rose-400 hover:border-rose-500/30 transition qbtn">
                    <i class="fas fa-power-off text-xs"></i>
                </button>
            </form>
        </div>
    </div>
</header>

{{-- ── FLASH MESSAGES ─────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 pt-4">
@if(session('success'))
    <div id="flash" class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-2xl text-sm font-medium mb-4 shadow-[0_0_15px_rgba(16,185,129,0.15)] backdrop-blur-md">
        <i class="fas fa-check-circle text-emerald-400"></i> {{ session('success') }}
    </div>
@elseif(session('error'))
    <div id="flash" class="flex items-center gap-3 bg-rose-500/10 border border-rose-500/30 text-rose-300 px-4 py-3 rounded-2xl text-sm font-medium mb-4 shadow-[0_0_15px_rgba(244,63,94,0.15)] backdrop-blur-md">
        <i class="fas fa-exclamation-circle text-rose-400"></i> {{ session('error') }}
    </div>
@endif
</div>

{{-- ── STATS ROW ────────────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 pt-2">
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="glass-panel rounded-2xl p-4 text-center relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-sky-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <p class="text-2xl font-black text-sky-400 relative z-10 drop-shadow-[0_0_8px_rgba(56,189,248,0.5)]">
                {{ $orders->whereIn('status',['received','diagnosing','repairing'])->count() }}
            </p>
            <p class="text-[10px] text-sky-200/60 mt-1 uppercase tracking-widest font-semibold relative z-10">Active</p>
        </div>
        <div class="glass-panel rounded-2xl p-4 text-center relative overflow-hidden group border-rose-500/20">
            <div class="absolute inset-0 bg-gradient-to-br from-rose-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <p class="text-2xl font-black text-rose-400 relative z-10 drop-shadow-[0_0_8px_rgba(244,63,94,0.5)]">
                {{ $orders->where('priority','high')->count() }}
            </p>
            <p class="text-[10px] text-rose-200/60 mt-1 uppercase tracking-widest font-semibold relative z-10">Urgent</p>
        </div>
        <div class="glass-panel rounded-2xl p-4 text-center relative overflow-hidden group border-emerald-500/20">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
            <p class="text-2xl font-black text-emerald-400 relative z-10 drop-shadow-[0_0_8px_rgba(52,211,153,0.5)]">
                {{ $orders->where('status','ready')->count() }}
            </p>
            <p class="text-[10px] text-emerald-200/60 mt-1 uppercase tracking-widest font-semibold relative z-10">Ready</p>
        </div>
    </div>
</div>

{{-- ── SEARCH BAR ─────────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 mb-6">
    <div class="relative group">
        <div class="absolute inset-0 bg-sky-500/20 rounded-2xl blur-md opacity-0 group-focus-within:opacity-100 transition duration-500"></div>
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-sky-300/60 text-sm z-10"></i>
        <input type="text" id="searchInput" oninput="filterJobs(this.value)"
            placeholder="Search job ID, customer, device..."
            class="relative w-full glass-panel rounded-2xl pl-10 pr-4 py-3.5 text-sm text-white placeholder-sky-200/40 outline-none focus:border-sky-400/60 focus:bg-white/5 transition duration-300 z-10">
    </div>
</div>

{{-- ── JOB CARDS ────────────────────────────────────────────── --}}
<div class="max-w-2xl mx-auto px-4 pb-28 space-y-5" id="jobList">

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
    
    <div class="job-card glass-panel rounded-3xl overflow-hidden relative"
         data-search="{{ strtolower($order->tc_job_id . ' ' . ($customer?->name ?? '') . ' ' . ($device?->brand ?? '') . ' ' . ($device?->model ?? '')) }}">
        
        {{-- Urgent Glowing Top Border --}}
        @if(($order->priority ?? '') === 'high')
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 via-rose-400 to-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.6)]"></div>
        @endif

        {{-- Card Header --}}
        <div class="px-5 pt-6 pb-4">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-1.5">
                        <span class="font-mono text-[11px] font-black text-sky-300 bg-sky-400/10 border border-sky-400/20 px-2.5 py-1 rounded-lg shadow-[0_0_8px_rgba(56,189,248,0.15)]">
                            {{ $order->tc_job_id }}
                        </span>
                        <span class="text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg {{ $badgeClass }}">
                            {{ $order->status }}
                        </span>
                    </div>
                    <h2 class="text-lg font-black text-white leading-tight tracking-wide drop-shadow-md">
                        {{ $device?->brand }} {{ $device?->model }}
                    </h2>
                    <p class="text-xs font-medium text-sky-200/60 mt-0.5">Device Type: {{ $device?->type ?? 'N/A' }}</p>
                </div>
                <div class="text-right flex-shrink-0 bg-white/5 border border-white/10 rounded-xl p-2.5 backdrop-blur-md">
                    <p class="text-[9px] text-sky-200/50 uppercase tracking-widest font-black mb-0.5">Customer</p>
                    <p class="text-sm font-bold text-white">{{ $customer?->name ?? 'Walk-in' }}</p>
                    @if($customer?->mobile)
                    <a href="tel:{{ $customer->mobile }}"
                       class="mt-1.5 flex items-center justify-end gap-1.5 text-[11px] text-emerald-400 font-bold hover:text-emerald-300 transition qbtn">
                        <i class="fas fa-phone-alt text-[9px]"></i> {{ $customer->mobile }}
                    </a>
                    @endif
                </div>
            </div>

            {{-- Fault / Problem --}}
            <div class="bg-red-500/5 border border-red-500/15 rounded-xl px-4 py-3 mb-5 shadow-inner">
                <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                    <i class="fas fa-exclamation-triangle text-[9px]"></i> Reported Fault
                </p>
                <p class="text-sm text-slate-200 leading-relaxed font-medium">{{ $order->fault_details }}</p>
            </div>

            {{-- Device Specs Row --}}
            @if($device)
            <div class="grid grid-cols-3 gap-3 mb-2">
                <div class="bg-sky-900/20 border border-sky-500/10 rounded-xl px-3 py-2.5 text-center transition hover:bg-sky-800/30">
                    <p class="text-[9px] text-sky-300/60 uppercase tracking-widest font-black">CPU</p>
                    <p class="text-[11px] font-bold text-white truncate mt-1">{{ $device->processor ?? '—' }}</p>
                </div>
                <div class="bg-sky-900/20 border border-sky-500/10 rounded-xl px-3 py-2.5 text-center transition hover:bg-sky-800/30">
                    <p class="text-[9px] text-sky-300/60 uppercase tracking-widest font-black">RAM</p>
                    <p class="text-[11px] font-bold text-white truncate mt-1">{{ $device->ram_old ?? '—' }}</p>
                </div>
                <div class="bg-sky-900/20 border border-sky-500/10 rounded-xl px-3 py-2.5 text-center transition hover:bg-sky-800/30">
                    <p class="text-[9px] text-sky-300/60 uppercase tracking-widest font-black">Storage</p>
                    <p class="text-[11px] font-bold text-white truncate mt-1">{{ $device->storage_old ?? '—' }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- ── QUICK STATUS BUTTONS ─── --}}
        <div class="px-5 pb-5">
            <div class="w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent mb-4"></div>
            <p class="text-[9px] text-sky-200/50 font-black uppercase tracking-widest mb-3 pl-1">Status Workflow</p>
            <div class="grid grid-cols-4 gap-2.5">
                @foreach(['diagnosing' => 'fas fa-stethoscope', 'repairing' => 'fas fa-tools', 'ready' => 'fas fa-check-double', 'packing' => 'fas fa-box'] as $s => $icon)
                <form action="{{ route('technician.services.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $s }}">
                    <button type="submit"
                        class="w-full py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition duration-300 drop-shadow flex flex-col items-center justify-center gap-1.5 qbtn
                        {{ $order->status === $s
                            ? 'bg-gradient-to-br from-sky-400 to-blue-600 text-white shadow-[0_4px_15px_rgba(56,189,248,0.4)] border border-sky-300/50'
                            : 'bg-white/5 text-slate-400 hover:bg-white/10 border border-white/5 hover:border-sky-500/30 hover:text-sky-300' }}">
                        <i class="{{ $icon }} text-sm"></i>
                        {{ ucfirst($s) }}
                    </button>
                </form>
                @endforeach
            </div>
        </div>

        {{-- ── PARTS USED ────────────────────────────────── --}}
        @php $usedParts = $order->parts_used ?? []; @endphp
        @if(count($usedParts) > 0)
        <div class="px-5 pb-5">
            <div class="bg-sky-900/20 border border-sky-500/10 rounded-2xl p-4">
                <p class="text-[9px] text-sky-300/60 font-black uppercase tracking-widest mb-3 border-b border-white/5 pb-2">Installed Components ({{ count($usedParts) }})</p>
                <div class="space-y-2">
                    @foreach($usedParts as $part)
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shadow-[0_0_5px_#22d3ee] flex-shrink-0"></span>
                            <span class="text-xs font-semibold text-slate-200">{{ $part['name'] }}</span>
                            <span class="text-[10px] font-black text-cyan-400/80 bg-cyan-400/10 px-1.5 rounded">×{{ $part['quantity'] }}</span>
                        </div>
                        <form action="{{ route('technician.services.removePart', [$order->id, $part['id']]) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-6 h-6 rounded flex items-center justify-center bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition qbtn">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ── ACTIONS (PART + NOTE) ─────────────────────── --}}
        <div class="px-5 pb-6">
            <div class="flex gap-2.5">
                <button onclick="toggleSection('part-{{ $order->id }}')"
                    class="flex-1 text-[10px] font-black text-sky-300 uppercase tracking-widest py-2.5 bg-sky-500/10 rounded-xl border border-sky-500/20 hover:bg-sky-500/20 transition qbtn shadow-inner">
                    <i class="fas fa-microchip mr-1.5"></i> Log Part
                </button>
                <button onclick="toggleSection('note-{{ $order->id }}')"
                    class="flex-1 text-[10px] font-black text-sky-300 uppercase tracking-widest py-2.5 bg-sky-500/10 rounded-xl border border-sky-500/20 hover:bg-sky-500/20 transition qbtn shadow-inner">
                    <i class="fas fa-camera mr-1.5"></i> Add Note
                </button>
            </div>

            {{-- Part Form --}}
            <div id="part-{{ $order->id }}" class="hidden mt-3">
                <form action="{{ route('technician.services.usePart', $order->id) }}" method="POST"
                      class="bg-[#050b14]/50 rounded-2xl border border-white/5 p-3 flex gap-2 backdrop-blur-md">
                    @csrf
                    <select name="product_id" required
                        class="flex-1 bg-white/5 border border-white/10 rounded-xl px-3 py-2.5 text-xs font-medium text-white outline-none focus:border-cyan-500/50">
                        <option value="" disabled selected class="text-slate-800">Select component from inventory...</option>
                        @foreach(\App\Models\Product::where('status', 'active')->get() as $p)
                        <option value="{{ $p->id }}" class="text-slate-800">{{ $p->name }} (In Stock: {{ $p->stock_quantity }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity" value="1" min="1" max="50"
                        class="w-14 bg-white/5 border border-white/10 rounded-xl px-2 py-2.5 text-sm text-center font-bold text-white outline-none focus:border-cyan-500/50">
                    <button type="submit"
                        class="bg-cyan-500 hover:bg-cyan-400 text-[#050b14] px-4 rounded-xl text-[10px] font-black uppercase tracking-wider transition shadow-[0_0_15px_rgba(6,182,212,0.3)] qbtn">
                        ADD
                    </button>
                </form>
            </div>

            {{-- Note / Photo Form --}}
            <div id="note-{{ $order->id }}" class="hidden mt-3">
                <form action="{{ route('technician.services.updateStatus', $order->id) }}" method="POST"
                      enctype="multipart/form-data" class="bg-[#050b14]/50 rounded-2xl border border-white/5 p-3 space-y-3 backdrop-blur-md">
                    @csrf
                    <input type="hidden" name="status" value="{{ $order->status }}">
                    <textarea name="engineer_comment" rows="2"
                        placeholder="Type diagnosis notes, component statuses, or customer warnings here..."
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-cyan-500/40 transition resize-none">{{ $order->engineer_comment }}</textarea>
                    
                    <div class="flex gap-2">
                        <label class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-sky-500/10 to-indigo-500/10 border-2 border-dashed border-sky-500/30 rounded-xl py-2 cursor-pointer hover:border-sky-400/60 transition text-[11px] font-bold text-sky-300 qbtn">
                            <i class="fas fa-camera text-sky-400 text-sm"></i> Upload Proof
                            <input type="file" name="proof_photo" accept="image/*" capture="environment" class="sr-only">
                        </label>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-500 text-white px-6 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-[0_0_15px_rgba(37,99,235,0.4)] transition qbtn">
                            SAVE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-24 glass-panel rounded-3xl border border-white/5">
        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-sky-400/20 to-blue-600/20 rounded-full flex items-center justify-center mb-5 border border-sky-500/20 shadow-[0_0_30px_rgba(56,189,248,0.1)]">
            <i class="fas fa-coffee text-3xl text-sky-400 drop-shadow-[0_0_10px_rgba(56,189,248,0.5)]"></i>
        </div>
        <p class="text-white font-black text-xl tracking-wide">Queue is Empty</p>
        <p class="text-sky-200/60 text-sm mt-2 font-medium">All assigned service jobs are completed.</p>
    </div>
    @endforelse
</div>

{{-- ── BOTTOM NAVIGATION ────────────────────────────────────── --}}
<nav class="bottom-nav shadow-[0_-10px_40px_rgba(0,0,0,0.6)]">
    <div class="grid grid-cols-3 max-w-2xl mx-auto px-4 py-3">
        <button class="flex flex-col items-center gap-1.5 text-sky-400 drop-shadow-[0_0_8px_rgba(56,189,248,0.5)] transition qbtn">
            <i class="fas fa-clipboard-list text-xl"></i>
            <span class="text-[9px] font-black uppercase tracking-widest">Jobs</span>
        </button>
        <button onclick="document.getElementById('scanModal').classList.remove('hidden')"
            class="flex flex-col items-center justify-start group transition qbtn">
            <div class="w-14 h-14 -mt-8 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center shadow-[0_0_20px_rgba(56,189,248,0.5)] border-2 border-[#050b14] group-hover:scale-105 transition">
                <i class="fas fa-qrcode text-white text-2xl drop-shadow-md"></i>
            </div>
        </button>
        <button class="flex flex-col items-center gap-1.5 text-slate-500 hover:text-sky-300 transition qbtn">
            <i class="fas fa-layer-group text-xl"></i>
            <span class="text-[9px] font-black uppercase tracking-widest">Parts</span>
        </button>
    </div>
</nav>

{{-- ── BARCODE SCAN MODAL ────────────────────────────────────── --}}
<div id="scanModal" class="hidden fixed inset-0 z-50 bg-[#050b14]/90 backdrop-blur-md flex items-end">
    <div class="w-full glass-panel rounded-t-[40px] p-8 border-t border-sky-500/30 shadow-[0_-10px_50px_rgba(56,189,248,0.15)] animate-[slideUp_0.3s_ease-out]">
        <style> @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } } </style>
        <div class="w-16 h-1.5 bg-sky-500/30 rounded-full mx-auto mb-8 shadow-[0_0_10px_rgba(56,189,248,0.3)]"></div>
        <div class="text-center mb-6">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-sky-400/20 to-blue-600/20 rounded-2xl flex items-center justify-center mb-4 border border-sky-500/20">
                <i class="fas fa-barcode text-3xl text-sky-400"></i>
            </div>
            <h3 class="text-xl font-black text-white">Scan Job ID</h3>
            <p class="text-xs font-medium text-sky-200/60 mt-2">Use scanner or type manually (e.g. TC-0001)</p>
        </div>
        <form action="{{ route('technician.dashboard') }}" method="GET" class="flex flex-col gap-4">
            <div class="relative">
                <i class="fas fa-keyboard absolute left-4 top-1/2 -translate-y-1/2 text-sky-400/50"></i>
                <input type="text" name="scan" placeholder="TC-..."
                    class="w-full bg-[#050b14]/50 border-2 border-sky-500/20 rounded-2xl pl-11 pr-4 py-4 text-base font-bold text-white uppercase tracking-widest outline-none focus:border-sky-400/60 focus:bg-white/5 transition shadow-inner"
                    autofocus>
            </div>
            <button type="submit"
                class="w-full py-4 bg-gradient-to-r from-sky-400 to-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-sm qbtn shadow-[0_0_20px_rgba(56,189,248,0.4)]">
                Locate Job
            </button>
        </form>
        <button onclick="document.getElementById('scanModal').classList.add('hidden')"
            class="w-full mt-4 py-4 rounded-2xl bg-white/5 text-slate-400 hover:text-white hover:bg-white/10 text-xs font-bold uppercase tracking-widest transition qbtn border border-white/5">
            Cancel
        </button>
    </div>
</div>

<script>
// Auto-dismiss flash after 4s
setTimeout(() => {
    const f = document.getElementById('flash');
    if (f) {
        f.style.opacity = '0';
        f.style.transform = 'translateY(-10px)';
        f.style.transition = 'all 0.5s ease';
        setTimeout(() => f.remove(), 500);
    }
}, 4000);

// Toggle collapsible sections purely
function toggleSection(id) {
    const el = document.getElementById(id);
    if (!el) return;
    
    if (el.classList.contains('hidden')) {
        el.classList.remove('hidden');
        el.style.opacity = '0';
        el.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            el.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 10);
    } else {
        el.style.opacity = '0';
        el.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            el.classList.add('hidden');
        }, 300);
    }
}

// Live search (enhanced smooth transitions)
function filterJobs(q) {
    q = q.toLowerCase();
    let found = false;
    document.querySelectorAll('#jobList .job-card').forEach(card => {
        const match = !q || card.dataset.search.includes(q);
        if (match) {
            card.style.display = '';
            setTimeout(() => { card.style.opacity = '1'; card.style.transform = 'scale(1)'; }, 10);
            found = true;
        } else {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => card.style.display = 'none', 300);
        }
    });
}

// Show scan modal and focus
document.addEventListener('keydown', e => {
    if (e.key === '/') {
        e.preventDefault();
        const m = document.getElementById('scanModal');
        m.classList.remove('hidden');
        setTimeout(() => m.querySelector('input').focus(), 100);
    }
});
</script>
</body>
</html>

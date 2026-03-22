@extends('layouts.customer')

@section('content')
<div class="animate-slide-up max-w-7xl mx-auto pb-24">
    
    <!-- Header Matrix -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-10 mb-16 px-4">
        <div class="flex items-center gap-8">
            <div class="w-20 h-20 rounded-3xl bg-slate-950 border border-white/5 flex items-center justify-center text-indigo-400 shadow-2xl relative overflow-hidden group shrink-0">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <svg class="w-10 h-10 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <h1 class="text-4xl font-black text-white tracking-tight">Protection Certificate</h1>
                <p class="text-slate-500 font-mono text-xs mt-2 uppercase tracking-[0.2em] font-black">
                    Registry UUID: <span class="text-indigo-400">#WC-{{ str_pad($warranty->id, 6, '0', STR_PAD_LEFT) }}</span>
                </p>
            </div>
        </div>
        <a href="{{ route('customer.warranty.index') }}" class="group flex items-center gap-4 px-10 py-5 rounded-2xl bg-white/5 border border-white/10 text-slate-400 hover:text-white hover:bg-white/10 transition-all font-black text-[10px] uppercase tracking-[0.3em] self-start md:self-center shadow-2xl backdrop-blur-3xl">
            <svg class="w-4 h-4 group-hover:-translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Historical Registry
        </a>
    </div>

    @if(session('success'))
        <div class="mb-10 mx-4 p-6 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-[0.2em] flex items-center gap-4 shadow-2xl animate-bounce-slow">
            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-10 mx-4 p-6 rounded-3xl bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-black uppercase tracking-[0.2em] flex items-center gap-4 shadow-2xl">
            <div class="w-8 h-8 rounded-xl bg-red-500/20 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start px-4">
        <!-- Main Content Matrix -->
        <div class="lg:col-span-8 space-y-12">
            <!-- Protection Module -->
            <div class="super-card p-12 relative overflow-hidden group rounded-[3rem] border-white/5 bg-slate-950/40 backdrop-blur-3xl">
                {{-- Decorative background text --}}
                <div class="absolute -right-12 -top-12 text-[10rem] font-black text-white/[0.02] select-none pointer-events-none tracking-tighter uppercase whitespace-nowrap">SECURE</div>
                
                <div class="absolute inset-0 bg-indigo-500/[0.01] pointer-events-none group-hover:bg-indigo-500/[0.03] transition-colors"></div>

                <div class="flex items-center justify-between mb-16 relative z-10">
                    <div class="flex items-center gap-8">
                        <div class="w-24 h-24 rounded-[2rem] flex items-center justify-center text-5xl shadow-2xl relative border-2 transition-all duration-700 group-hover:rotate-3
                            {{ $warranty->is_active ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-slate-950 border-white/5' }}">
                            {{ $warranty->is_active ? '🛡️' : '🔘' }}
                            @if($warranty->is_active)
                                <div class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-emerald-500 rounded-full border-4 border-slate-950 animate-pulse shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-3xl font-black text-white tracking-tighter leading-tight">Verified Protection Node</h2>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] mt-3 flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full {{ $warranty->is_active ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]' : 'bg-red-500' }}"></span>
                                <span class="{{ $warranty->is_active ? 'text-emerald-500/70' : 'text-red-500/70' }}">
                                    {{ $warranty->is_active ? 'Protocol Active' : 'Link Terminated' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <div class="p-8 rounded-[2rem] bg-slate-950/60 border border-white/5 hover:border-indigo-500/30 transition-all group/node shadow-inner">
                        <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.2em] mb-4">Linked Entity</p>
                        <p class="text-white font-black text-2xl leading-tight tracking-tighter group-hover/node:text-indigo-400 transition-colors">
                            @if($warranty->sparePart)
                                {{ $warranty->sparePart->name }}
                            @elseif($warranty->serviceOrder)
                                System Service #{{ $warranty->serviceOrder->tc_job_id }}
                            @else
                                Legacy Component
                            @endif
                        </p>
                    </div>
                    @if($warranty->serial_number)
                    <div class="p-8 rounded-[2rem] bg-slate-950/60 border border-white/5 shadow-inner">
                        <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.2em] mb-4">Identity Signature (S/N)</p>
                        <p class="text-indigo-400 font-black font-mono text-2xl tracking-[0.2em]">{{ $warranty->serial_number }}</p>
                    </div>
                    @endif
                    <div class="p-8 rounded-[2rem] bg-slate-950/60 border border-white/5 shadow-inner">
                        <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.2em] mb-4">Initial Handshake</p>
                        <p class="text-slate-200 font-black text-xl tracking-widest uppercase">{{ $warranty->purchase_date->format('d F Y') }}</p>
                    </div>
                    <div class="p-8 rounded-[2rem] bg-slate-950/60 border border-white/5 shadow-inner">
                        <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.2em] mb-4">Lifespan Spectrum</p>
                        <p class="text-slate-200 font-black text-sm flex items-center gap-4 tracking-widest uppercase">
                            {{ $warranty->warranty_start->format('M Y') }}
                            <svg class="w-5 h-5 text-indigo-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            {{ $warranty->warranty_end->format('M Y') }}
                        </p>
                    </div>
                </div>

                <div class="mt-16 relative z-10">
                    <div class="flex justify-between items-center mb-6 px-2">
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.3em]">Lifecycle Consumption</span>
                        <span class="{{ $warranty->is_active ? 'text-indigo-400' : 'text-red-500' }} text-[10px] font-black uppercase tracking-[0.3em]">
                            {{ $warranty->progress_percent }}% Resource Occupied
                        </span>
                    </div>
                    <div class="h-5 rounded-full bg-slate-950 border border-white/10 p-1.5 overflow-hidden shadow-inner">
                        <div class="h-full rounded-full transition-all duration-[2000ms] shadow-2xl relative
                            {{ $warranty->days_remaining < 30 ? 'bg-gradient-to-r from-red-600 to-rose-400 shadow-red-500/20' : 'bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-500 shadow-indigo-500/20' }}"
                            style="width: {{ $warranty->progress_percent }}%">
                            <div class="absolute inset-0 bg-white/20 animate-pulse opacity-20"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historical Claims Registry -->
            @if($warranty->claims->count())
            <div class="space-y-8 animate-slide-up" style="animation-delay: 200ms">
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] flex items-center gap-6">
                    <span class="w-12 h-px bg-white/10"></span>
                    Incident Registry
                    <span class="w-12 h-px bg-white/10"></span>
                </h3>
                <div class="grid grid-cols-1 gap-8">
                    @foreach($warranty->claims as $claim)
                    <div class="super-card p-8 group rounded-[2.5rem] bg-slate-950/40 border-white/5">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-center text-2xl shadow-xl group-hover:scale-110 transition-transform">📁</div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-600 mb-1">Incident Report Node</p>
                                    <p class="text-[11px] text-white font-black tracking-widest uppercase">{{ $claim->created_at->format('M d, Y • H:i') }}</p>
                                </div>
                            </div>
                            <span class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] border self-start
                                @if($claim->status === 'approved' || $claim->status === 'resolved') bg-emerald-500/10 border-emerald-500/30 text-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.2)]
                                @elseif($claim->status === 'rejected') bg-red-500/10 border-red-500/30 text-red-400
                                @elseif($claim->status === 'reviewing') bg-indigo-500/10 border-indigo-500/30 text-indigo-400 animate-pulse
                                @else bg-slate-900 border-white/5 text-slate-500 @endif">
                                {{ $claim->status }}
                            </span>
                        </div>
                        <div class="p-6 bg-slate-950/60 rounded-2xl border-l-4 border-indigo-500/40 mb-8">
                            <p class="text-slate-400 text-sm leading-relaxed font-medium px-2">{{ $claim->description }}</p>
                        </div>
                        @if($claim->admin_notes)
                            <div class="p-6 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl relative overflow-hidden">
                                <div class="absolute inset-0 bg-indigo-500/5 opacity-40"></div>
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] mb-4 flex items-center gap-3 relative z-10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Nodal Feedback Repository
                                </p>
                                <p class="text-slate-200 text-sm font-semibold relative z-10 leading-relaxed">{{ $claim->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Claim Injection Interface -->
            @if($warranty->is_active && !$existingClaim)
            <div class="super-card p-12 rounded-[3rem] bg-indigo-500/[0.02] border-indigo-500/30 shadow-2xl relative overflow-hidden" id="claim">
                <div class="absolute -right-24 -top-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px]"></div>
                
                <div class="mb-12 relative z-10">
                    <h3 class="text-3xl font-black text-white tracking-tighter leading-tight mb-3">Initialize Claim Protocol</h3>
                    <p class="text-slate-500 font-medium text-sm">Submit defect metadata and diagnostic imagery for nodal review.</p>
                </div>

                <form method="POST" action="{{ route('customer.warranty.claim', $warranty->id) }}" enctype="multipart/form-data" class="space-y-10 relative z-10">
                    @csrf
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-2">Anomaly Description Registry *</label>
                        <textarea name="description" rows="5" required minlength="20"
                            placeholder="Categorize the failure: Hardware degradation, terminal instability, or functional disconnect..."
                            class="super-input w-full min-h-[180px] py-6 leading-relaxed font-medium text-slate-300">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-3 ml-2 flex items-center gap-2">
                            <span class="w-1 h-1 bg-red-500 rounded-full"></span> {{ $message }}
                        </p> @enderror
                    </div>
                    
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-2">Evidence Payload (Visual Diagnostics)</label>
                        <div class="super-card bg-slate-950/60 border-dashed border-2 border-white/5 p-16 text-center group cursor-pointer hover:border-indigo-500/40 hover:bg-indigo-500/5 transition-all duration-500 rounded-[2rem] shadow-inner" onclick="document.getElementById('evidence-upload').click()">
                            <input type="file" id="evidence-upload" name="evidence[]" multiple accept="image/*" class="hidden" onchange="updateFileList(this)">
                            <div class="w-20 h-20 rounded-3xl bg-slate-950 border border-white/5 flex items-center justify-center mx-auto mb-8 group-hover:scale-110 group-hover:rotate-6 group-hover:bg-indigo-500/10 group-hover:border-indigo-500/20 transition-all duration-700 shadow-2xl">
                                <svg class="w-10 h-10 text-slate-700 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-white font-black text-base tracking-tight mb-2">Inject Diagnostic Data</p>
                            <p class="text-slate-600 text-[10px] font-black uppercase tracking-[0.3em]">JPG, PNG, WEBP • Max 4MB per Node</p>
                        </div>
                        <div id="file-list" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6"></div>
                    </div>

                    <button type="submit" class="w-full py-6 rounded-3xl bg-indigo-500 text-white text-[11px] font-black uppercase tracking-[0.4em] hover:bg-indigo-400 transition-all shadow-[0_20px_50px_rgba(99,102,241,0.4)] flex items-center justify-center gap-6 group hover:scale-[1.02] active:scale-95">
                        Initialize Claim sequence
                        <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
            @elseif($existingClaim)
                <div class="super-card p-24 text-center relative overflow-hidden rounded-[3rem] bg-indigo-500/[0.02] border-indigo-500/20">
                    <div class="absolute inset-0 bg-indigo-500/[0.01] animate-pulse"></div>
                    <div class="w-24 h-24 bg-slate-950 border border-white/5 rounded-3xl flex items-center justify-center mx-auto mb-10 shadow-2xl relative">
                        <span class="text-6xl animate-bounce-slow">⏳</span>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-4 tracking-tighter">Claim Protocol Active</h3>
                    <p class="text-slate-500 text-sm max-w-sm mx-auto font-medium leading-relaxed uppercase tracking-[0.1em]">An incident report is currently under review by nodal diagnostics. Stay connected for feedback.</p>
                </div>
            @elseif(!$warranty->is_active)
                <div class="super-card p-24 text-center relative overflow-hidden rounded-[3rem] bg-red-500/[0.02] border-red-500/20">
                    <div class="w-24 h-24 bg-slate-950 border border-white/5 rounded-3xl flex items-center justify-center mx-auto mb-10 shadow-2xl grayscale group-hover:grayscale-0 transition-all">
                        <span class="text-6xl text-red-500">🚫</span>
                    </div>
                    <h3 class="text-3xl font-black text-white mb-4 tracking-tighter">Sequence Terminated</h3>
                    <p class="text-slate-500 text-sm max-w-sm mx-auto font-medium leading-relaxed uppercase tracking-[0.1em]">Nodal protection has reached terminal end-of-life status. No further claim protocols are authorized.</p>
                </div>
            @endif
        </div>

        <!-- System Hub Sidebar -->
        <div class="lg:col-span-4 space-y-12">
            <!-- Metadata Registry -->
            <div class="super-card p-10 bg-slate-950/40 backdrop-blur-3xl border-white/5 rounded-[2.5rem] shadow-2xl">
                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.4em] mb-12 flex items-center gap-4">
                    <span class="w-3 h-1 bg-indigo-500 rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]"></span>
                    Terminal Registry
                </h3>
                <div class="space-y-8">
                    <div class="flex flex-col gap-3 group">
                        <span class="text-[10px] text-slate-700 font-black uppercase tracking-[0.2em] group-hover:text-indigo-400 transition-colors">Registry ID</span>
                        <span class="text-indigo-400 font-black font-mono bg-indigo-500/5 py-4 px-6 rounded-2xl border border-indigo-500/20 shadow-xl text-center tracking-[0.3em] text-sm">#WC-{{ str_pad($warranty->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex flex-col gap-3">
                            <span class="text-[10px] text-slate-700 font-black uppercase tracking-[0.2em]">Genesis</span>
                            <span class="text-slate-300 font-black text-[11px] tracking-widest uppercase bg-white/5 px-4 py-2 rounded-xl text-center border border-white/5">{{ $warranty->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex flex-col gap-3">
                            <span class="text-[10px] text-slate-700 font-black uppercase tracking-[0.2em]">Auth Status</span>
                            <span class="{{ $warranty->is_active ? 'text-emerald-500/80 bg-emerald-500/5' : 'text-red-500/80 bg-red-500/5' }} font-black text-[11px] tracking-widest uppercase px-4 py-2 rounded-xl text-center border border-white/5 shadow-inner">{{ strtoupper($warranty->status) }}</span>
                        </div>
                    </div>
                    <div class="pt-10 border-t border-white/5">
                        <div class="flex items-center justify-between px-2">
                            <span class="text-[10px] text-slate-700 font-black uppercase tracking-[0.2em]">Incident Count</span>
                            <span class="text-indigo-400 font-black text-xs px-5 py-1.5 bg-indigo-500/10 rounded-xl border border-indigo-500/20 shadow-2xl">{{ $warranty->claims->count() }} Files</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contextual Support -->
            <div class="super-card p-10 bg-indigo-600/[0.03] border-indigo-500/20 rounded-[2.5rem] group/support">
                <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.4em] mb-8 flex items-center gap-4">
                    <span class="w-10 h-px bg-indigo-500/40"></span> Hub Assistant
                </h3>
                <p class="text-slate-500 text-xs leading-relaxed mb-10 font-bold uppercase tracking-[0.1em]">For critical tactical assistance or onsite physical review, initialize a direct service request at the nodal hub.</p>
                <a href="{{ route('customer.service.book') }}" class="w-full py-5 bg-white/5 border border-white/10 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] text-white text-center transition-all flex items-center justify-center gap-4 hover:bg-indigo-500 hover:border-indigo-400 hover:shadow-2xl hover:shadow-indigo-500/30">
                    <svg class="w-5 h-5 text-indigo-400 group-hover/support:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Book Repair Sequence
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function updateFileList(input) {
    const list = document.getElementById('file-list');
    list.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const div = document.createElement('div');
        div.className = 'flex items-center gap-4 text-[10px] text-slate-400 bg-slate-950/80 border border-white/5 rounded-2xl px-5 py-4 shadow-2xl animate-slide-up group/file';
        div.innerHTML = `<span class="text-xl group-hover/file:rotate-12 transition-transform">📎</span> <div class="flex-1 min-w-0"><p class="truncate font-black uppercase tracking-[0.2em] text-white mb-1">${file.name}</p><p class="text-[9px] text-indigo-400 font-bold tracking-widest uppercase">Payload: ${(file.size/1024).toFixed(0)}KB</p></div>`;
        list.appendChild(div);
    });
}
</script>
@endsection

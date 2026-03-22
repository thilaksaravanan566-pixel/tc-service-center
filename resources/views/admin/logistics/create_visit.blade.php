@extends('layouts.admin')

@section('title', 'Schedule Field Visit')

@section('content')
<div class="mb-10 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight italic">Provision Field Support</h1>
        <p class="text-gray-500 mt-2 font-medium">Assign a technician or team member for physical partner site verification.</p>
    </div>
    <a href="{{ route('admin.logistics.visits') }}" class="btn btn-secondary border border-gray-200 bg-white shadow-sm font-bold text-xs uppercase tracking-widest px-6 py-3 rounded-xl">
        <i class="fas fa-arrow-left mr-2"></i> Back
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2">
        <div class="card p-10 shadow-2xl border-0 bg-white relative overflow-hidden group">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
            <div class="relative">
                <form action="{{ route('admin.logistics.storeVisit') }}" method="POST">
                    @csrf
                    
                    <div class="mb-10 flex items-center gap-2 border-b border-indigo-50/50 pb-4">
                         <span class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-ping"></span>
                         <label class="block text-xs uppercase font-extrabold text-indigo-600 tracking-widest">Protocol Assignment Details</label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-3 tracking-widest">Target Partner Facility</label>
                            <select name="dealer_id" class="input w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-black appearance-none group hover:border-indigo-500/50 transition" required>
                                <option value="" disabled selected>Identify Partner...</option>
                                @foreach($dealers as $dealer)
                                <option value="{{ $dealer->id }}">{{ $dealer->business_name }} ({{ $dealer->user->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-3 tracking-widest">Field Personnel Ident</label>
                            <select name="assigned_to" class="input w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-black appearance-none group hover:border-indigo-500/50 transition" required>
                                <option value="" disabled selected>Authenticate Staff...</option>
                                @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}">{{ $tech->name }} ({{ $tech->role }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-3 tracking-widest">Scheduled Execution Date</label>
                            <input type="date" name="visit_date" class="input w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-black group hover:border-indigo-500/50 transition" required>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-3 tracking-widest">Operational Purpose</label>
                            <input type="text" name="purpose" class="input w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-black group hover:border-indigo-500/50 transition uppercase tracking-tighter" placeholder="e.g. SYSTEM AUDIT, COLLECTION, TRAINING">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] uppercase font-bold text-gray-400 mb-3 tracking-widest">Mission Directives / Briefing</label>
                            <textarea name="notes" rows="4" class="input w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-black group hover:border-indigo-500/50 transition" placeholder="Detailed technical directives for the personnel..."></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end pt-8 mt-10 border-t border-gray-100">
                        <button type="submit" class="btn btn-primary px-16 py-5 text-sm font-black italic uppercase tracking-widest rounded-2xl shadow-2xl shadow-indigo-600/30 hover:scale-105 active:scale-95 transition-all">
                            Finalize Briefing <i class="fas fa-check-circle ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-8">
        <div class="card p-10 bg-gray-900 text-white shadow-2xl border-0 rounded-[2.5rem] relative overflow-hidden group">
             <div class="absolute -right-6 -bottom-6 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl transition duration-1000 group-hover:scale-150"></div>
             <div class="relative">
                <h3 class="text-xs uppercase font-extrabold tracking-widest text-indigo-400 mb-8 border-b border-white/5 pb-4 leading-none">Technician Protocol</h3>
                <div class="space-y-8 leading-tight">
                    <div class="flex gap-5">
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/5 flex items-center justify-center shrink-0">
                            <i class="fas fa-mobile-alt text-lg text-indigo-400"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase text-indigo-50 italic tracking-widest leading-none mb-1">Mobile Access</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tight">Personnel must use the Tech App for GPS check-in upon arrival.</p>
                        </div>
                    </div>
                    <div class="flex gap-5">
                         <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/5 flex items-center justify-center shrink-0">
                            <i class="fas fa-camera text-lg text-indigo-400"></i>
                         </div>
                         <div>
                            <p class="text-xs font-black uppercase text-indigo-50 italic tracking-widest leading-none mb-1">Visual Verification</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tight">Check-out requires uploading real-time photos of the partner facility.</p>
                         </div>
                    </div>
                    <div class="flex gap-5">
                         <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/5 flex items-center justify-center shrink-0">
                            <i class="fas fa-file-signature text-lg text-indigo-400"></i>
                         </div>
                         <div>
                            <p class="text-xs font-black uppercase text-indigo-50 italic tracking-widest leading-none mb-1">On-Site Record</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tight">Mission notes are logged directly into the partner audit trail.</p>
                         </div>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>
@endsection

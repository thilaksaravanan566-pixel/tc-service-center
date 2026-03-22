@extends('layouts.customer')

@section('content')
<div class="w-full max-w-3xl mx-auto py-12 md:py-24 animate-fade-in-up relative z-10">
    <div class="text-center mb-10">
        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight mb-4 drop-shadow-md">Track Service Status</h1>
        <p class="text-lg text-gray-400 font-medium">Enter your TC Job ID to check the real-time progress of your device.</p>
    </div>

    <!-- Search Box -->
    <div class="glass-card p-8 md:p-12 rounded-[2.5rem] shadow-[0_8px_32px_rgba(0,0,0,0.5)] border border-white/5 relative overflow-hidden group">
        <!-- Decorative Glow -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-gold-400/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none group-hover:bg-gold-400/10 transition-all duration-700"></div>

        <form action="#" method="GET" class="relative z-10" id="tracking-form">
            <label class="text-xs font-black uppercase text-gold-400 tracking-widest block mb-3 ml-2 drop-shadow-sm">Job Reference Number</label>
            <div class="relative flex items-center">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <svg class="w-6 h-6 text-gold-500 drop-shadow-[0_0_5px_rgba(234,179,8,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" id="job_id" name="job_id" class="w-full glass-input bg-dark-900/50 border border-white/10 text-white text-lg rounded-full py-5 pl-16 pr-32 font-bold focus:bg-dark-800 focus:border-gold-500/50 focus:ring-1 focus:ring-gold-500/50 outline-none shadow-inner transition-all placeholder:text-gray-500" placeholder="e.g. TC-2026-A1B2" required>
                
                <button type="button" onclick="submitTracking()" class="absolute right-2 top-2 bottom-2 btn-gold hover:text-dark-900 font-black px-6 rounded-full text-sm uppercase tracking-widest shadow-[0_4px_15px_rgba(202,138,4,0.3)] transition-all flex items-center gap-2">
                    Trace
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>

        <!-- Script for Client Side Redirection to clean URL /track/ID -->
        <script>
            function submitTracking() {
                var jobId = document.getElementById('job_id').value.trim();
                if(jobId) {
                    window.location.href = "{{ route('tracking.index') }}/" + jobId;
                } else {
                    alert("Please enter a valid Job ID.");
                }
            }
            
            document.getElementById('tracking-form').addEventListener('submit', function(e){
                e.preventDefault();
                submitTracking();
            });
        </script>

        <!-- Common Support Text -->
        <div class="mt-8 text-center text-sm font-medium text-gray-500">
            <span class="inline-block relative">
                <span class="absolute -left-3 top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-green-400 shadow-[0_0_8px_rgba(74,222,128,0.8)] animate-pulse"></span>
                Need quick support? <a href="#" class="text-gold-400 hover:text-gold-300 hover:underline font-bold ml-1 transition-colors drop-shadow-sm">Contact Live Chat</a>
            </span>
        </div>
    </div>
</div>
@endsection

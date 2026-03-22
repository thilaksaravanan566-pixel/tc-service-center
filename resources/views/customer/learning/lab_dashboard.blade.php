@extends('layouts.customer')

@section('title', 'Thambu Tech Lab')

@section('content')
<div class="min-h-screen bg-[#020410] relative overflow-hidden rounded-3xl" x-data="techLabDashboard()">
    
    <!-- Animated Matrix/Cyber Background -->
    <div class="absolute inset-0 z-0 pointer-events-none opacity-40">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(0,198,255,0.1)_0%,transparent_80%)]"></div>
        <div class="cyber-grid absolute inset-0 text-[#00f2ff]/10"></div>
    </div>

    <style>
        .cyber-grid {
            background-image: 
                linear-gradient(rgba(0, 242, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 242, 255, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        .neon-glow-blue { box-shadow: 0 0 20px rgba(0, 242, 255, 0.4); }
        .neon-glow-purple { box-shadow: 0 0 20px rgba(188, 19, 254, 0.4); }
        .glass-panel {
            background: rgba(10, 15, 30, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 242, 255, 0.2);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }
        .holo-card {
            border: 1px solid rgba(0, 242, 255, 0.1);
            background: linear-gradient(135deg, rgba(0, 242, 255, 0.05), rgba(188, 19, 254, 0.05));
            transition: all 0.3s;
        }
        .holo-card:hover {
            border-color: rgba(0, 242, 255, 0.5);
            background: rgba(0, 242, 255, 0.1);
            transform: translateY(-5px);
        }
        .rank-badge {
            background: linear-gradient(135deg, #00f2ff, #bc13fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 5s ease-in-out infinite; }
    </style>

    <!-- UI Overlay -->
    <div class="relative z-10 p-6 lg:p-10">
        
        <!-- Header: Mission Control -->
        <div class="flex flex-wrap justify-between items-center mb-12">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-[#00f2ff]/10 border-2 border-[#00f2ff]/30 flex items-center justify-center neon-glow-blue">
                    <svg class="w-10 h-10 text-[#00f2ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <div>
                    <h1 class="text-4xl font-black text-white tracking-tighter uppercase italic">Thambu Tech <span class="text-[#00f2ff]">Lab</span></h1>
                    <p class="text-[10px] text-gray-500 font-mono tracking-widest uppercase mt-1">Status: Operational // Simulation Mode Delta</p>
                </div>
            </div>

            <!-- Global Stats (Holographic) -->
            <div class="flex gap-4">
                <div class="glass-panel px-6 py-3 rounded-2xl border-l-4 border-l-[#00f2ff]">
                    <div class="text-[9px] text-gray-500 uppercase tracking-widest font-bold">Technician Level</div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-black text-white" x-text="player.level">1</span>
                        <span class="text-[10px] bg-[#00f2ff]/20 text-[#00f2ff] px-2 rounded-full font-bold uppercase tracking-widest" x-text="getRankName(player.level)">Apprentice</span>
                    </div>
                </div>
                <div class="glass-panel px-6 py-3 rounded-2xl border-l-4 border-l-[#bc13fe]">
                    <div class="text-[9px] text-gray-500 uppercase tracking-widest font-bold">Experience XP</div>
                    <div class="text-2xl font-black text-white" x-text="player.xp">0</div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Modules -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Center Simulations Column -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Hero Simulation Entry -->
                <div class="glass-panel rounded-3xl overflow-hidden group relative">
                    <div class="h-64 bg-gradient-to-br from-[#00f2ff]/20 to-[#bc13fe]/20 relative">
                        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1591405351990-4726e331f141?q=80&w=1000')] bg-cover bg-center opacity-40 grayscale group-hover:grayscale-0 transition-all duration-700 scale-110 group-hover:scale-100"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#020410] via-transparent to-transparent"></div>
                        <div class="absolute bottom-8 left-8">
                            <h2 class="text-3xl font-black text-white tracking-tighter uppercase italic mb-2">3D Hardware Academy</h2>
                            <p class="text-sm text-gray-400 max-w-lg">Master the art of high-precision assembly in our fully immersive 3D simulation environment.</p>
                        </div>
                    </div>
                    <div class="p-8 flex flex-wrap gap-4">
                        <a href="{{ route('customer.tech-lab.builder') }}" class="btn-primary py-4 px-10 text-sm tracking-widest group-hover:neon-glow-blue transition-all">
                            Initialize Builder
                        </a>
                        <a href="{{ route('customer.tech-lab.repair') }}" class="btn-secondary py-4 px-10 text-sm tracking-widest hover:border-[#bc13fe] hover:text-[#bc13fe] transition-all">
                            Repair Simulation
                        </a>
                    </div>
                </div>

                <!-- Simulation Modules Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Byte Device Scanner -->
                    <div class="holo-card p-6 rounded-2xl border-t-2 border-t-[#00f2ff]/50 bg-gradient-to-br from-[#00f2ff]/5 to-transparent">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-[#00f2ff]/10 rounded-xl flex items-center justify-center text-[#00f2ff]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[10px] font-bold text-[#00f2ff] bg-[#00f2ff]/10 px-2 rounded-full uppercase tracking-tighter">AI Diagnostic Node</span>
                        </div>
                        <h3 class="text-lg font-black text-white uppercase italic tracking-tight mb-2">Byte Scanner</h3>
                        <p class="text-xs text-gray-500 mb-6 leading-relaxed">Scan hardware specifications and receive instant AI analysis and upgrade projections.</p>
                        <a href="{{ route('customer.tech-lab.scanner') }}" class="text-[#00f2ff] text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 group">
                            Explore Diagnostic Loop <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                    
                    <div class="holo-card p-6 rounded-2xl border-t-2 border-t-[#00f2ff]/50">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-[#00f2ff]/10 rounded-xl flex items-center justify-center text-[#00f2ff]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            </div>
                            <span class="text-[10px] font-bold text-[#00f2ff] bg-[#00f2ff]/10 px-2 rounded-full uppercase tracking-tighter">Diagnostic Tool</span>
                        </div>
                        <h3 class="text-lg font-black text-white uppercase italic tracking-tight mb-2">AI Troubleshooter</h3>
                        <p class="text-xs text-gray-500 mb-6 leading-relaxed">Solve dynamically generated hardware crises. Beat the clock and rescue the data.</p>
                        <a href="{{ route('customer.tech-lab.troubleshoot') }}" class="text-[#00f2ff] text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 group">
                            Launch Scenario <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>

                    <div class="holo-card p-6 rounded-2xl border-t-2 border-t-[#bc13fe]/50">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-[#bc13fe]/10 rounded-xl flex items-center justify-center text-[#bc13fe]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3c1.708 0 3.32.428 4.73 1.171m0 0A10.003 10.003 0 0112 21a9.003 9.003 0 01-5.041-1.554m10.771-1.554l-.054.09A10.003 10.003 0 0112 3"/></svg>
                            </div>
                            <span class="text-[10px] font-bold text-[#bc13fe] bg-[#bc13fe]/10 px-2 rounded-full uppercase tracking-tighter">Skill Trainer</span>
                        </div>
                        <h3 class="text-lg font-black text-white uppercase italic tracking-tight mb-2">Port Identificator</h3>
                        <p class="text-xs text-gray-500 mb-6 leading-relaxed">Identify motherboard interfaces at rapid speeds. Essential training for junior nodes.</p>
                        <a href="#" class="text-[#bc13fe] text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 group">
                            Begin Drill <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>

                    <div class="holo-card p-6 rounded-2xl border-t-2 border-t-[#ffcc00]/50 group">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-[#ffcc00]/10 rounded-xl flex items-center justify-center text-[#ffcc00] group-hover:neon-glow-gold transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <span class="text-[10px] font-bold text-[#ffcc00] bg-[#ffcc00]/10 px-2 rounded-full uppercase tracking-tighter">Business Sim</span>
                        </div>
                        <h3 class="text-lg font-black text-white uppercase italic tracking-tight mb-2">Repair Tycoon</h3>
                        <p class="text-xs text-gray-500 mb-6 leading-relaxed">Launch your tech empire. Build workbenches, hire interns, and scale to global dominance.</p>
                        <a href="{{ route('customer.tech-lab.tycoon') }}" class="text-[#ffcc00] text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 group">
                            Scale Empire <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>

                    <div class="holo-card p-6 rounded-2xl border-t-2 border-t-[#ff0055]/50 group">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-[#ff0055]/10 rounded-xl flex items-center justify-center text-[#ff0055] group-hover:neon-glow-red transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <span class="text-[10px] font-bold text-[#ff0055] bg-[#ff0055]/10 px-2 rounded-full uppercase tracking-tighter">Arcade Combat</span>
                        </div>
                        <h3 class="text-lg font-black text-white uppercase italic tracking-tight mb-2">Virus Smash</h3>
                        <p class="text-xs text-gray-500 mb-6 leading-relaxed">System critical! Intercept malware nodes and purge the kernel. High addictive risk.</p>
                        <a href="{{ route('customer.tech-lab.virus-smash') }}" class="text-[#ff0055] text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 group">
                            Neutralize Threats <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Side Intel Column -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Player Profile Hologram -->
                <div class="glass-panel p-8 rounded-3xl relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#00f2ff]/10 rounded-full blur-3xl"></div>
                    <h2 class="text-xs font-black text-gray-500 uppercase tracking-[0.3em] mb-8">Operator Profile</h2>
                    
                    <div class="flex flex-col items-center mb-8">
                        <div class="w-24 h-24 rounded-full border-2 border-[#00f2ff]/50 p-1 mb-4 group-hover:neon-glow-blue transition-all">
                             <div class="w-full h-full rounded-full bg-gradient-to-br from-[#00f2ff]/20 to-[#bc13fe]/20 flex items-center justify-center text-4xl">
                                🤖
                             </div>
                        </div>
                        <h3 class="text-xl font-black text-white uppercase tracking-tight">{{ explode(' ', $player->customer->name)[0] }}</h3>
                        <p class="text-[10px] text-[#00f2ff] font-bold uppercase tracking-widest mt-1">Status: Master Node</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">System Level Progress</span>
                            <span class="text-[10px] font-bold text-white uppercase" x-text="`${player.level} / 50`"></span>
                        </div>
                        <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden p-[1px]">
                            <div class="h-full bg-gradient-to-r from-[#00f2ff] to-[#bc13fe] rounded-full" :style="`width: ${Math.min(100, (player.xp / (Math.pow(player.level, 2) * 100)) * 100)}%`"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <div class="bg-black/30 p-4 rounded-2xl border border-white/5 text-center">
                            <p class="text-[8px] text-gray-500 mb-1 uppercase font-bold tracking-widest">Success Rate</p>
                            <span class="text-xl font-black text-white" x-text="`${player.success_rate || 0}%`"></span>
                        </div>
                        <div class="bg-black/30 p-4 rounded-2xl border border-white/5 text-center">
                            <p class="text-[8px] text-gray-500 mb-1 uppercase font-bold tracking-widest">Simulations</p>
                            <span class="text-xl font-black text-white" x-text="player.games_played">0</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Achievement Node -->
                <div class="glass-panel p-8 rounded-3xl">
                    <h2 class="text-xs font-black text-gray-500 uppercase tracking-[0.3em] mb-6">Achievement Node</h2>
                    <div class="space-y-4">
                        <template x-for="badge in badges" :key="badge.id">
                            <div class="flex items-center gap-4 p-3 bg-white/5 rounded-xl border border-white/5">
                                <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center text-xl" x-text="badge.icon"></div>
                                <div>
                                    <h4 class="text-xs font-bold text-white" x-text="badge.name"></h4>
                                    <p class="text-[9px] text-gray-500" x-text="badge.description"></p>
                                </div>
                            </div>
                        </template>
                        <div x-show="badges.length === 0" class="text-center py-6">
                           <p class="text-[10px] text-gray-600 font-bold uppercase tracking-widest">No achievements unlocked</p>
                           <p class="text-[8px] text-gray-700 mt-2 italic leading-relaxed">Complete simulations to earn specialized hardware badges.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Background Elements -->
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-[#00f2ff]/10 rounded-full blur-[100px] animate-pulse"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#bc13fe]/10 rounded-full blur-[100px] animate-pulse" style="animation-delay: 2s"></div>

    <script>
        function techLabDashboard() {
            return {
                player: @json($player),
                badges: @json($badges),
                
                getRankName(level) {
                    if (level < 5) return 'Apprentice';
                    if (level < 15) return 'Technician';
                    if (level < 30) return 'Specialist';
                    if (level < 45) return 'Hardware Master';
                    return 'Arch Technician';
                }
            }
        }
    </script>
</div>
@endsection

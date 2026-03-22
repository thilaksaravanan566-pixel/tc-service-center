@extends('layouts.customer')

@section('title', 'Laptop Repair Tycoon')

@section('content')
<div class="min-h-screen bg-[#020410] relative rounded-3xl overflow-hidden font-mono select-none" x-data="repairTycoon()">
    
    <!-- Background FX -->
    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_20%,rgba(0,198,255,0.15)_0%,transparent_70%)]"></div>
        <div class="cyber-lines absolute inset-0"></div>
    </div>

    <style>
        .cyber-lines {
            background-image: linear-gradient(rgba(0, 242, 255, 0.03) 1px, transparent 1px);
            background-size: 100% 40px;
        }
        .neon-border-blue { border: 1px solid rgba(0, 242, 255, 0.3); box-shadow: 0 0 15px rgba(0, 242, 255, 0.1); }
        .neon-border-gold { border: 1px solid rgba(255, 204, 0, 0.3); box-shadow: 0 0 15px rgba(255, 204, 0, 0.1); }
        .glass-dark { background: rgba(5, 10, 20, 0.8); backdrop-filter: blur(15px); }
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-customer { animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        
        .progress-bar { height: 6px; border-radius: 99px; background: rgba(255,255,255,0.05); overflow: hidden; }
        .progress-fill { height: 100%; transition: width 0.1s linear; }
    </style>

    <!-- UI: HUD -->
    <div class="relative z-10 p-6 lg:p-10 flex flex-col h-full min-h-screen">
        
        <!-- Top Bar -->
        <div class="flex flex-wrap justify-between items-start gap-6 mb-12">
            <div class="flex items-center gap-4">
                <a href="{{ route('customer.tech-lab.dashboard') }}" class="w-12 h-12 rounded-xl glass-dark border border-white/10 flex items-center justify-center text-gray-400 hover:text-[#00f2ff] transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-white italic uppercase tracking-tighter">Repair <span class="text-[#00f2ff]">Tycoon</span></h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest" x-text="`HQ LEVEL ${shopLevel}` text"></span>
                    </div>
                </div>
            </div>

            <!-- Currency & Global Stats -->
            <div class="flex gap-4">
                <div class="glass-dark px-6 py-3 rounded-2xl neon-border-gold">
                    <div class="text-[8px] text-gray-500 uppercase font-black tracking-widest">Available Credits</div>
                    <div class="flex items-center gap-2">
                        <span class="text-xl text-[#ffcc00] font-black italic">₿</span>
                        <span class="text-2xl font-black text-white tabular-nums" x-text="formatNumber(coins)">0</span>
                    </div>
                </div>
                <div class="glass-dark px-6 py-3 rounded-2xl neon-border-blue">
                    <div class="text-[8px] text-gray-500 uppercase font-black tracking-widest">Net Revenue / Sec</div>
                    <div class="text-2xl font-black text-[#00f2ff] tabular-nums" x-text="formatNumber(passiveIncome)">0.0</div>
                </div>
            </div>
        </div>

        <!-- Main Workspace -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 flex-grow">
            
            <!-- Left: Customer Queue & Active Jobs -->
            <div class="lg:col-span-8 flex flex-col">
                
                <!-- Active Stations -->
                <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 flex items-center gap-2">
                   <span class="w-2 h-2 rounded-full bg-[#00f2ff]"></span> Active Workbenches
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <template x-for="(station, index) in stations" :key="index">
                        <div class="glass-dark rounded-3xl p-6 border border-white/5 relative overflow-hidden group">
                            <div x-show="station.busy" class="absolute top-0 left-0 w-full h-1 bg-[#00f2ff]/20">
                                <div class="h-full bg-[#00f2ff] shadow-[0_0_10px_#00f2ff]" :style="`width: ${station.progress}%` text"></div>
                            </div>

                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-3xl" :class="station.busy ? 'animate-pulse' : 'opacity-20'">
                                        <span x-text="station.busy ? station.job.icon : '🛠️'"></span>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-white uppercase italic" x-text="station.busy ? station.job.name : 'Ready for Protocol'"></h4>
                                        <p class="text-[9px] text-gray-500 font-bold uppercase" x-text="station.busy ? `Serial: #TX-${station.job.id}` : 'Idle State'"></p>
                                    </div>
                                </div>
                                <div x-show="station.busy" class="text-right">
                                    <p class="text-[8px] text-gray-500 uppercase font-black">Gain</p>
                                    <p class="text-xs font-black text-[#ffcc00]" x-text="`₿${station.job.payout}` text"></p>
                                </div>
                            </div>

                            <!-- Action -->
                            <div class="mt-4">
                                <button x-show="!station.busy && customerQueue.length > 0" 
                                        @click="assignJob(index)"
                                        class="w-full py-3 rounded-xl bg-[#00f2ff]/10 text-[#00f2ff] border border-[#00f2ff]/20 text-[10px] font-black uppercase tracking-widest hover:bg-[#00f2ff] hover:text-black transition-all">
                                    Assign Incoming Unit
                                </button>
                                <div x-show="station.busy" class="flex items-center justify-between">
                                    <span class="text-[8px] text-gray-600 font-mono" x-text="`${Math.floor(station.progress)}%` text"></span>
                                    <span class="text-[8px] text-gray-600 uppercase font-bold tracking-widest" x-text="`${((station.duration * (1 - station.progress/100)) / 1000).toFixed(1)}s remaining` text"></span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Locked Station -->
                    <div x-show="stations.length < maxStationsPerLevel" 
                         class="rounded-3xl border-2 border-dashed border-white/5 flex flex-col items-center justify-center p-8 text-center glass-dark">
                         <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center text-gray-600 mb-4 italic font-black">?</div>
                         <h4 class="text-[10px] font-black text-gray-600 uppercase tracking-widest mb-4">Expansion Potential</h4>
                         <button @click="buyStation()" 
                                 :disabled="coins < stationCost"
                                 class="py-2 px-6 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all"
                                 :class="coins >= stationCost ? 'bg-[#00f2ff] text-black hover:scale-105' : 'bg-white/5 text-gray-600 cursor-not-allowed'">
                             Unlock (₿<span x-text="formatNumber(stationCost)"></span>)
                         </button>
                    </div>
                </div>

                <!-- Incoming Customer Queue -->
                <div class="mt-auto">
                    <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 flex items-center justify-between">
                        <span>Intake Registry</span>
                        <span class="text-[#00f2ff]" x-text="`${customerQueue.length} Units Pending` text"></span>
                    </h3>
                    <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
                        <template x-for="customer in customerQueue" :key="customer.id">
                            <div class="flex-shrink-0 w-48 glass-dark p-4 rounded-2xl border border-white/5 animate-customer">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-xl" x-text="customer.icon"></div>
                                    <div>
                                        <p class="text-[10px] text-white font-black truncate max-w-[100px]" x-text="customer.name"></p>
                                        <p class="text-[8px] text-[#ffcc00] font-bold" x-text="`₿${customer.payout}` text"></p>
                                    </div>
                                </div>
                                <div class="text-[8px] text-gray-500 uppercase font-black tracking-widest">Issue: <span class="text-gray-300" x-text="customer.issue"></span></div>
                            </div>
                        </template>
                        <div x-show="customerQueue.length === 0" class="w-full py-8 text-center glass-dark rounded-2xl border border-dashed border-white/5">
                            <p class="text-[10px] text-gray-600 font-black uppercase italic tracking-widest">Intake Clear // Awaiting Requests</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Upgrades & Staff -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                
                <!-- Upgrade Tabs -->
                <div class="glass-dark rounded-3xl p-6 border border-white/5 h-full">
                    <div class="flex gap-4 mb-8">
                        <button @click="activeTab = 'upgrades'" class="text-[10px] font-black uppercase tracking-widest pb-2 transition-all" :class="activeTab === 'upgrades' ? 'text-[#00f2ff] border-b-2 border-[#00f2ff]' : 'text-gray-500'">Tech Upgrades</button>
                        <button @click="activeTab = 'staff'" class="text-[10px] font-black uppercase tracking-widest pb-2 transition-all" :class="activeTab === 'staff' ? 'text-[#00f2ff] border-b-2 border-[#00f2ff]' : 'text-gray-500'">Hiring Node</button>
                    </div>

                    <!-- Upgrades List -->
                    <div x-show="activeTab === 'upgrades'" class="space-y-4">
                        <template x-for="(upg, key) in upgrades" :key="key">
                            <button @click="buyUpgrade(key)" 
                                    :disabled="coins < upg.cost"
                                    class="w-full group text-left p-4 rounded-2xl border transition-all"
                                    :class="coins >= upg.cost ? 'bg-white/5 border-white/10 hover:border-[#00f2ff]/50' : 'bg-black/40 border-white/5 opacity-50'">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-sm" x-text="upg.icon"></div>
                                        <div>
                                            <h5 class="text-[10px] font-black text-white uppercase tracking-tight" x-text="upg.name"></h5>
                                            <p class="text-[8px] text-gray-500 uppercase font-bold" x-text="`Tier ${upg.level}` text"></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-[#ffcc00]" x-text="`₿${formatNumber(upg.cost)}` text"></p>
                                    </div>
                                </div>
                                <p class="text-[9px] text-gray-400 leading-tight italic" x-text="upg.desc"></p>
                            </button>
                        </template>
                    </div>

                    <!-- Staff List -->
                    <div x-show="activeTab === 'staff'" class="space-y-4">
                         <template x-for="(member, key) in staff" :key="key">
                            <button @click="hireStaff(key)" 
                                    :disabled="coins < member.cost"
                                    class="w-full group text-left p-4 rounded-2xl border transition-all"
                                    :class="coins >= member.cost ? 'bg-white/5 border-white/10 hover:border-[#bc13fe]/50' : 'bg-black/40 border-white/5 opacity-50'">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-sm" x-text="member.icon"></div>
                                        <div>
                                            <h5 class="text-[10px] font-black text-white uppercase tracking-tight" x-text="member.name"></h5>
                                            <p class="text-[8px] text-[#bc13fe] font-bold uppercase" x-text="`${member.count} Active` text"></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-[#ffcc00]" x-text="`₿${formatNumber(member.cost)}` text"></p>
                                    </div>
                                </div>
                                <p class="text-[9px] text-gray-400 leading-tight italic" x-text="`Generates ₿${member.income}/sec passively.` text"></p>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Viral Sharing Card -->
                <div class="glass-dark p-6 rounded-3xl border border-[#00f2ff]/20 bg-gradient-to-br from-[#00f2ff]/5 to-transparent">
                    <h4 class="text-[10px] font-black text-white uppercase tracking-[0.2em] mb-4">Transmission Center</h4>
                    <p class="text-[10px] text-gray-500 mb-6 italic">Broadcast your tech empire's growth to the global neural network.</p>
                    <div class="flex gap-4">
                        <button @click="shareEmpire('whatsapp')" class="flex-grow py-3 rounded-xl bg-green-500/10 text-green-400 border border-green-500/20 text-[9px] font-black uppercase tracking-widest hover:bg-green-500 hover:text-white transition-all">WhatsApp</button>
                        <button @click="shareEmpire('twitter')" class="flex-grow py-3 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[9px] font-black uppercase tracking-widest hover:bg-blue-500 hover:text-white transition-all">Twitter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function repairTycoon() {
            return {
                coins: @json($player->coins),
                shopLevel: @json($player->level),
                xp: @json($player->xp),
                activeTab: 'upgrades',
                
                stations: [],
                customerQueue: [],
                nextJobId: 1,
                lastTick: Date.now(),
                saveTimer: 0,
                
                maxStationsPerLevel: 6,
                stationCost: 1000,
                
                upgrades: {
                    nanobots: { name: 'Surgical Nanobots', level: 1, cost: 500, icon: '🛰️', desc: 'Accelerate repair speed by 15% per tier.', factor: 0.15 },
                    flux: { name: 'Flux Capacitors', level: 1, cost: 1200, icon: '🌀', desc: 'Boost repair payouts by 20% per tier.', factor: 0.20 }
                },

                staff: {
                    junior: { name: 'Junior Intern', count: 0, cost: 200, income: 5, icon: '🧒' },
                    expert: { name: 'Tech Guru', count: 0, cost: 2000, income: 60, icon: '👴' },
                    ai: { name: 'Sentinel AI', count: 0, cost: 15000, income: 450, icon: '🤖' }
                },

                init() {
                    // Load state from DB or defaults
                    const saved = @json($player->tycoon_state);
                    if(saved) {
                        this.upgrades = saved.upgrades || this.upgrades;
                        this.staff = saved.staff || this.staff;
                        this.shopLevel = saved.shopLevel || this.shopLevel;
                        this.stationCost = saved.stationCost || 1000;
                        const stationCount = saved.stationCount || 1;
                        for(let i=0; i<stationCount; i++) this.stations.push({ busy: false, progress: 0, duration: 0, job: null });
                    } else {
                        this.stations.push({ busy: false, progress: 0, duration: 0, job: null });
                    }

                    // Start Game Ticks
                    setInterval(() => this.tick(), 100);
                },

                get passiveIncome() {
                    let total = 0;
                    Object.values(this.staff).forEach(m => total += (m.count * m.income));
                    return total;
                },

                tick() {
                    const now = Date.now();
                    const delta = now - this.lastTick;
                    this.lastTick = now;

                    // Add Passive Income
                    this.coins += (this.passiveIncome * (delta / 1000));

                    // Update Stations
                    const speedMultiplier = 1 + (this.upgrades.nanobots.level * this.upgrades.nanobots.factor);
                    this.stations.forEach(s => {
                        if (s.busy) {
                            s.progress += (100 / (s.duration / speedMultiplier)) * delta;
                            if (s.progress >= 100) {
                                this.coins += s.job.payout;
                                s.busy = false;
                                s.progress = 0;
                            }
                        }
                    });

                    // Customer Spawning
                    if (this.customerQueue.length < 5 && Math.random() < 0.005) {
                        this.spawnCustomer();
                    }

                    // Save Periodically
                    this.saveTimer += delta;
                    if(this.saveTimer > 10000) {
                        this.saveState();
                        this.saveTimer = 0;
                    }
                },

                spawnCustomer() {
                    const names = ['Elite Gamer', 'Data Hub', 'Corporate Node', 'Street Runner', 'Core Dev'];
                    const laptops = ['Razer-Blade X', 'Nitro 5', 'Legion Pro', 'Mac-Node M3', 'Alien-Core'];
                    const icons = ['💻', '🎮', '💡', '🔋', '🔌'];
                    const issues = ['Thermal Overload', 'Logic Failure', 'Cell Death', 'Liquid Intrusion', 'Port Jam'];
                    
                    const basePayout = 100 + (Math.random() * 200);
                    const multiplier = 1 + (this.upgrades.flux.level * this.upgrades.flux.factor);

                    this.customerQueue.push({
                        id: this.nextJobId++,
                        name: names[Math.floor(Math.random()*names.length)],
                        laptop: laptops[Math.floor(Math.random()*laptops.length)],
                        icon: icons[Math.floor(Math.random()*icons.length)],
                        issue: issues[Math.floor(Math.random()*issues.length)],
                        payout: Math.floor(basePayout * multiplier),
                        duration: 5000 + (Math.random() * 10000)
                    });
                },

                assignJob(index) {
                    if (this.customerQueue.length === 0) return;
                    const job = this.customerQueue.shift();
                    this.stations[index].busy = true;
                    this.stations[index].job = job;
                    this.stations[index].duration = job.duration;
                    this.stations[index].progress = 0;
                },

                buyStation() {
                    if (this.coins >= this.stationCost) {
                        this.coins -= this.stationCost;
                        this.stations.push({ busy: false, progress: 0, duration: 0, job: null });
                        this.stationCost = Math.floor(this.stationCost * 2.5);
                    }
                },

                buyUpgrade(key) {
                    const upg = this.upgrades[key];
                    if (this.coins >= upg.cost) {
                        this.coins -= upg.cost;
                        upg.level++;
                        upg.cost = Math.floor(upg.cost * 1.8);
                    }
                },

                hireStaff(key) {
                    const member = this.staff[key];
                    if (this.coins >= member.cost) {
                        this.coins -= member.cost;
                        member.count++;
                        member.cost = Math.floor(member.cost * 1.6);
                    }
                },

                formatNumber(n) {
                    if (n >= 1000000) return (n / 1000000).toFixed(2) + 'M';
                    if (n >= 1000) return (n / 1000).toFixed(1) + 'K';
                    return Math.floor(n).toLocaleString();
                },

                saveState() {
                    const payload = {
                        coins: Math.floor(this.coins),
                        tycoon_state: {
                            upgrades: this.upgrades,
                            staff: this.staff,
                            shopLevel: this.shopLevel,
                            stationCost: this.stationCost,
                            stationCount: this.stations.length
                        }
                    };
                    fetch("{{ route('customer.tech-lab.save-tycoon') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(payload)
                    });
                },

                shareEmpire(platform) {
                    const text = `I just reached ₿${this.formatNumber(this.coins)} net worth in Thambu Laptop Repair Tycoon! Come run your own tech empire: ${window.location.href}`;
                    const url = platform === 'whatsapp' 
                        ? `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`
                        : `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}`;
                    window.open(url, '_blank');
                }
            }
        }
    </script>
</div>
@endsection

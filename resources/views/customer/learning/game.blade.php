@extends('layouts.customer')

@section('title', 'PC Hardware Builder Game')

@section('content')
<div x-data="pcBuilder()" class="min-h-screen bg-[#0a0a0f] text-white p-4 sm:p-8 rounded-3xl overflow-hidden relative" :class="{'matrix-bg': true}">
    <!-- Custom Matrix CSS -->
    <style>
        .matrix-bg {
            background-color: #05050a;
            background-image: 
                radial-gradient(circle at 50% 50%, rgba(79, 70, 229, 0.1) 0%, transparent 80%),
                linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%),
                linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            background-size: 100% 100%, 100% 2px, 3px 100%;
        }
        .cyber-card {
            background: rgba(15, 15, 25, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(79, 70, 229, 0.3);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5), inset 0 0 10px rgba(79, 70, 229, 0.1);
        }
        .neon-blue { color: #00f2ff; text-shadow: 0 0 10px rgba(0, 242, 255, 0.5); }
        .neon-purple { color: #bc13fe; text-shadow: 0 0 10px rgba(188, 19, 254, 0.5); }
        .neon-border-blue { border-color: #00f2ff; box-shadow: 0 0 10px rgba(0, 242, 255, 0.3); }
        .neon-border-purple { border-color: #bc13fe; box-shadow: 0 0 10px rgba(188, 19, 254, 0.3); }
        
        .part-slot {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: absolute;
            background: rgba(255, 255, 255, 0.03);
            border: 2px dashed rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .part-slot:hover {
            background: rgba(79, 70, 229, 0.1);
            border-color: rgba(79, 70, 229, 0.5);
        }
        .part-slot.highlight {
            background: rgba(0, 242, 255, 0.2);
            border-color: #00f2ff;
            box-shadow: 0 0 15px rgba(0, 242, 255, 0.4);
        }
        .part-slot.correct {
            border-style: solid;
            background: rgba(16, 185, 129, 0.1);
            border-color: #10b981;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
        }
        .part-slot.wrong {
            border-style: solid;
            background: rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        .tool-part {
            cursor: grab;
            transition: all 0.2s;
        }
        .tool-part:active { cursor: grabbing; scale: 0.95; }
        
        /* Custom Scrollbar */
        .cyber-scroll::-webkit-scrollbar { width: 5px; }
        .cyber-scroll::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        .cyber-scroll::-webkit-scrollbar-thumb { background: #4f46e5; border-radius: 10px; }

        .glitch-text {
            animation: glitch 1s linear infinite;
        }
        @keyframes glitch {
            2%, 64% { transform: translate(2px,0) skew(0deg); }
            4%, 60% { transform: translate(-2px,0) skew(0deg); }
            62% { transform: translate(0,0) skew(5deg); }
        }
    </style>

    <!-- UI Overlay: Top Bar -->
    <div class="flex flex-wrap justify-between items-center mb-8 relative z-20">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-[0_0_20px_rgba(79,70,229,0.5)]">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-black neon-blue tracking-widest uppercase">Hardware Builder</h1>
                <p class="text-xs text-indigo-300 font-mono tracking-tighter">SIMULATION_VERSION: 2.0.4.TC</p>
            </div>
        </div>

        <div class="flex gap-6 items-center">
            <div class="flex flex-col items-end">
                <span class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Time Elapsed</span>
                <span class="text-xl font-mono text-white" x-text="formatTime(timer)">00:00</span>
            </div>
            <div class="flex flex-col items-end">
                <span class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Score Pool</span>
                <span class="text-2xl font-mono neon-purple" x-text="score">0</span>
            </div>
            <div class="h-10 w-[1px] bg-white/10 mx-2"></div>
            <button @click="resetGame()" class="p-2 rounded-lg bg-red-500/10 text-red-400 border border-red-500/30 hover:bg-red-500/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>
    </div>

    <!-- Main Game Area -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-[calc(100vh-250px)] min-h-[600px] relative z-10">
        
        <!-- Toolbox (Left) -->
        <div class="lg:col-span-3 cyber-card rounded-2xl p-4 flex flex-col h-full overflow-hidden">
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-white/10">
                <h2 class="font-bold text-sm tracking-widest uppercase text-gray-400 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Toolbox
                </h2>
                <span class="text-[10px] bg-white/5 px-2 py-0.5 rounded text-gray-500" x-text="`${availableParts.length} Parts Available` text"></span>
            </div>

            <div class="flex-grow overflow-y-auto cyber-scroll space-y-3 pr-2">
                <template x-for="part in availableParts" :key="part.id">
                    <div 
                        draggable="true" 
                        @dragstart="onDragStart($event, part)"
                        class="tool-part group cyber-card bg-white/5 border-white/5 rounded-xl p-3 hover:border-indigo-500/50 hover:bg-indigo-500/5 transition-all relative overflow-hidden"
                    >
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-12 h-12 rounded-lg bg-black/40 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform" x-text="part.icon"></div>
                            <div>
                                <h3 class="font-bold text-xs uppercase" x-text="part.name"></h3>
                                <p class="text-[10px] text-gray-500" x-text="part.category"></p>
                            </div>
                        </div>
                        <!-- Progress bar mock -->
                        <div class="mt-2 h-1 w-full bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 opacity-50" style="width: 40%"></div>
                        </div>
                    </div>
                </template>

                <div x-show="availableParts.length === 0" class="h-full flex flex-col items-center justify-center text-center opacity-40">
                    <div class="text-4xl mb-2">📦</div>
                    <p class="text-xs uppercase tracking-widest font-bold">Toolbox Empty</p>
                    <p class="text-[10px] leading-relaxed mt-2">All components have been<br>moved to the assembly bay.</p>
                </div>
            </div>
        </div>

        <!-- Assembly Bay (Center) -->
        <div class="lg:col-span-6 cyber-card rounded-2xl p-6 flex flex-col relative overflow-hidden bg-black/40">
            <!-- Background Grid -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>

            <!-- Motherboard Visual Container -->
            <div class="flex-grow relative flex items-center justify-center p-4">
                <div class="relative w-[500px] h-[500px] bg-[#1a1a2e] border-2 border-[#2b2b4d] rounded-2xl shadow-2xl p-4 overflow-hidden" 
                     id="motherboard-target"
                >
                    <!-- PC Motherboard Traces (Visual) -->
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/circuit-board.png')]"></div>
                    
                    <!-- Motherboard Label -->
                    <div class="absolute top-4 right-4 text-[10px] text-gray-600 font-mono text-right">
                        THAMBU_COMPUTERS_MOD_V3<br>
                        SN: 8849-0012-X
                    </div>

                    <!-- Drop Slots -->
                    <template x-for="slot in slots" :key="slot.id">
                        <div 
                            class="part-slot rounded-lg group"
                            :class="{ 
                                'correct': placedParts[slot.id], 
                                'wrong': wrongFlash === slot.id,
                                'highlight': draggingPart && draggingPart.targetSlot === slot.id 
                            }"
                            :style="`top: ${slot.top}; left: ${slot.left}; width: ${slot.width}; height: ${slot.height};`"
                            @dragover.prevent=""
                            @drop.stop="onSlotDrop($event, slot.id)"
                            @click="showPartDetails(slot.id)"
                        >
                            <span x-show="!placedParts[slot.id]" class="text-[8px] uppercase tracking-tighter text-gray-500 font-bold group-hover:text-indigo-400" x-text="slot.label"></span>
                            
                            <!-- Placed Part Content -->
                            <div x-show="placedParts[slot.id]" class="w-full h-full flex flex-col items-center justify-center transition-all animate-pulse-slow">
                                <span class="text-2xl" x-text="slots.find(s => s.id === slot.id).icon"></span>
                                <span class="text-[8px] font-bold text-green-400 mt-1 uppercase" x-text="placedParts[slot.id].name"></span>
                            </div>

                            <!-- Glow effect for correct placement -->
                            <div x-show="placedParts[slot.id]" class="absolute inset-0 bg-green-500/10 blur-xl"></div>
                        </div>
                    </template>

                    <!-- CPU Socket Cooler Overlay (Placed after CPU) -->
                    <div x-show="placedParts['cpu_cooler']" 
                         class="absolute z-10 pointer-events-none transition-all duration-500"
                         style="top: 15%; left: 35%; width: 140px; height: 140px;"
                    >
                         <div class="w-full h-full rounded-full border-4 border-indigo-500/30 flex items-center justify-center bg-black/60 shadow-2xl backdrop-blur-sm">
                             <div class="w-24 h-24 border-2 border-indigo-400 animate-spin-slow rounded-full flex items-center justify-center">
                                 <div class="w-5 h-20 bg-indigo-500/40 rounded-full"></div>
                                 <div class="w-5 h-20 bg-indigo-500/40 rounded-full rotate-90 absolute"></div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex justify-center gap-4">
                <button @click="useHint()" :disabled="hints <= 0 || gameOver" class="px-6 py-2 rounded-xl bg-orange-500/10 text-orange-400 border border-orange-500/30 hover:bg-orange-500/20 disabled:opacity-30 disabled:grayscale transition-all text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    Use Hint (<span x-text="hints"></span>)
                </button>
            </div>
        </div>

        <!-- System Status (Right) -->
        <div class="lg:col-span-3 flex flex-col gap-6">
            
            <!-- Component Info Card -->
            <div class="cyber-card rounded-2xl p-5 flex flex-col min-h-[300px]">
                <h2 class="font-bold text-sm tracking-widest uppercase text-gray-400 mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span> Component Intel
                </h2>

                <div x-show="!inspectedPart" class="flex-grow flex flex-col items-center justify-center text-center opacity-30">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-[10px] uppercase font-bold tracking-widest leading-relaxed">System Idle<br>Select a part for intel</p>
                </div>

                <div x-show="inspectedPart" class="animate-slide-up">
                    <div class="mb-4">
                        <span class="text-[10px] text-indigo-400 font-bold uppercase" x-text="inspectedPart.category"></span>
                        <h3 class="text-xl font-black text-white" x-text="inspectedPart.name"></h3>
                    </div>
                    <div class="space-y-4">
                        <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                            <p class="text-[11px] text-gray-300 leading-relaxed font-medium" x-text="inspectedPart.description"></p>
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Tech Specs</h4>
                            <div class="grid grid-cols-2 gap-2 text-[10px] font-mono">
                                <div class="bg-black/30 p-2 rounded border border-white/5">
                                    <span class="text-gray-500">Power:</span> <span class="text-white" x-text="inspectedPart.power || 'N/A'"></span>
                                </div>
                                <div class="bg-black/30 p-2 rounded border border-white/5">
                                    <span class="text-gray-500">Temp:</span> <span class="text-green-400" x-text="inspectedPart.temp || '32°C'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaderboard Mini -->
            <div class="cyber-card rounded-2xl p-5 flex-grow bg-gradient-to-tr from-black/80 to-[#12121f]">
                <h2 class="font-bold text-sm tracking-widest uppercase text-gray-400 mb-4 flex items-center justify-between">
                    <span>Rankings</span>
                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-indigo-500/10 border border-indigo-500/30">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-indigo-400">01</span>
                            <span class="text-xs font-bold uppercase tracking-tighter">CyberTech_99</span>
                        </div>
                        <span class="text-xs font-mono">14,200</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-white/5">
                        <div class="flex items-center gap-3 text-gray-400">
                            <span class="font-mono">02</span>
                            <span class="text-xs font-bold uppercase tracking-tighter">TC_Technician_B</span>
                        </div>
                        <span class="text-xs font-mono">12,550</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Win Modal -->
    <div x-show="gameOver" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" x-transition>
        <div class="cyber-card w-full max-w-md rounded-3xl p-8 border-2 neon-border-blue text-center overflow-hidden relative">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-purple-500 rounded-full blur-3xl opacity-20"></div>
            
            <div class="relative z-10">
                <div class="text-6xl mb-4 animate-bounce">🏆</div>
                <h2 class="text-3xl font-black neon-blue uppercase mb-2">Build Success!</h2>
                <p class="text-gray-400 text-sm mb-6">System BIOS initializing... All hardware detected and operational.</p>
                
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Final Score</div>
                        <div class="text-2xl font-mono text-white" x-text="score"></div>
                    </div>
                    <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Total Time</div>
                        <div class="text-2xl font-mono text-white" x-text="formatTime(timer)"></div>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest mb-4">Rank Achievement</div>
                    <div class="flex justify-center gap-4">
                         <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-yellow-400 to-orange-600 p-[2px] shadow-[0_0_15px_rgba(234,179,8,0.4)]">
                             <div class="w-full h-full bg-[#0a0a0f] rounded-[14px] flex flex-col items-center justify-center">
                                 <span class="text-xl">🥇</span>
                                 <span class="text-[7px] font-bold text-yellow-500 tracking-tighter">HARDWARE MASTER</span>
                             </div>
                         </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button @click="resetGame()" class="btn-primary w-full py-4 text-sm tracking-widest uppercase">Start New Assembly</button>
                    <a href="{{ route('customer.learning.index') }}" class="text-gray-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors py-2">Return to Learning Center</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Part Feedback Sound Simulation (Visual only for now) -->
    <div x-show="showPulse" 
         class="fixed inset-0 pointer-events-none z-[60] border-[20px]"
         :class="pulseType === 'correct' ? 'border-green-500/20' : 'border-red-500/20'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-110"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
    ></div>

    <script>
        function pcBuilder() {
            return {
                score: 0,
                timer: 0,
                timerInterval: null,
                gameOver: false,
                draggingPart: null,
                inspectedPart: null,
                hints: 3,
                wrongFlash: null,
                showPulse: false,
                pulseType: 'correct',
                
                // Final state
                placedParts: {},

                // Components Master Data
                parts: [
                    { id: 'cpu', name: 'Intel Core i9-13900K', category: 'Processor', icon: '🧠', targetSlot: 'cpu_slot', description: 'The brain of your computer. Connects to the central socket on the motherboard.', power: '125W', order: 1 },
                    { id: 'cooler', name: 'Liquid Cooler AIO 360', category: 'Cooling', icon: '❄️', targetSlot: 'cpu_cooler', description: 'Keeps the CPU from melting. Must be placed directly on top of the CPU.', power: '15W', order: 2, required: 'cpu' },
                    { id: 'ram', name: '32GB DDR5 6000MHz', category: 'Memory', icon: '📏', targetSlot: 'ram_slot', description: 'High-speed temporary storage. Slots into the vertical lanes next to the CPU.', power: '10W', order: 3 },
                    { id: 'ssd', name: '2TB NVMe Gen4 SSD', category: 'Storage', icon: '💾', targetSlot: 'ssd_slot', description: 'Ultra-fast storage module. Small stick that plugs into the M.2 socket.', power: '5W', order: 4 },
                    { id: 'gpu', name: 'RTX 4090 OC', category: 'Graphics', icon: '🎮', targetSlot: 'gpu_slot', description: 'The powerhouse for visuals. Heaviest part that goes into the long PCIe slot.', power: '450W', order: 5 },
                    { id: 'psu', name: '1000W Titanium PSU', category: 'Power', icon: '⚡', targetSlot: 'psu_slot', description: 'Supplies power to all components. Usually sits at the bottom of the case.', power: 'In: 240V', order: 6 },
                ],

                // Motherboard Slots Mapping
                slots: [
                    { id: 'cpu_slot', label: 'CPU Socket', top: '15%', left: '35%', width: '140px', height: '140px', icon: '🧠' },
                    { id: 'cpu_cooler', label: 'Cooler Mount', top: '13%', left: '33%', width: '160px', height: '160px', icon: '❄️' },
                    { id: 'ram_slot', label: 'RAM DIMM Slots', top: '15%', left: '68%', width: '80px', height: '220px', icon: '📏' },
                    { id: 'ssd_slot', label: 'M.2 NVMe Slot', top: '55%', left: '38%', width: '120px', height: '40px', icon: '💾' },
                    { id: 'gpu_slot', label: 'PCIe x16 Slot', top: '70%', left: '15%', width: '400px', height: '40px', icon: '🎮' },
                    { id: 'psu_slot', label: 'Power Bay', top: '85%', left: '65%', width: '150px', height: '100px', icon: '⚡' },
                ],

                get availableParts() {
                    return this.parts.filter(p => !this.placedParts[p.targetSlot]);
                },

                init() {
                    this.startTimer();
                },

                startTimer() {
                    this.timerInterval = setInterval(() => {
                        this.timer++;
                    }, 1000);
                },

                formatTime(seconds) {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                },

                onDragStart(e, part) {
                    this.draggingPart = part;
                    // Transfer the part ID
                    e.dataTransfer.setData('partId', part.id);
                    // Create a small preview
                    const ghost = e.target.cloneNode(true);
                    ghost.style.position = "absolute";
                    ghost.style.top = "-1000px";
                    document.body.appendChild(ghost);
                    e.dataTransfer.setDragImage(ghost, 25, 25);
                    setTimeout(() => document.body.removeChild(ghost), 0);
                    
                    this.showPartDetails(null, part);
                },

                onSlotDrop(e, slotId) {
                    if (!this.draggingPart) return;

                    if (slotId === this.draggingPart.targetSlot) {
                        this.placePart(this.draggingPart, slotId);
                    } else {
                        this.triggerWrong(slotId);
                    }
                    
                    this.draggingPart = null;
                },

                placePart(part, slotId) {
                    // Check if required part is placed (e.g., CPU before Cooler)
                    if (part.required && !this.placedParts[this.parts.find(p => p.id === part.required).targetSlot]) {
                        this.triggerWrong(slotId);
                        this.inspectedPart = {
                            name: 'Placement Error',
                            category: 'SYSTEM ALERT',
                            description: `You must install the ${part.required.toUpperCase()} before attaching the ${part.name.toUpperCase()}.`,
                            power: '0W'
                        };
                        return;
                    }

                    this.placedParts[slotId] = part;
                    this.score += 500 + Math.max(0, 500 - (this.timer * 2)); // Dynamic score based on time
                    this.triggerPulse('correct');
                    this.inspectedPart = part;

                    // Check win condition
                    if (Object.keys(this.placedParts).length === this.parts.length) {
                        this.win();
                    }
                },

                triggerWrong(slotId) {
                    this.wrongFlash = slotId;
                    this.triggerPulse('wrong');
                    this.score = Math.max(0, this.score - 50);
                    setTimeout(() => { this.wrongFlash = null; }, 500);
                },

                triggerPulse(type) {
                    this.pulseType = type;
                    this.showPulse = true;
                    setTimeout(() => { this.showPulse = false; }, 400);
                },

                useHint() {
                    if (this.hints <= 0) return;
                    this.hints--;
                    
                    // Highlight the next required part and its slot
                    const nextPart = this.parts.find(p => !this.placedParts[p.targetSlot]);
                    if (nextPart) {
                        this.inspectedPart = {
                            name: 'TC HINT SYSTEM',
                            category: 'GUIDANCE',
                            description: `Target the ${nextPart.targetSlot.replace('_', ' ').toUpperCase()} with the ${nextPart.name.toUpperCase()}.`,
                            power: 'HINT_ACTIVE'
                        };
                    }
                },

                showPartDetails(slotId, partObject = null) {
                    if (partObject) {
                        this.inspectedPart = partObject;
                        return;
                    }
                    if (slotId && this.placedParts[slotId]) {
                        this.inspectedPart = this.placedParts[slotId];
                    }
                },

                win() {
                    clearInterval(this.timerInterval);
                    setTimeout(() => {
                        this.gameOver = true;
                    }, 1000);
                },

                resetGame() {
                    this.score = 0;
                    this.timer = 0;
                    this.gameOver = false;
                    this.placedParts = {};
                    this.inspectedPart = null;
                    this.hints = 3;
                    clearInterval(this.timerInterval);
                    this.startTimer();
                }
            };
        }
    </script>
</div>
@endsection

@extends('layouts.customer')

@section('title', 'Byte Device Scanner')

@section('content')
<div class="min-h-screen bg-[#020617] relative rounded-3xl overflow-hidden font-outfit select-none" x-data="byteScanner()">
    
    <!-- Cyber Background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_20%,rgba(0,198,255,0.1),transparent_70%)]"></div>
        <div class="circuit-grid absolute inset-0 opacity-20"></div>
    </div>

    <style>
        .circuit-grid {
            background-image: 
                linear-gradient(rgba(0, 198, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 198, 255, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        .glass-dark {
            background: rgba(10, 20, 40, 0.7);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(0, 198, 255, 0.15);
        }
        .box-glow {
            box-shadow: 0 0 30px rgba(0, 198, 255, 0.1);
        }
        .scan-line {
            height: 2px;
            background: linear-gradient(90deg, transparent, #00f2ff, transparent);
            box-shadow: 0 0 15px #00f2ff;
            position: absolute;
            left: 0; right: 0;
            z-index: 20;
        }
        @keyframes pulse-border {
            0% { border-color: rgba(0, 198, 255, 0.15); }
            50% { border-color: rgba(0, 198, 255, 0.5); }
            100% { border-color: rgba(0, 198, 255, 0.15); }
        }
        .analyzing { animation: pulse-border 1.5s infinite; }
    </style>

    <div class="relative z-10 p-6 lg:p-12">
        <!-- Top HUD -->
        <div class="flex justify-between items-start mb-16">
            <div>
                <h1 class="text-3xl font-black text-white italic uppercase tracking-tighter">Byte <span class="text-[#00f2ff]">Scanner</span></h1>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-2 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#00f2ff] animate-ping"></span>
                    AI Diagnostic Node v2.0
                </p>
            </div>
            <a href="{{ route('customer.tech-lab.dashboard') }}" class="p-4 glass-dark rounded-2xl hover:text-[#00f2ff] transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            
            <!-- Left: Device Input Form -->
            <div class="space-y-8" x-show="step === 'input'" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8">
                <div class="glass-dark p-8 rounded-[2.5rem] box-glow">
                    <h2 class="text-xl font-black text-white italic uppercase tracking-tight mb-8">Hardware Registry</h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block">Brand</label>
                                <input type="text" x-model="form.brand" placeholder="e.g. Dell" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-[#00f2ff] outline-none transition-all">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block">Model</label>
                                <input type="text" x-model="form.model" placeholder="e.g. Inspiron 15" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-[#00f2ff] outline-none transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block">RAM (GB)</label>
                                <input type="number" x-model="form.ram" placeholder="8" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-[#00f2ff] outline-none transition-all">
                            </div>
                            <div>
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block">Storage Type</label>
                                <select x-model="form.storage" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-[#00f2ff] outline-none transition-all">
                                    <option value="HDD">Mechanical (HDD)</option>
                                    <option value="SSD">Flash (SSD/NVMe)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block">Anomaly Description</label>
                            <textarea x-model="form.problem" rows="3" placeholder="Describe the behavior..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:border-[#00f2ff] outline-none transition-all resize-none"></textarea>
                        </div>

                        <button @click="startScan()" :disabled="!isFormValid" class="w-full py-4 bg-[#00f2ff] text-black rounded-2xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all shadow-[0_0_20px_rgba(0,242,255,0.3)] disabled:opacity-30 disabled:hover:scale-100">
                            Initialize Diagnostic Scan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Scanning State -->
            <div class="lg:col-span-1 flex flex-col items-center justify-center py-20" x-show="step === 'scanning'" x-transition:enter="transition opacity-100 duration-500">
                <div class="w-64 h-80 glass-dark rounded-3xl relative overflow-hidden flex items-center justify-center border-2 border-[#00f2ff]/30 analyzing">
                    <div class="scan-line" id="scan-line"></div>
                    <div class="text-[#00f2ff] opacity-20 animate-pulse text-6xl">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <!-- Isometric Cube placeholder -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-40">
                         <div class="w-32 h-32 border border-[#00f2ff] rotate-45 animate-spin duration-[10s]"></div>
                         <div class="w-24 h-24 border border-[#bc13fe] -rotate-45 absolute animate-spin duration-[8s]"></div>
                    </div>
                </div>
                <h3 class="text-xl font-black text-white italic uppercase tracking-widest mt-12">Byte is analyzing...</h3>
                <p class="text-[9px] text-[#00f2ff] font-bold mt-2 uppercase tracking-widest">Cross-referencing global hardware database</p>
            </div>

            <!-- Right: Results Panel -->
            <div class="lg:col-span-1 space-y-6" x-show="step === 'results'" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 translate-x-8">
                
                <!-- AI Insight -->
                <div class="glass-dark p-8 rounded-[2.5rem] bg-gradient-to-br from-[#00f2ff]/5 to-transparent">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#00f2ff]/10 flex items-center justify-center text-2xl">🧠</div>
                        <div>
                            <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest">Byte Analysis Outcome</h4>
                            <p class="text-lg font-black text-white italic tracking-tight">Diagnosis: Critical Efficiency Gap</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <template x-for="line in analysisData.analysis" :key="line">
                            <div class="flex items-start gap-3 p-4 bg-white/5 rounded-2xl border border-white/5">
                                <span class="text-[#00f2ff] mt-1">●</span>
                                <p class="text-xs text-gray-300 leading-relaxed font-bold italic" x-text="line"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Recommendations -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="rec in analysisData.recommendations" :key="rec.type">
                        <div class="glass-dark p-6 rounded-3xl border-t-2 border-t-[#bc13fe]/40">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-2xl" x-text="rec.icon"></span>
                                <h5 class="text-[11px] font-black text-white uppercase tracking-widest" x-text="rec.type"></h5>
                            </div>
                            <p class="text-[9px] text-[#bc13fe] font-black uppercase tracking-widest" x-text="rec.benefit"></p>
                        </div>
                    </template>
                </div>

                <!-- Estimated Costs -->
                <div class="glass-dark p-6 rounded-3xl">
                    <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4">Projected Operational Costs</h4>
                    <div class="space-y-3">
                        <template x-for="cost in analysisData.costs" :key="cost.item">
                            <div class="flex justify-between items-center py-2 border-b border-white/5">
                                <span class="text-[10px] text-white font-bold italic" x-text="cost.item"></span>
                                <span class="text-[11px] text-[#00f2ff] font-black">₹<span x-text="cost.price"></span></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <a href="{{ route('customer.service.book') }}" class="flex-grow py-4 bg-[#00f2ff] text-black rounded-2xl font-black text-[10px] uppercase tracking-widest text-center hover:scale-105 transition-all shadow-[0_0_20px_rgba(0,242,255,0.3)]">
                        Initialize Booking
                    </a>
                    <button @click="reset()" class="flex-grow py-4 glass-dark text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white/5 transition-all">
                        New Scan
                    </button>
                </div>
            </div>

            <!-- Decorative AI Avatar -->
            <div class="hidden lg:flex flex-col items-center justify-center p-20" x-show="step === 'input'">
                <div class="w-64 h-64 rounded-full glass-dark relative flex items-center justify-center group">
                    <div class="absolute inset-0 rounded-full border-2 border-[#00f2ff]/20 animate-spin duration-[15s]"></div>
                    <div class="absolute inset-4 rounded-full border border-[#bc13fe]/30 animate-spin duration-[10s] direction-reverse"></div>
                    <div class="text-8xl animate-bounce">🤖</div>
                    
                    <!-- Floating Data Bubbles -->
                    <div class="absolute -top-4 -right-4 w-12 h-12 glass-dark rounded-xl flex items-center justify-center text-xl animate-pulse">💾</div>
                    <div class="absolute -bottom-4 -left-4 w-12 h-12 glass-dark rounded-xl flex items-center justify-center text-xl animate-pulse delay-700">📶</div>
                </div>
                <div class="mt-12 text-center max-w-xs">
                    <h3 class="text-white font-black uppercase italic tracking-tighter text-xl">Byte Diagnostic Node</h3>
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-2 leading-relaxed">System-ready for zero-latency hardware analysis and lifecycle projections.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Voice Visualizer -->
    <div x-show="isSpeaking" class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50 flex items-center gap-1 group">
        <template x-for="i in Array(12).keys()">
             <div class="w-1 bg-[#00f2ff] rounded-full animate-bounce" :style="`height: ${10 + Math.random()*30}px; animation-duration: ${0.5 + Math.random()}s`"></div>
        </template>
        <span class="ml-4 text-[9px] font-black text-[#00f2ff] uppercase tracking-[0.3em]">Byte is speaking...</span>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script>
        function byteScanner() {
            return {
                step: 'input',
                form: {
                    brand: '',
                    model: '',
                    ram: '',
                    storage: 'HDD',
                    problem: ''
                },
                analysisData: {
                    analysis: [],
                    recommendations: [],
                    costs: []
                },
                isSpeaking: false,
                
                get isFormValid() {
                    return this.form.brand && this.form.model && this.form.ram && this.form.problem;
                },

                startScan() {
                    this.step = 'scanning';
                    
                    // Animate scan line
                    gsap.to("#scan-line", {
                        top: '100%',
                        duration: 1.5,
                        repeat: 2,
                        yoyo: true,
                        ease: "power1.inOut"
                    });

                    // Call backend
                    setTimeout(async () => {
                        try {
                            const response = await fetch("{{ route('customer.tech-lab.analyze-device') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(this.form)
                            });
                            
                            this.analysisData = await response.json();
                            this.step = 'results';
                            this.speak(this.analysisData.speech);
                        } catch (e) {
                            alert("Link Failure: Could not establish connection to Byte Node.");
                            this.step = 'input';
                        }
                    }, 3000);
                },

                speak(text) {
                    if ('speechSynthesis' in window) {
                        this.isSpeaking = true;
                        const utterance = new SpeechSynthesisUtterance(text);
                        utterance.rate = 1.1;
                        utterance.pitch = 0.9;
                        utterance.onend = () => this.isSpeaking = false;
                        window.speechSynthesis.speak(utterance);
                    }
                },

                reset() {
                    this.step = 'input';
                    this.form = { brand: '', model: '', ram: '', storage: 'HDD', problem: '' };
                    window.speechSynthesis.cancel();
                    this.isSpeaking = false;
                }
            }
        }
    </script>
</div>
@endsection

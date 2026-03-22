@extends('layouts.customer')

@section('title', 'AI Troubleshooting Challenge')

@section('content')
<div class="min-h-screen bg-[#020410] relative rounded-3xl overflow-hidden font-mono" x-data="aiTroubleshooter()">
    
    <!-- Matrix Terminal Background -->
    <div class="absolute inset-0 opacity-10 pointer-events-none overflow-hidden">
        <template x-for="i in 20">
            <div class="absolute text-[8px] text-[#00f2ff] animate-pulse whitespace-nowrap" 
                 :style="`left: ${Math.random()*100}%; top: -10%; animation-duration: ${2+Math.random()*5}s; transform: rotate(90deg)`">
                 01010111 10101001 11001101 11110001 00011110 10101010 01100110
            </div>
        </template>
    </div>

    <!-- Main Console UI -->
    <div class="relative z-10 p-10 flex flex-col h-full lg:h-screen">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-12">
            <div class="flex items-center gap-4">
                 <div class="w-10 h-10 rounded-full border border-[#00f2ff] flex items-center justify-center text-[#00f2ff] glow-pulse">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                 </div>
                 <h1 class="text-xl font-bold text-white uppercase tracking-tighter italic">AI Sentinel <span class="text-[#00f2ff]">Diagnostic Console</span></h1>
            </div>
            <div class="text-right">
                <span class="text-[9px] text-gray-500 uppercase font-black tracking-widest block">Uptime</span>
                <span class="text-sm font-bold text-white" x-text="formatTime(timer)">00:00:00</span>
            </div>
        </div>

        <!-- Crisis Display -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 flex-grow">
            
            <!-- Left: Problem Monitor -->
            <div class="lg:col-span-8 space-y-8">
                <div class="p-1 w-full bg-gradient-to-r from-[#00f2ff]/30 via-transparent to-[#bc13fe]/30 rounded-3xl">
                    <div class="bg-black/90 rounded-[22px] p-8 min-h-[400px] relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-[#00f2ff]/20"></div>
                        
                        <div x-show="!currentScenario" class="flex flex-col items-center justify-center h-full">
                            <div class="w-20 h-20 border-4 border-dashed border-[#00f2ff]/20 animate-spin rounded-full mb-6"></div>
                            <p class="text-gray-600 animate-pulse">CONNECTING TO NEURAL MESH...</p>
                        </div>

                        <div x-show="currentScenario" class="animate-slide-up">
                            <div class="flex justify-between items-start mb-10">
                                <div>
                                    <span class="text-[9px] font-bold text-[#00f2ff] uppercase tracking-widest bg-[#00f2ff]/10 px-3 py-1 rounded-full mb-4 inline-block">CRITICAL ANOMALY DETECTED</span>
                                    <h2 class="text-4xl font-black text-white italic uppercase tracking-tighter" x-text="currentScenario.title"></h2>
                                </div>
                                <div class="text-right">
                                    <span class="text-[9px] text-gray-500 uppercase font-bold">Risk Level</span>
                                    <div class="flex gap-1 mt-2">
                                        <template x-for="i in 5">
                                            <div class="w-4 h-1 rounded-full" :class="i <= currentScenario.risk ? 'bg-red-500 shadow-[0_0_8px_#ef4444]' : 'bg-white/10'"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 bg-white/5 border border-white/5 rounded-2xl mb-10">
                                <p class="text-sm text-gray-300 leading-relaxed italic" x-text="`“${currentScenario.description}”`"></p>
                            </div>

                            <div class="grid grid-cols-3 gap-6">
                                <div class="p-4 bg-black border border-white/5 rounded-xl">
                                    <p class="text-[8px] text-gray-600 uppercase mb-1">Observation</p>
                                    <p class="text-[10px] text-white" x-text="currentScenario.symptom"></p>
                                </div>
                                <div class="p-4 bg-black border border-white/5 rounded-xl">
                                    <p class="text-[8px] text-gray-600 uppercase mb-1">Temp Delta</p>
                                    <p class="text-[10px] text-red-400" x-text="currentScenario.temp"></p>
                                </div>
                                <div class="p-4 bg-black border border-white/5 rounded-xl">
                                    <p class="text-[8px] text-gray-600 uppercase mb-1">Fan RPM</p>
                                    <p class="text-[10px] text-orange-400 font-bold" x-text="currentScenario.rpm"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Decision Matrix -->
            <div class="lg:col-span-4 space-y-6">
                <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4">Decision Matrix</h3>
                
                <template x-for="option in currentScenario?.options" :key="option.id">
                    <button 
                        class="w-full text-left p-6 rounded-2xl border transition-all relative overflow-hidden group"
                        :class="selectedOption === option.id ? 'bg-[#00f2ff] text-black border-transparent scale-[1.02] shadow-[0_0_30px_rgba(0,242,255,0.4)]' : 'bg-black/60 border-white/10 text-white hover:border-[#00f2ff]/50'"
                        @click="submitDecision(option)"
                    >
                        <div class="flex justify-between items-center relative z-10">
                            <span class="text-xs font-black uppercase italic tracking-tight" x-text="option.label"></span>
                            <span x-show="selectedOption !== option.id" class="text-[10px] text-gray-600 font-bold">CMD_ALPHA</span>
                        </div>
                        <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 opacity-0 group-hover:opacity-10 group-hover:translate-x-4 transition-all duration-500 pointer-events-none">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </button>
                </template>

                <div class="mt-12 p-6 glass-panel rounded-2xl bg-[#00f2ff]/5">
                    <h4 class="text-[9px] font-black text-[#00f2ff] uppercase tracking-widest mb-4">Sentinel Intel</h4>
                    <p class="text-[10px] text-gray-500 leading-relaxed italic">Think like a technician. If the thermal sensors are peaking but the fan RPM is zero, the localized node failure is mechanical.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Win/Loss Overlay -->
    <div x-show="feedbackStatus" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-xl" x-transition>
        <div class="text-center animate-float">
             <div class="w-32 h-32 mx-auto rounded-full flex items-center justify-center text-6xl mb-8" 
                  :class="feedbackStatus === 'success' ? 'bg-green-500/20 text-green-400 shadow-[0_0_50px_rgba(34,197,94,0.3)]' : 'bg-red-500/20 text-red-400 shadow-[0_0_50px_rgba(239,68,68,0.3)]'">
                  <span x-text="feedbackStatus === 'success' ? '✓' : '✗'"></span>
             </div>
             <h2 class="text-5xl font-black text-white uppercase italic tracking-tighter mb-4" x-text="feedbackStatus === 'success' ? 'FIXED' : 'SYSTEM CRASH'"></h2>
             <p class="text-gray-400 mb-10 max-w-sm mx-auto" x-text="feedbackMessage"></p>
             
             <template x-if="feedbackStatus === 'success'">
                <button @click="nextScenario()" class="btn-primary py-4 px-12 text-xs tracking-widest">Next Anomaly</button>
             </template>
             <template x-if="feedbackStatus === 'error'">
                <button @click="resetGame()" class="py-4 px-12 rounded-2xl border border-red-500/50 text-red-400 text-xs font-black uppercase tracking-widest hover:bg-red-500/10">Reboot System</button>
             </template>
        </div>
    </div>

</div>

<script>
    function aiTroubleshooter() {
        return {
            timer: 0,
            interval: null,
            currentScenario: null,
            selectedOption: null,
            feedbackStatus: null,
            feedbackMessage: '',
            scenarios: [
                {
                    title: 'Thermal Spike Delta',
                    description: 'Client reports laptop shut down during heavy compilation. Visual inspection shows zero exhaust movement.',
                    symptom: 'Artifacting on screen',
                    temp: '105°C (Critical)',
                    rpm: '0 (Null)',
                    risk: 4,
                    options: [
                        { id: 1, label: 'Replace Thermal Compound', correct: false, msg: 'The compound helps, but if the fan isn\'t spinning, it won\'t solve the core spike.' },
                        { id: 2, label: 'Replace Thermal Fan Unit', correct: true, msg: 'Correct. The zero RPM reading confirmed a mechanical node failure.' },
                        { id: 3, label: 'Update BIOS Firmware', correct: false, msg: 'Software cannot fix a physical obstruction or motor death.' }
                    ]
                },
                {
                    title: 'BIOS No-Post Loop',
                    description: 'System powers on, LEDs active, but no visual output. PC beeps 3 times in short succession.',
                    symptom: '3x Short Beeps',
                    temp: '32°C (Stable)',
                    rpm: '1200 (Normal)',
                    risk: 3,
                    options: [
                        { id: 1, label: 'Re-seat Memory Modules', correct: true, msg: 'Standard post code for RAM failure. Reseating solved the bridge issue.' },
                        { id: 2, label: 'Replace GPU Bridge', correct: false, msg: 'GPU failure usually emits different post signals.' },
                        { id: 3, label: 'Check Power Supply Rail', correct: false, msg: 'System has power (LEDs/Fans), so the rail is likely healthy.' }
                    ]
                }
            ],

            init() {
                this.startTimer();
                this.nextScenario();
            },

            startTimer() {
                this.interval = setInterval(() => { this.timer++; }, 1000);
            },

            formatTime(s) {
                const h = Math.floor(s / 3600);
                const m = Math.floor((s % 3600) / 60);
                const sec = s % 60;
                return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')}`;
            },

            nextScenario() {
                this.feedbackStatus = null;
                this.selectedOption = null;
                const available = this.scenarios.filter(s => s !== this.currentScenario);
                this.currentScenario = available[Math.floor(Math.random() * available.length)];
            },

            submitDecision(option) {
                this.selectedOption = option.id;
                setTimeout(() => {
                    if (option.correct) {
                        this.feedbackStatus = 'success';
                        this.feedbackMessage = option.msg;
                        this.saveScore();
                    } else {
                        this.feedbackStatus = 'error';
                        this.feedbackMessage = option.msg;
                    }
                }, 800);
            },

            resetGame() {
                this.timer = 0;
                this.nextScenario();
            },

            saveScore() {
                  fetch("{{ route('customer.tech-lab.save-score') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        game_type: 'troubleshooting',
                        score: 2500,
                        time_seconds: this.timer
                    })
                });
            }
        }
    }
</script>
@endsection

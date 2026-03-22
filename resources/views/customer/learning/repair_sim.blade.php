@extends('layouts.customer')

@section('title', '3D Laptop Repair Simulation')

@section('content')
<div class="min-h-screen bg-[#050510] relative rounded-3xl overflow-hidden" x-data="laptopRepairSim()">
    
    <!-- Three.js Canvas Container -->
    <div id="canvas-container" class="absolute inset-0 z-0"></div>

    <!-- UI Overlay (Repair HUD) -->
    <div class="relative z-10 p-6 flex flex-col h-full pointer-events-none">
        
        <!-- Top HUD -->
        <div class="flex justify-between items-start pointer-events-auto">
            <div class="flex items-center gap-4">
                <a href="{{ route('customer.tech-lab.dashboard') }}" class="p-3 bg-white/5 border border-white/10 rounded-xl text-gray-400 hover:text-white transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-xl font-black text-white uppercase italic tracking-tighter">Repair <span class="text-[#bc13fe]">Surgical Node</span></h1>
                    <p class="text-[9px] text-[#bc13fe] font-mono font-bold uppercase tracking-widest">Active Case: LAPTOP_COOLING_FAILURE_V5</p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="bg-black/80 backdrop-blur-xl px-6 py-3 rounded-2xl border border-[#bc13fe]/30">
                    <div class="text-[8px] text-gray-500 uppercase font-black tracking-widest">Technician rank</div>
                    <div class="text-xl font-black text-white" x-text="rankName">Apprentice</div>
                </div>
            </div>
        </div>

        <!-- Left Column: Tool Box -->
        <div class="flex-grow flex items-center pointer-events-none">
            <div class="w-20 space-y-4 pointer-events-auto">
                <template x-for="tool in tools" :key="tool.id">
                    <button 
                        class="w-16 h-16 rounded-2xl border flex items-center justify-center text-3xl transition-all"
                        :class="selectedTool === tool.id ? 'bg-[#bc13fe]/20 border-[#bc13fe] neon-glow-purple' : 'bg-black/60 border-white/5 opacity-50 hover:opacity-100'"
                        @click="selectedTool = tool.id"
                        :title="tool.name"
                    >
                        <span x-text="tool.icon"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Bottom: Instruction Panel -->
        <div class="mt-auto flex justify-center pointer-events-auto">
            <div class="w-full max-w-2xl p-8 rounded-3xl bg-black/80 backdrop-blur-3xl border border-white/10 relative overflow-hidden">
                <div class="absolute top-0 right-0 h-1 bg-[#bc13fe]/30" :style="`width: ${progress}%`"></div>
                
                <div class="flex gap-8 items-center">
                    <div class="w-20 h-20 rounded-2xl bg-white/5 flex items-center justify-center text-4xl border border-white/5 italic font-black text-white/10" x-text="currentStepIndex + 1">1</div>
                    <div class="flex-grow">
                        <h2 class="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-2" x-text="steps[currentStepIndex].category">PREPARATION</h2>
                        <h3 class="text-xl font-black text-white uppercase italic mb-4" x-text="steps[currentStepIndex].title">Remove Chassis Screws</h3>
                        <p class="text-sm text-gray-400 leading-relaxed max-w-md" x-text="steps[currentStepIndex].instruction"></p>
                    </div>
                    <div>
                        <button 
                            @click="actionRequest()"
                            class="py-4 px-10 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all"
                            :class="canProgress ? 'bg-[#bc13fe] text-black hover:brightness-110' : 'bg-white/5 text-gray-600 cursor-not-allowed'"
                        >
                            Perform Action
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Win Modal -->
    <div x-show="repairComplete" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/95 backdrop-blur-md" x-transition>
         <div class="w-full max-w-sm p-10 rounded-[3rem] bg-black border-2 border-[#bc13fe]/30 text-center">
             <div class="text-7xl mb-6 italic">🛠️</div>
             <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter mb-4">Diagnostics <span class="text-[#bc13fe]">Clear</span></h2>
             <p class="text-gray-400 mb-8 text-sm">Thermal efficiency restored. Battery health optimized. System operational.</p>
             <button @click="location.href='{{ route('customer.tech-lab.dashboard') }}'" class="btn-primary py-4 w-full text-xs tracking-[0.2em] shadow-[#bc13fe]/50" style="background: linear-gradient(135deg, #bc13fe, #00f2ff)">Return to Command Hub</button>
         </div>
    </div>

</div>

<!-- Three.js Integration -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>

<script>
    function laptopRepairSim() {
        return {
            selectedTool: 'hand',
            currentStepIndex: 0,
            progress: 0,
            repairComplete: false,
            rankName: 'Apprentice',
            
            tools: [
                { id: 'hand', name: 'Precision Hand', icon: '🖐️' },
                { id: 'driver', name: 'Nano Driver', icon: '🪛' },
                { id: 'paste', name: 'Thermal Compound', icon: '🧪' },
                { id: 'tweezers', name: 'Titanium Tweezers', icon: '✂️' }
            ],

            steps: [
                { category: 'CHASSIS OPS', title: 'Open Outer Shell', instruction: 'Use the Nano Driver to remove the 3 primary security screws to access the internal motherboard node.', tool: 'driver' },
                { category: 'ENERGY NODE', title: 'Disconnect Power Cell', instruction: 'SAFETY PROTOCOL: Use your hands to carefully detach the battery bridge cable from the board.', tool: 'hand' },
                { category: 'THERMAL SYSTEM', title: 'Extract Defective Fan', instruction: 'Remove the securing brackets and lift the failing cooling turbine using tweezers.', tool: 'tweezers' },
                { category: 'THERMAL SYSTEM', title: 'Apply Global Paste', instruction: 'Apply a single pea-sized drop of high-conductivity thermal compound to the CPU die.', tool: 'paste' },
                { category: 'FINALIZATION', title: 'Seal System', instruction: 'Replace the chassis cover and secure all nodes. Power cycle initiate.', tool: 'driver' }
            ],

            get canProgress() {
                return this.selectedTool === this.steps[this.currentStepIndex].tool;
            },

            init() {
                this.init3D();
            },

            init3D() {
                const container = document.getElementById('canvas-container');
                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
                const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
                
                renderer.setSize(window.innerWidth, window.innerHeight);
                container.appendChild(renderer.domElement);

                // Lighting
                scene.add(new THREE.AmbientLight(0xffffff, 0.3));
                const rimLight = new THREE.PointLight(0xbc13fe, 2);
                rimLight.position.set(-5, 5, -5);
                scene.add(rimLight);

                const centerLight = new THREE.PointLight(0xffffff, 1);
                centerLight.position.set(0, 10, 0);
                scene.add(centerLight);

                // Laptop Chassis (Stylized Box)
                const chassisGeom = new THREE.BoxGeometry(6, 0.4, 4);
                const chassisMat = new THREE.MeshPhongMaterial({ color: 0x111111, shininess: 100 });
                const chassis = new THREE.Mesh(chassisGeom, chassisMat);
                scene.add(chassis);
                this.chassis = chassis;

                // Screws
                this.screws = [];
                for(let i=0; i<3; i++) {
                    const sGeom = new THREE.CylinderGeometry(0.05, 0.05, 0.1, 8);
                    const sMat = new THREE.MeshPhongMaterial({ color: 0x555555 });
                    const screw = new THREE.Mesh(sGeom, sMat);
                    screw.position.set(-2.5 + (i * 2.5), 0.2, 1.8);
                    scene.add(screw);
                    this.screws.push(screw);
                }

                camera.position.set(0, 6, 4);
                camera.lookAt(0, 0, 0);

                const controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.autoRotate = true;
                controls.autoRotateSpeed = 0.5;
                this.controls = controls;

                const animate = () => {
                    requestAnimationFrame(animate);
                    controls.update();
                    renderer.render(scene, camera);
                };
                animate();

                this.scene = scene;
            },

            actionRequest() {
                if (!this.canProgress) return;

                const step = this.steps[this.currentStepIndex];
                
                // Animate Step specific world objects
                if (this.currentStepIndex === 0) {
                    // Remove Screws
                    this.screws.forEach((s, idx) => {
                        gsap.to(s.position, { y: 2, duration: 0.5, delay: idx * 0.2 });
                        gsap.to(s.scale, { x:0, y:0, z:0, duration: 0.5, delay: 0.5 + idx * 0.2 });
                    });
                } else if (this.currentStepIndex === 1) {
                    // Lift a box representing battery
                    const bGeom = new THREE.BoxGeometry(4, 0.1, 1);
                    const bMat = new THREE.MeshPhongMaterial({ color: 0x222222 });
                    const battery = new THREE.Mesh(bGeom, bMat);
                    battery.position.set(0, 0.25, 1);
                    this.scene.add(battery);
                    gsap.to(battery.position, { y: 1.5, x: 5, duration: 1 });
                } else if (this.currentStepIndex === 3) {
                     // Thermal paste drop
                     const pGeom = new THREE.SphereGeometry(0.1, 16, 16);
                     const pMat = new THREE.MeshBasicMaterial({ color: 0xc0c0c0 });
                     const paste = new THREE.Mesh(pGeom, pMat);
                     paste.position.set(0, 5, 0);
                     this.scene.add(paste);
                     gsap.to(paste.position, { y: 0.25, duration: 0.5, ease: "bounce.out" });
                }

                // Progress Logic
                this.currentStepIndex++;
                this.progress = (this.currentStepIndex / this.steps.length) * 100;

                if (this.currentStepIndex >= this.steps.length) {
                    this.repairComplete = true;
                    this.saveScore();
                }
            },

            saveScore() {
                fetch("{{ route('customer.tech-lab.save-score') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        game_type: 'laptop_repair',
                        score: 5000,
                        time_seconds: 0
                    })
                });
            }
        }
    }
</script>
@endsection

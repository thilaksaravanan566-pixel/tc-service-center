@extends('layouts.customer')

@section('title', '3D PC Builder Simulation')

@section('content')
<div class="min-h-screen bg-[#020410] relative rounded-3xl overflow-hidden" x-data="pcBuilder3D()">
    
    <!-- Three.js Canvas Container -->
    <div id="canvas-container" class="absolute inset-0 z-0"></div>

    <!-- UI Overlay (HUD) -->
    <div class="relative z-10 p-6 flex flex-col h-full pointer-events-none">
        
        <!-- Top HUD -->
        <div class="flex justify-between items-start pointer-events-auto">
            <div class="flex items-center gap-4">
                <a href="{{ route('customer.tech-lab.dashboard') }}" class="p-3 bg-white/5 border border-white/10 rounded-xl text-gray-400 hover:text-white transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 0118 0z"/></svg>
                </a>
                <div>
                    <h1 class="text-xl font-black text-white uppercase italic tracking-tighter">3D Assembly <span class="text-[#00f2ff]">Bay</span></h1>
                    <p class="text-[9px] text-[#00f2ff] font-mono font-bold uppercase tracking-widest">Active Sim: BUILD_NODAL_PC_V2</p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="bg-black/60 backdrop-blur-xl px-6 py-3 rounded-2xl border border-[#00f2ff]/30 text-right">
                    <div class="text-[8px] text-gray-500 uppercase font-black tracking-widest">Assembly Score</div>
                    <div class="text-2xl font-black text-white" x-text="score">0</div>
                </div>
                <button @click="resetCamera()" class="p-3 bg-black/60 backdrop-blur-xl border border-white/10 rounded-xl text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-7h1m4 0h1m-7 4h1m4 0h1m-7 4h1m4 0h1"/></svg>
                </button>
            </div>
        </div>

        <!-- Right Side: Component Selector -->
        <div class="flex-grow flex justify-end items-center pointer-events-none">
            <div class="w-80 space-y-4 pointer-events-auto">
                <h2 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 text-right">Component Registry</h2>
                
                <template x-for="component in components" :key="component.id">
                    <div 
                        class="p-4 rounded-2xl border transition-all cursor-pointer flex items-center gap-4 group"
                        :class="component.placed ? 'bg-green-500/10 border-green-500/30' : (selectedComponent === component.id ? 'bg-[#00f2ff]/10 border-[#00f2ff]/50' : 'bg-black/60 border-white/10')"
                        @click="selectComponent(component)"
                    >
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl transition-transform group-hover:scale-110" 
                             :class="component.placed ? 'bg-green-500/20' : 'bg-white/5'"
                             x-text="component.icon"></div>
                        <div class="flex-grow">
                            <h3 class="text-xs font-bold uppercase tracking-tight" :class="component.placed ? 'text-green-400' : 'text-white'" x-text="component.name"></h3>
                            <p class="text-[9px] text-gray-500 font-bold uppercase" x-text="component.category"></p>
                        </div>
                        <div x-show="component.placed" class="text-green-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Bottom Status Bar -->
        <div class="mt-auto flex justify-between items-end pointer-events-auto">
            <div class="w-96 p-6 rounded-3xl bg-black/80 backdrop-blur-3xl border border-white/10">
                <div x-show="!inspectedPart" class="text-center py-4 opacity-30">
                     <p class="text-[10px] uppercase font-black font-mono tracking-widest">Neural Link Idle // Select Part</p>
                </div>
                <div x-show="inspectedPart" class="animate-slide-up">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-lg font-black text-white uppercase italic" x-text="inspectedPart.name"></h2>
                        <span class="text-[9px] font-bold text-[#00f2ff] bg-[#00f2ff]/10 px-2 rounded-full uppercase" x-text="inspectedPart.category"></span>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed mb-6" x-text="inspectedPart.description"></p>
                    <div class="flex gap-4">
                         <button @click="attemptPlacement()" class="flex-grow py-3 rounded-xl bg-[#00f2ff] text-black font-black text-[10px] uppercase tracking-widest hover:brightness-110 transition-all">
                             Install Component
                         </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-end gap-2">
                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Simulation Engine</span>
                <div class="flex items-center gap-1">
                    <div class="w-1 h-1 rounded-full bg-[#00f2ff] animate-ping"></div>
                    <span class="text-[10px] font-mono text-white">RENDER_SPEED: 144FPS</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Win Modal -->
    <div x-show="winState" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md" x-transition>
         <div class="w-full max-w-lg p-10 rounded-[3rem] bg-black border-2 border-[#00f2ff]/30 text-center relative overflow-hidden">
             <div class="absolute inset-0 bg-[#00f2ff]/5 z-0"></div>
             <div class="relative z-10">
                 <div class="text-7xl mb-6">🌩️</div>
                 <h2 class="text-4xl font-black text-white uppercase italic tracking-tighter mb-4">BIOS Initialize <span class="text-[#00f2ff]">Success</span></h2>
                 <p class="text-gray-400 mb-8">All nodes mapped. Power distribution stable. Hardware assembly complete.</p>
                 
                 <div class="grid grid-cols-2 gap-4 mb-10">
                     <div class="bg-white/5 p-6 rounded-3xl border border-white/10">
                         <div class="text-[10px] text-gray-500 uppercase font-bold mb-1">Final Score</div>
                         <div class="text-3xl font-black text-white" x-text="score"></div>
                     </div>
                     <div class="bg-white/5 p-6 rounded-3xl border border-white/10">
                         <div class="text-[10px] text-gray-500 uppercase font-bold mb-1">XP Earned</div>
                         <div class="text-3xl font-black text-[#bc13fe]" x-text="Math.floor(score/10)"></div>
                     </div>
                 </div>

                 <div class="flex flex-col gap-4">
                     <button @click="saveAndExit()" class="btn-primary py-4 w-full text-xs tracking-[0.2em]">Transmit Data & Exit</button>
                     <button @click="location.reload()" class="text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest">Restart Assembly</button>
                 </div>
             </div>
         </div>
    </div>

</div>

<!-- Three.js Integration -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>

<script>
    function pcBuilder3D() {
        return {
            score: 0,
            selectedComponent: null,
            inspectedPart: null,
            winState: false,
            
            components: [
                { id: 'cpu', name: 'Intel Delta-X CPU', category: 'Processor', icon: '🧠', placed: false, description: 'Central processing node. Must be inserted into the Socket LGA-TC.' },
                { id: 'cooler', name: 'Neon-Liquid AIO', category: 'Cooling', icon: '❄️', placed: false, required: ['cpu'], description: 'Thermal regulation module. Installs directly over the CPU node.' },
                { id: 'ram', name: 'Hyper-Stream DDR6', category: 'Memory', icon: '📏', placed: false, description: 'Flux data cache. Slots into primary DIMM lanes.' },
                { id: 'gpu', name: 'Quantum Ray 9000', category: 'Graphics', icon: '🎮', placed: false, description: 'Visual processing engine. The heavy node for the primary PCIe bridge.' },
                { id: 'psu', name: 'Fusion Core 10k', category: 'Power', icon: '⚡', placed: false, description: 'Global energy distributor. Located in the base energy bay.' }
            ],

            init() {
                this.init3D();
            },

            init3D() {
                const container = document.getElementById('canvas-container');
                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
                const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
                
                renderer.setSize(window.innerWidth, window.innerHeight);
                renderer.setPixelRatio(window.devicePixelRatio);
                container.appendChild(renderer.domElement);

                // Lighting
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
                scene.add(ambientLight);

                const pointLight = new THREE.PointLight(0x00f2ff, 1);
                pointLight.position.set(5, 5, 5);
                scene.add(pointLight);

                // Motherboard Placeholder (Main Build Area)
                const mbGeometry = new THREE.BoxGeometry(4, 0.1, 5);
                const mbMaterial = new THREE.MeshPhongMaterial({ 
                    color: 0x0a0a1f, 
                    emissive: 0x00f2ff, 
                    emissiveIntensity: 0.05,
                    shininess: 100 
                });
                const motherboard = new THREE.Mesh(mbGeometry, mbMaterial);
                scene.add(motherboard);

                // Draw Circuit Lines (Visual FX)
                const gridHelper = new THREE.GridHelper(10, 20, 0x00f2ff, 0x222222);
                gridHelper.position.y = -0.1;
                scene.add(gridHelper);

                // Camera Position
                camera.position.set(5, 5, 5);
                camera.lookAt(0, 0, 0);

                const controls = new THREE.OrbitControls(camera, renderer.domElement);
                controls.enableDamping = true;
                this.controls = controls;

                // Game Object Tracking
                this.scene = scene;
                this.camera = camera;
                this.renderer = renderer;
                this.parts = {}; // Rendered meshes

                // Animate
                const animate = () => {
                    requestAnimationFrame(animate);
                    controls.update();
                    renderer.render(scene, camera);
                };
                animate();

                // Window Resize
                window.addEventListener('resize', () => {
                    camera.aspect = window.innerWidth / window.innerHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(window.innerWidth, window.innerHeight);
                });

                this.renderSlots();
            },

            renderSlots() {
                // Visualize where parts should go
                const slotMaterial = new THREE.MeshBasicMaterial({ color: 0x00f2ff, transparent: true, opacity: 0.1, wireframe: true });
                
                // CPU Slot
                const cpuSlot = new THREE.Mesh(new THREE.BoxGeometry(0.8, 0.1, 0.8), slotMaterial);
                cpuSlot.position.set(-0.5, 0.1, -0.5);
                this.scene.add(cpuSlot);
                this.parts['cpu_slot'] = cpuSlot;

                // RAM Slots
                const ramSlot = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.2, 2.5), slotMaterial);
                ramSlot.position.set(1.5, 0.1, -0.5);
                this.scene.add(ramSlot);
                this.parts['ram_slot'] = ramSlot;

                // GPU Slot
                const gpuSlot = new THREE.Mesh(new THREE.BoxGeometry(3, 0.1, 0.2), slotMaterial);
                gpuSlot.position.set(0, 0.1, 1.5);
                this.scene.add(gpuSlot);
                this.parts['gpu_slot'] = gpuSlot;
            },

            selectComponent(comp) {
                if (comp.placed) return;
                this.selectedComponent = comp.id;
                this.inspectedPart = comp;
                
                // Visual Highlight of the target slot in 3D
                // (In a real app, logic to flash the slot mesh)
            },

            attemptPlacement() {
                if (!this.selectedComponent) return;
                const comp = this.components.find(c => c.id === this.selectedComponent);

                // Check dependencies
                if (comp.required) {
                    const missing = comp.required.filter(id => !this.components.find(c => c.id === id).placed);
                    if (missing.length > 0) {
                        alert(`CRITICAL ERROR: Requires ${missing.join(', ').toUpperCase()} placement first.`);
                        return;
                    }
                }

                // Placeholder for 3D part instantiation
                this.spawnPart(comp);
                
                comp.placed = true;
                this.score += 1000;
                this.selectedComponent = null;
                this.inspectedPart = null;

                if (this.components.every(c => c.placed)) {
                    this.winState = true;
                }
            },

            spawnPart(comp) {
                let geom, mat, mesh;
                
                switch(comp.id) {
                    case 'cpu':
                        geom = new THREE.BoxGeometry(0.7, 0.1, 0.7);
                        mat = new THREE.MeshPhongMaterial({ color: 0x333333, shininess: 100 });
                        mesh = new THREE.Mesh(geom, mat);
                        mesh.position.set(-0.5, 0.1, -0.5);
                        break;
                    case 'cooler':
                        geom = new THREE.BoxGeometry(1.2, 0.8, 1.2);
                        mat = new THREE.MeshPhongMaterial({ color: 0x111111, transparent: true, opacity: 0.8 });
                        mesh = new THREE.Mesh(geom, mat);
                        mesh.position.set(-0.5, 0.5, -0.5);
                        break;
                    case 'ram':
                        geom = new THREE.BoxGeometry(0.08, 0.8, 2.4);
                        mat = new THREE.MeshPhongMaterial({ color: 0x00f2ff });
                        mesh = new THREE.Mesh(geom, mat);
                        mesh.position.set(1.5, 0.45, -0.5);
                        break;
                    case 'gpu':
                        geom = new THREE.BoxGeometry(2.8, 0.8, 0.6);
                        mat = new THREE.MeshPhongMaterial({ color: 0xbc13fe });
                        mesh = new THREE.Mesh(geom, mat);
                        mesh.position.set(0, 0.45, 1.5);
                        break;
                    case 'psu':
                        geom = new THREE.BoxGeometry(1.5, 1, 1.5);
                        mat = new THREE.MeshPhongMaterial({ color: 0x050505 });
                        mesh = new THREE.Mesh(geom, mat);
                        mesh.position.set(0, -1, -3); // Off-board position
                        break;
                }

                if (mesh) {
                    // Animation: Fall into place
                    mesh.position.y += 5;
                    this.scene.add(mesh);
                    gsap.to(mesh.position, { y: mesh.position.y - 5, duration: 0.5, ease: "bounce.out" });
                }
            },

            resetCamera() {
                gsap.to(this.camera.position, { x: 5, y: 5, z: 5, duration: 1 });
            },

            saveAndExit() {
                fetch("{{ route('customer.tech-lab.save-score') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        game_type: 'pc_build',
                        score: this.score,
                        time_seconds: 0 // Mocked
                    })
                })
                .then(res => res.json())
                .then(data => {
                    location.href = "{{ route('customer.tech-lab.dashboard') }}";
                });
            }
        }
    }
</script>
@endsection

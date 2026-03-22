@extends('layouts.customer')

@section('title', 'Laptop Virus Smash')

@section('content')
<div class="min-h-screen bg-[#050510] relative rounded-3xl overflow-hidden cursor-crosshair select-none" x-data="virusSmashGame()">
    
    <!-- Matrix Rain Background (Canvas Layer 0) -->
    <canvas id="matrix-bg" class="absolute inset-0 z-0 opacity-20 pointer-events-none"></canvas>

    <!-- Main Game Canvas (Canvas Layer 1) -->
    <canvas id="game-canvas" class="absolute inset-0 z-10 w-full h-full" @mousedown="handleClick($event)" @touchstart="handleTouch($event)"></canvas>

    <!-- HUD Overlay -->
    <div class="relative z-20 p-6 lg:p-10 pointer-events-none flex flex-col h-full lg:h-screen">
        
        <!-- Top HUD bar -->
        <div class="flex justify-between items-start">
            <div class="flex items-center gap-6 pointer-events-auto">
                <a href="{{ route('customer.tech-lab.dashboard') }}" class="w-12 h-12 rounded-xl bg-black/60 border border-white/10 flex items-center justify-center text-gray-400 hover:text-[#00f2ff] transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div class="hidden md:block">
                    <h1 class="text-xl font-black text-white italic uppercase tracking-tighter">Laptop Virus <span class="text-[#00f2ff]">Smash</span></h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">System Shield: ACTIVE</span>
                    </div>
                </div>
            </div>

            <!-- Stats Module -->
            <div class="flex gap-4 pointer-events-auto">
                <div class="glass-panel px-6 py-3 rounded-2xl border-l-4 border-l-[#00f2ff]">
                    <div class="text-[8px] text-gray-500 uppercase font-black tracking-widest">Score Data</div>
                    <div class="text-2xl font-black text-white tabular-nums" x-text="score">0</div>
                </div>
                <div class="glass-panel px-6 py-3 rounded-2xl border-l-4 border-l-[#ff0055]">
                    <div class="text-[8px] text-gray-500 uppercase font-black tracking-widest">System Integrity</div>
                    <div class="h-2 w-32 bg-white/5 rounded-full mt-2 overflow-hidden border border-white/5">
                        <div class="h-full bg-gradient-to-r from-[#ff0055] to-[#ff6600] transition-all duration-300" :style="`width: ${health}%` text"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Combo Multiplier (Floating) -->
        <div class="flex-grow flex items-center justify-center pointer-events-none">
            <div x-show="combo > 1" class="text-6xl font-black italic text-[#00f2ff] opacity-40 animate-bounce" x-text="`${combo}X` text"></div>
        </div>

        <!-- Bottom Status bar -->
        <div class="mt-auto flex justify-between items-end">
            <div class="glass-panel p-6 rounded-2xl w-auto pointer-events-auto">
                <h3 class="text-[10px] font-black text-[#00f2ff] uppercase tracking-widest mb-3">Power-Ups Available</h3>
                <div class="flex gap-3">
                    <template x-for="power in powerups" :key="power.id">
                        <button 
                            @click="usePowerUp(power)"
                            class="w-12 h-12 rounded-xl border flex items-center justify-center text-xl transition-all relative overflow-hidden active:scale-95"
                            :class="power.active ? 'bg-[#ffcc00] border-transparent text-black scale-110 shadow-[0_0_20px_#ffcc00]' : 'glass-panel border-white/10 text-white opacity-40'"
                            :disabled="!power.ready"
                        >
                            <span x-text="power.icon"></span>
                            <div x-show="!power.ready" class="absolute inset-0 bg-black/60 flex items-center justify-center text-[10px] font-bold" x-text="power.cooldown"></div>
                        </button>
                    </template>
                </div>
            </div>

            <div class="text-right pointer-events-auto">
                <div class="text-[9px] text-gray-500 uppercase font-bold tracking-widest mb-2">Level Progression</div>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-black text-white italic" x-text="`STAGE ${level}` text"></span>
                    <div class="w-24 h-1.5 bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full bg-[#00f2ff]" :style="`width: ${levelProgress}%` text"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Start Menu -->
    <div x-show="gameState === 'start'" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md">
        <div class="text-center space-y-8 animate-float">
            <div class="w-32 h-32 mx-auto rounded-[2rem] bg-gradient-to-br from-[#00f2ff] to-[#bc13fe] flex items-center justify-center text-6xl shadow-[0_0_50px_rgba(0,242,255,0.3)]">
                👾
            </div>
            <h2 class="text-5xl font-black text-white italic tracking-tighter uppercase underline decoration-[#00f2ff] decoration-4 underline-offset-8">Virus Smash</h2>
            <p class="text-gray-400 max-w-sm mx-auto font-bold uppercase text-xs tracking-widest leading-loose">Secure the kernel. Terminate all incoming malware nodes before system entropy reaches 100%.</p>
            <div class="flex flex-col gap-4">
                <button @click="startGame()" class="btn-primary py-5 px-16 text-sm tracking-[0.3em] uppercase italic">Initialize System Seal</button>
                <span class="text-[9px] text-[#00f2ff] uppercase font-bold tracking-widest animate-pulse">Ready to intercept</span>
            </div>
        </div>
    </div>

    <!-- Game Over Modal -->
    <div x-show="gameState === 'gameover'" class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-xl" x-transition>
        <div class="w-full max-w-md p-10 rounded-[3rem] glass-panel text-center relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-red-500/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="text-7xl mb-6">💀</div>
                <h2 class="text-4xl font-black text-red-500 italic uppercase tracking-tighter mb-2">System Crash</h2>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-10">Total Sector Compromise</p>
                
                <div class="grid grid-cols-2 gap-4 mb-10">
                    <div class="bg-white/5 p-6 rounded-3xl border border-white/5">
                        <div class="text-[9px] text-gray-500 uppercase font-black mb-1">Final Score</div>
                        <div class="text-3xl font-black text-white" x-text="score"></div>
                    </div>
                    <div class="bg-white/5 p-6 rounded-3xl border border-white/5">
                        <div class="text-[9px] text-gray-500 uppercase font-black mb-1">Level Reached</div>
                        <div class="text-3xl font-black text-[#00f2ff]" x-text="level"></div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <button @click="saveAndExit()" class="btn-primary py-4 w-full text-xs tracking-[0.2em] shadow-[#00f2ff]/30">Transmit Data & Exit</button>
                    <button @click="startGame()" class="text-gray-500 hover:text-white text-[10px] font-black uppercase tracking-widest">Full System Reboot</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .glass-panel {
            background: rgba(10, 15, 30, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 242, 255, 0.2);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }
        .btn-primary {
            background: linear-gradient(135deg, #00f2ff, #bc13fe);
            color: white;
            font-weight: 900;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 242, 255, 0.2);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .cursor-crosshair { cursor: crosshair; }
    </style>

    <script>
        function virusSmashGame() {
            return {
                gameState: 'start', // start, playing, gameover
                score: 0,
                health: 100,
                level: 1,
                combo: 1,
                comboTimer: 0,
                levelProgress: 0,
                lastSpawnTime: 0,
                spawnsThisLevel: 0,
                
                powerups: [
                    { id: 'shield', icon: '🛡️', ready: true, active: false, cooldown: 0 },
                    { id: 'nuke', icon: '☢️', ready: true, active: false, cooldown: 30 }
                ],

                // Game Components
                viruses: [],
                particles: [],
                explosions: [],
                
                // Canvas Contexts
                ctx: null,
                bgCtx: null,

                init() {
                    this.initBackground();
                    window.addEventListener('resize', () => {
                        this.resizeCanvas();
                    });
                },

                initBackground() {
                    const canvas = document.getElementById('matrix-bg');
                    this.bgCtx = canvas.getContext('2d');
                    this.resizeCanvas();
                    this.drawMatrix();
                },

                resizeCanvas() {
                    const bgCanvas = document.getElementById('matrix-bg');
                    const gameCanvas = document.getElementById('game-canvas');
                    bgCanvas.width = window.innerWidth;
                    bgCanvas.height = window.innerHeight;
                    gameCanvas.width = window.innerWidth;
                    gameCanvas.height = window.innerHeight;
                },

                drawMatrix() {
                    const canvas = document.getElementById('matrix-bg');
                    const ctx = this.bgCtx;
                    const fontSize = 14;
                    const columns = canvas.width / fontSize;
                    const drops = [];

                    for(let x = 0; x < columns; x++) drops[x] = 1;

                    const draw = () => {
                        ctx.fillStyle = 'rgba(5, 5, 16, 0.05)';
                        ctx.fillRect(0, 0, canvas.width, canvas.height);
                        ctx.fillStyle = '#00f2ff';
                        ctx.font = fontSize + 'px monospace';

                        for(let i = 0; i < drops.length; i++) {
                            const text = String.fromCharCode(Math.random() * 128);
                            ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                            if(drops[i] * fontSize > canvas.height && Math.random() > 0.975) drops[i] = 0;
                            drops[i]++;
                        }
                    };
                    setInterval(draw, 33);
                },

                startGame() {
                    this.gameState = 'playing';
                    this.score = 0;
                    this.health = 100;
                    this.level = 1;
                    this.levelProgress = 0;
                    this.viruses = [];
                    this.particles = [];
                    this.spawnsThisLevel = 0;

                    const canvas = document.getElementById('game-canvas');
                    this.ctx = canvas.getContext('2d');
                    
                    this.gameLoop();
                },

                gameLoop() {
                    if (this.gameState !== 'playing') return;

                    this.update();
                    this.draw();

                    requestAnimationFrame(() => this.gameLoop());
                },

                update() {
                    const now = Date.now();
                    const spawnRate = Math.max(300, 1500 - (this.level * 100));

                    // Spawning
                    if (now - this.lastSpawnTime > spawnRate) {
                        this.spawnVirus();
                        this.lastSpawnTime = now;
                    }

                    // Update Viruses
                    this.viruses = this.viruses.filter(v => {
                        v.update();
                        if (v.y > window.innerHeight) {
                            this.takeDamage(10);
                            this.combo = 1;
                            return false;
                        }
                        return true;
                    });

                    // Update Particles
                    this.particles = this.particles.filter(p => {
                        p.update();
                        return p.life > 0;
                    });

                    // Level Up Logic
                    if (this.levelProgress >= 100) {
                        this.levelUp();
                    }

                    // Combo Timer
                    if (this.combo > 1) {
                        this.comboTimer += 16;
                        if (this.comboTimer > 1500) {
                            this.combo = 1;
                            this.comboTimer = 0;
                        }
                    }

                    // Check Death
                    if (this.health <= 0) {
                        this.gameState = 'gameover';
                    }
                },

                draw() {
                    this.ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);

                    // Draw Particles
                    this.particles.forEach(p => p.draw(this.ctx));

                    // Draw Viruses
                    this.viruses.forEach(v => v.draw(this.ctx));
                },

                spawnVirus() {
                    const types = [
                        { symbol: '👾', pts: 100, speed: 2 + Math.random() * 2, size: 40 },
                        { symbol: '🐛', pts: 200, speed: 4 + Math.random() * 3, size: 30 },
                        { symbol: '🔓', pts: 500, speed: 1.5, size: 60, health: 3 }
                    ];

                    // Chance for rare hacker
                    if (Math.random() > 0.9) types.push({ symbol: '👤', pts: 1000, speed: 8, size: 35 });

                    const config = types[Math.floor(Math.random() * types.length)];
                    const x = 50 + Math.random() * (window.innerWidth - 100);
                    
                    this.viruses.push(new Virus(x, -50, config));
                    this.spawnsThisLevel++;
                    this.levelProgress = (this.spawnsThisLevel % 20) * 5;
                },

                handleClick(e) {
                    if (this.gameState !== 'playing') return;
                    this.handleAction(e.clientX, e.clientY);
                },

                handleTouch(e) {
                    if (this.gameState !== 'playing') return;
                    e.preventDefault();
                    this.handleAction(e.touches[0].clientX, e.touches[0].clientY);
                },

                handleAction(x, y) {
                    let hit = false;
                    this.viruses.forEach(v => {
                        const dist = Math.hypot(v.x - x, v.y - y);
                        if (dist < v.size + 20) {
                            v.onHit();
                            if (v.dead) {
                                this.score += v.pts * this.combo;
                                this.combo++;
                                this.comboTimer = 0;
                                this.createExplosion(v.x, v.y, v.color);
                            }
                            hit = true;
                        }
                    });

                    if (!hit) {
                        this.combo = 1;
                    }
                },

                createExplosion(x, y, color) {
                    for(let i=0; i<15; i++) {
                        this.particles.push(new Particle(x, y, color));
                    }
                },

                takeDamage(amt) {
                    this.health -= amt;
                    // Screen shake effect
                    const canvas = document.getElementById('game-canvas');
                    gsap.to(canvas, { x: 10, duration: 0.05, repeat: 3, yoyo: true, onComplete: () => gsap.set(canvas, {x:0}) });
                },

                levelUp() {
                    this.level++;
                    this.levelProgress = 0;
                    this.spawnsThisLevel = 0;
                    this.health = Math.min(100, this.health + 20);
                },

                usePowerUp(p) {
                    if (p.id === 'nuke') {
                        this.viruses.forEach(v => {
                            this.score += v.pts;
                            this.createExplosion(v.x, v.y, '#00f2ff');
                        });
                        this.viruses = [];
                        p.ready = false;
                        p.active = true;
                        setTimeout(() => p.active = false, 500);
                    }
                },

                saveAndExit() {
                     fetch("{{ route('customer.tech-lab.save-score') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            game_type: 'virus_smash',
                            score: this.score,
                            level: this.level
                        })
                    }).then(() => {
                        location.href = "{{ route('customer.tech-lab.dashboard') }}";
                    });
                }
            }
        }

        // Virus Entity class
        class Virus {
            constructor(x, y, config) {
                this.x = x;
                this.y = y;
                this.symbol = config.symbol;
                this.pts = config.pts;
                this.speed = config.speed;
                this.size = config.size;
                this.health = config.health || 1;
                this.dead = false;
                this.color = this.getColor();
                this.oscillation = 0;
            }

            getColor() {
                if(this.symbol === '👾') return '#00f2ff';
                if(this.symbol === '🐛') return '#ff0055';
                if(this.symbol === '🔓') return '#ffcc00';
                return '#bc13fe';
            }

            update() {
                this.y += this.speed;
                this.oscillation += 0.05;
                this.x += Math.sin(this.oscillation) * 2;
            }

            draw(ctx) {
                ctx.save();
                ctx.translate(this.x, this.y);
                
                // Glow
                ctx.shadowBlur = 15;
                ctx.shadowColor = this.color;
                
                ctx.font = this.size + 'px serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(this.symbol, 0, 0);
                
                ctx.restore();
            }

            onHit() {
                this.health--;
                if (this.health <= 0) this.dead = true;
            }
        }

        // Particle Class
        class Particle {
            constructor(x, y, color) {
                this.x = x;
                this.y = y;
                this.color = color;
                this.size = Math.random() * 4;
                this.vx = (Math.random() - 0.5) * 10;
                this.vy = (Math.random() - 0.5) * 10;
                this.life = 1.0;
                this.decay = Math.random() * 0.02 + 0.02;
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;
                this.life -= this.decay;
            }

            draw(ctx) {
                ctx.fillStyle = this.color;
                ctx.globalAlpha = this.life;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
                ctx.globalAlpha = 1;
            }
        }
    </script>
</div>
@endsection

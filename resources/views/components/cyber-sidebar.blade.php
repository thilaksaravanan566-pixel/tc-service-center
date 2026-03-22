<div x-data="{ collapsed: false, mobileOpen: false }" class="relative z-50">
    
    <style>
        .cyber-sidebar {
            background: linear-gradient(180deg, #0A0F2C 0%, #050816 100%);
            box-shadow: 1px 0 24px rgba(79, 124, 255, 0.08);
            border-right: 1px solid rgba(79, 124, 255, 0.15);
        }

        .cyber-menu-link {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .cyber-menu-link::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%) scaleY(0);
            height: 60%;
            width: 3px;
            background-color: #4F7CFF;
            border-radius: 4px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 0 10px rgba(79,124,255,0.45);
        }

        .cyber-menu-link:hover {
            background: linear-gradient(90deg, rgba(79,124,255,0.1) 0%, transparent 100%);
            transform: translateX(4px);
        }

        .cyber-menu-link:hover::before {
            transform: translateY(-50%) scaleY(1);
        }

        .cyber-menu-link:hover .cyber-icon {
            color: #4F7CFF;
            filter: drop-shadow(0 0 6px rgba(79,124,255,0.45));
            transform: scale(1.1);
        }

        .cyber-menu-active {
            background: linear-gradient(90deg, rgba(79,124,255,0.15) 0%, rgba(79,124,255,0.02) 100%);
            border: 1px solid rgba(79, 124, 255, 0.2);
            border-left: none;
            border-right: none;
        }

        .cyber-menu-active::before {
            transform: translateY(-50%) scaleY(1);
            height: 100%;
            border-radius: 0;
            left: -1px;
        }

        .cyber-menu-active .cyber-icon {
            color: #4F7CFF;
            filter: drop-shadow(0 0 8px rgba(79,124,255,0.45));
        }

        .cyber-glass-panel {
            background: rgba(10, 15, 44, 0.6);
            backdrop-filter: blur(12px);
            border-top: 1px solid rgba(79, 124, 255, 0.15);
        }

        /* Scrollbar */
        .cyber-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .cyber-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .cyber-scroll::-webkit-scrollbar-thumb {
            background: rgba(79,124,255,0.2);
            border-radius: 10px;
        }
        .cyber-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(79,124,255,0.5);
        }

        @keyframes pulseGlow {
            0% { box-shadow: 0 0 5px rgba(79,124,255,0.3); }
            50% { box-shadow: 0 0 15px rgba(79,124,255,0.6); }
            100% { box-shadow: 0 0 5px rgba(79,124,255,0.3); }
        }
        .animate-pulse-glow {
            animation: pulseGlow 3s infinite;
        }
    </style>

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-[#050816]/90 backdrop-blur-md lg:hidden"
         @click="mobileOpen = false"
         style="display: none;"></div>

    <!-- Sidebar Container -->
    <aside :class="collapsed ? 'w-[80px]' : 'w-[280px]'"
           class="cyber-sidebar fixed lg:relative inset-y-0 left-0 flex flex-col h-screen transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] transform lg:transform-none z-50"
           :class="mobileOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- LOGO AREA -->
        <div class="h-20 flex items-center px-6 relative overflow-hidden flex-shrink-0 border-b border-[#4F7CFF]/20">
            <div class="absolute inset-0 bg-gradient-to-r from-[#4F7CFF]/10 to-transparent opacity-50"></div>
            
            <div class="flex items-center gap-4 relative z-10 w-full">
                <!-- Icon -->
                <div class="w-10 h-10 rounded-xl bg-[#050816] border border-[#4F7CFF]/40 flex items-center justify-center flex-shrink-0 animate-pulse-glow">
                    <svg class="w-6 h-6 text-[#4F7CFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                
                <!-- Text Box -->
                <div class="transition-opacity duration-300 whitespace-nowrap" :class="collapsed ? 'opacity-0 w-0 overflow-hidden' : 'opacity-100'">
                    <h1 class="text-[#FFFFFF] font-bold text-sm tracking-wide leading-tight drop-shadow-[0_0_8px_rgba(255,255,255,0.3)]">THAMBU COMPUTERS</h1>
                    <p class="text-[#4F7CFF] text-[10px] uppercase tracking-[0.15em] font-semibold mt-0.5 drop-shadow-[0_0_5px_rgba(79,124,255,0.6)]">Service Command Hub</p>
                </div>
            </div>

            <!-- Collapse button (Desktop) -->
            <button @click="collapsed = !collapsed" class="absolute right-4 text-[#8A93B2] hover:text-[#FFFFFF] transition-colors lg:block hidden z-20 bg-[#0A0F2C] rounded-full p-1 border border-[#4F7CFF]/20 hover:border-[#4F7CFF]">
                <svg class="w-4 h-4 transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
            </button>
        </div>

        <!-- MAIN NAVIGATION -->
        <nav class="flex-1 overflow-y-auto cyber-scroll py-6 px-3 space-y-7 relative">
            
            <!-- SECTION 1 — REPAIR PROTOCOL -->
            <div>
                <p class="px-3 text-[10px] font-bold text-[#8A93B2]/70 tracking-[0.15em] uppercase mb-3 transition-opacity duration-300 whitespace-nowrap" :class="collapsed ? 'opacity-0 h-0 hidden' : 'opacity-100'">Repair Protocol</p>
                <div class="space-y-1.5">
                    <a href="#" class="cyber-menu-link cyber-menu-active flex items-center gap-4 px-3 py-2.5 rounded-lg text-[#FFFFFF]">
                        <svg class="cyber-icon w-5 h-5 text-[#4F7CFF] flex-shrink-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.83-5.83M15.17 11.42L21 5.58A2.652 2.652 0 0017.25 1.83l-5.83 5.83m-1.42 1.42l-2.83 2.83a2.652 2.652 0 00-3.75 3.75l-.83.83m4.58-4.58l-2.83 2.83a2.652 2.652 0 00-3.75 3.75l-.83.83m13.75-13.75L5.58 21m0 0l-3.75 3.75M21 5.58l-3.75 3.75"/></svg>
                        <span class="text-sm font-medium tracking-wide whitespace-nowrap transition-opacity duration-300" :class="collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Repair Service</span>
                    </a>
                </div>
            </div>

            <!-- SECTION 2 — MARKET MATRIX -->
            <div>
                <p class="px-3 text-[10px] font-bold text-[#8A93B2]/70 tracking-[0.15em] uppercase mb-3 transition-opacity duration-300 whitespace-nowrap" :class="collapsed ? 'opacity-0 h-0 hidden' : 'opacity-100'">Market Matrix</p>
                <div class="space-y-1.5">
                    <a href="#" class="cyber-menu-link flex items-center gap-4 px-3 py-2.5 rounded-lg text-[#8A93B2] hover:text-[#FFFFFF]">
                        <svg class="cyber-icon w-5 h-5 flex-shrink-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span class="text-sm font-medium tracking-wide whitespace-nowrap transition-opacity duration-300" :class="collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Spare Store</span>
                    </a>
                    <a href="#" class="cyber-menu-link flex items-center gap-4 px-3 py-2.5 rounded-lg text-[#8A93B2] hover:text-[#FFFFFF]">
                        <svg class="cyber-icon w-5 h-5 flex-shrink-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-sm font-medium tracking-wide whitespace-nowrap transition-opacity duration-300" :class="collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Refurbished Laptops</span>
                    </a>
                </div>
            </div>

            <!-- SECTION 3 — DEPLOYMENT OPS -->
            <div>
                <p class="px-3 text-[10px] font-bold text-[#8A93B2]/70 tracking-[0.15em] uppercase mb-3 transition-opacity duration-300 whitespace-nowrap" :class="collapsed ? 'opacity-0 h-0 hidden' : 'opacity-100'">Deployment Ops</p>
                <div class="space-y-1.5">
                    <a href="#" class="cyber-menu-link flex items-center gap-4 px-3 py-2.5 rounded-lg text-[#8A93B2] hover:text-[#FFFFFF]">
                        <svg class="cyber-icon w-5 h-5 flex-shrink-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span class="text-sm font-medium tracking-wide whitespace-nowrap transition-opacity duration-300" :class="collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Desktop Assembly</span>
                    </a>
                    <a href="#" class="cyber-menu-link flex items-center gap-4 px-3 py-2.5 rounded-lg text-[#8A93B2] hover:text-[#FFFFFF]">
                        <svg class="cyber-icon w-5 h-5 flex-shrink-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span class="text-sm font-medium tracking-wide whitespace-nowrap transition-opacity duration-300" :class="collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">CCTV Installation</span>
                    </a>
                </div>
            </div>

            <!-- SECTION 4 — LOGISTICS & PROTECTION -->
            <div>
                <p class="px-3 text-[10px] font-bold text-[#8A93B2]/70 tracking-[0.15em] uppercase mb-3 transition-opacity duration-300 whitespace-nowrap" :class="collapsed ? 'opacity-0 h-0 hidden' : 'opacity-100'">Logistics & Protection</p>
                <div class="space-y-1.5">
                    <a href="#" class="cyber-menu-link flex items-center gap-4 px-3 py-2.5 rounded-lg text-[#8A93B2] hover:text-[#FFFFFF]">
                        <svg class="cyber-icon w-5 h-5 flex-shrink-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        <span class="text-sm font-medium tracking-wide whitespace-nowrap transition-opacity duration-300" :class="collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Track Order</span>
                    </a>
                    <a href="#" class="cyber-menu-link flex items-center gap-4 px-3 py-2.5 rounded-lg text-[#8A93B2] hover:text-[#FFFFFF]">
                        <svg class="cyber-icon w-5 h-5 flex-shrink-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span class="text-sm font-medium tracking-wide whitespace-nowrap transition-opacity duration-300" :class="collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Warranty Shield</span>
                    </a>
                    <a href="#" class="cyber-menu-link flex items-center gap-4 px-3 py-2.5 rounded-lg text-[#8A93B2] hover:text-[#FFFFFF] relative">
                        <svg class="cyber-icon w-5 h-5 flex-shrink-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span class="flex-1 text-sm font-medium tracking-wide whitespace-nowrap transition-opacity duration-300" :class="collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Cart</span>
                        <span class="absolute right-3 bg-[#4F7CFF]/20 text-[#4F7CFF] text-[10px] font-bold px-2 py-0.5 rounded border border-[#4F7CFF]/30 transition-opacity duration-300 shadow-[0_0_8px_rgba(79,124,255,0.4)]" :class="collapsed ? 'opacity-0 hidden' : 'opacity-100'">03</span>
                    </a>
                </div>
            </div>
            
        </nav>

        <!-- USER PANEL (BOTTOM) -->
        <div class="p-4 cyber-glass-panel relative flex-shrink-0 z-10 before:content-[''] before:absolute before:inset-0 before:bg-gradient-to-t before:from-[#050816] before:to-transparent before:-z-10">
            <div class="flex items-center gap-3">
                <div class="relative flex-shrink-0 group cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name=Guest+User&background=4F7CFF&color=fff&size=40" class="w-10 h-10 rounded-xl border border-[#4F7CFF]/50 p-[1px] shadow-[0_0_10px_rgba(79,124,255,0.3)] transition-all group-hover:border-[#4F7CFF] group-hover:shadow-[0_0_15px_rgba(79,124,255,0.6)]" alt="User Avatar">
                    <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-400 border-2 border-[#0A0F2C] rounded-full shadow-[0_0_8px_rgba(52,211,153,0.8)] animate-pulse"></div>
                </div>
                <div class="transition-opacity duration-300 min-w-0" :class="collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">
                    <p class="text-sm font-bold text-[#FFFFFF] truncate tracking-wide">Guest User</p>
                    <p class="text-[9px] text-[#4F7CFF] uppercase tracking-[0.2em] font-semibold mt-0.5">Level 1 Protocol</p>
                </div>
            </div>
            
            <button class="mt-4 w-full flex items-center justify-center gap-2 py-2.5 rounded text-red-400 hover:text-[#FFFFFF] hover:bg-red-500/20 bg-red-500/5 shadow-[inset_0_0_10px_rgba(239,68,68,0.1)] hover:shadow-[0_0_15px_rgba(239,68,68,0.3),inset_0_0_15px_rgba(239,68,68,0.2)] border border-red-500/20 transition-all duration-300 group overflow-hidden relative" :class="collapsed ? 'px-0' : 'px-4'">
                <svg class="w-4 h-4 flex-shrink-0 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span class="text-[10px] font-bold tracking-[0.15em] uppercase transition-opacity duration-300 whitespace-nowrap" :class="collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Terminate Connection</span>
                
                <!-- Hover scanline -->
                <div class="absolute inset-y-0 left-0 w-full bg-gradient-to-b from-transparent via-red-500/20 to-transparent translate-y-[-100%] group-hover:translate-y-[100%] transition-transform duration-[1.5s] ease-in-out"></div>
            </button>
        </div>
    </aside>

    <!-- Mobile Toggle Button (Visible only on mobile out of the sidebar context, this should ideally go in your topbar) -->
    <!-- 
    <button @click="mobileOpen = true" class="fixed top-4 left-4 z-40 lg:hidden bg-[#0A0F2C] p-2 border border-[#4F7CFF]/30 rounded-lg text-[#4F7CFF] shadow-[0_0_10px_rgba(79,124,255,0.3)]">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
    </button> 
    -->
</div>

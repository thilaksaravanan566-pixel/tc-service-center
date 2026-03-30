@extends('layouts.customer')
@section('title', 'Settings')

@section('content')
<div class="animate-slide-up" x-data="{
    activeTab: 'profile',
    notifs: { service: true, logistics: true, warranty: true, promo: false },
    saving: false,
    saved: false,
    saveSettings() {
        this.saving = true;
        setTimeout(() => {
            this.saving = false;
            this.saved = true;
            setTimeout(() => this.saved = false, 2500);
        }, 1200);
    }
}">

    {{-- Save Toast --}}
    <div x-show="saved" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-end="opacity-0"
         class="fixed top-6 right-6 z-[200] flex items-center gap-3 px-5 py-3 rounded-xl"
         style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;box-shadow:var(--shadow-lg)">
        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        <span style="font-size:0.8rem;font-weight:600">Settings saved successfully</span>
    </div>

    <div class="page-header">
        <h1 class="page-title">Settings</h1>
        <p class="page-sub">Manage your profile, notifications, security and preferences.</p>
    </div>

    <div style="display:flex;gap:24px;align-items:flex-start" class="settings-wrap">

        {{-- Settings Sidebar --}}
        <div style="width:220px;flex-shrink:0" class="settings-sidebar">
            <div class="card" style="padding:12px;position:sticky;top:84px">
                @php
                $tabs = [
                    ['id' => 'profile',        'label' => 'Profile',       'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['id' => 'notifications',  'label' => 'Notifications', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                    ['id' => 'locations',      'label' => 'Addresses',     'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['id' => 'security',       'label' => 'Security',      'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['id' => 'support',        'label' => 'Help & Support', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
                ];
                @endphp
                @foreach($tabs as $tab)
                <button @click="activeTab = '{{ $tab['id'] }}'"
                        :class="activeTab === '{{ $tab['id'] }}' ? 'nav-item active' : 'nav-item'"
                        class="w-full text-left" id="tab-{{ $tab['id'] }}"
                        style="border:none;background:none;cursor:pointer;width:100%">
                    <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/>
                    </svg>
                    {{ $tab['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Main Panel --}}
        <div style="flex:1;min-width:0">

            {{-- 1. PROFILE --}}
            <div x-show="activeTab === 'profile'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="card" style="padding:28px">
                    <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border)">
                        <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary)">Profile Details</h2>
                        <p style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">Update your personal information.</p>
                    </div>
                    <form action="#" method="POST">
                        @csrf
                        <div style="display:flex;gap:24px;flex-wrap:wrap;margin-bottom:24px">
                            {{-- Avatar --}}
                            <div style="flex-shrink:0;text-align:center">
                                <div style="width:80px;height:80px;border-radius:var(--radius-md);overflow:hidden;margin:0 auto 8px">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth('customer')->user()->name ?? 'Guest') }}&background=0ea5e9&color=fff&size=128"
                                         alt="Profile" style="width:100%;height:100%;object-fit:cover">
                                </div>
                                <button type="button" style="font-size:0.72rem;font-weight:600;color:var(--primary);background:none;border:none;cursor:pointer">Change photo</button>
                            </div>
                            {{-- Fields --}}
                            <div style="flex:1;display:grid;grid-template-columns:1fr 1fr;gap:14px;min-width:240px">
                                <div>
                                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Full Name</label>
                                    <input type="text" name="name" value="{{ auth('customer')->user()->name ?? '' }}" class="super-input">
                                </div>
                                <div>
                                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Email Address</label>
                                    <input type="email" name="email" value="{{ auth('customer')->user()->email ?? '' }}" class="super-input">
                                </div>
                                <div>
                                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Phone Number</label>
                                    <input type="text" name="mobile" value="{{ auth('customer')->user()->mobile ?? '' }}" placeholder="+91 XXXXX XXXXX" class="super-input">
                                </div>
                                <div>
                                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Language</label>
                                    <select name="language" class="super-input" style="appearance:none;cursor:pointer">
                                        <option>English</option>
                                        <option>Tamil</option>
                                        <option>Hindi</option>
                                    </select>
                                </div>
                                <div style="grid-column:span 2">
                                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Address</label>
                                    <textarea name="address" class="super-input" rows="2" style="resize:none">{{ auth('customer')->user()->address ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:16px;border-top:1px solid var(--border)">
                            <button type="button" @click="saveSettings()" class="btn btn-primary">
                                <svg x-show="saving" class="animate-spin" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 2. NOTIFICATIONS --}}
            <div x-show="activeTab === 'notifications'" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="card" style="padding:28px">
                    <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border)">
                        <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary)">Notifications</h2>
                        <p style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">Choose which updates to receive.</p>
                    </div>
                    @php
                    $notifItems = [
                        ['key' => 'service',   'title' => 'Service Updates',     'desc' => 'Status changes for your active repair jobs.'],
                        ['key' => 'logistics', 'title' => 'Order & Delivery',     'desc' => 'Packing, dispatch and delivery notifications.'],
                        ['key' => 'warranty',  'title' => 'Warranty Alerts',      'desc' => 'Reminders when warranty is about to expire.'],
                        ['key' => 'promo',     'title' => 'Promotions & Offers',  'desc' => 'Deals, discounts and new product announcements.'],
                    ];
                    @endphp
                    <div style="display:flex;flex-direction:column;gap:4px">
                        @foreach($notifItems as $item)
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);gap:16px" onmouseover="this.style.background='var(--primary-50)'" onmouseout="this.style.background='transparent'">
                            <div>
                                <p style="font-size:0.875rem;font-weight:600;color:var(--text-primary)">{{ $item['title'] }}</p>
                                <p style="font-size:0.75rem;color:var(--text-muted);margin-top:2px">{{ $item['desc'] }}</p>
                            </div>
                            <label style="cursor:pointer;flex-shrink:0">
                                <input type="checkbox" class="sr-only" x-model="notifs.{{ $item['key'] }}">
                                <div class="neon-toggle" :class="notifs.{{ $item['key'] }} ? 'on' : ''">
                                    <div class="neon-toggle-thumb"></div>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div style="display:flex;justify-content:flex-end;margin-top:20px">
                        <button type="button" @click="saveSettings()" class="btn btn-primary">
                            <span x-text="saving ? 'Saving...' : 'Save Preferences'"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- 3. LOCATIONS --}}
            <div x-show="activeTab === 'locations'" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="card" style="padding:28px">
                    <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
                        <div>
                            <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary)">Saved Addresses</h2>
                            <p style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">Manage your delivery locations.</p>
                        </div>
                        <button class="btn btn-secondary btn-sm">
                            <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Add Address
                        </button>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px">
                        <div style="padding:16px;border-radius:var(--radius-sm);border:1px solid var(--primary-200);background:var(--primary-50);position:relative">
                            <span class="badge badge-sky" style="position:absolute;top:12px;right:12px">Primary</span>
                            <p style="font-size:0.875rem;font-weight:600;color:var(--text-primary);margin-bottom:4px">Home Address</p>
                            <p style="font-size:0.8rem;color:var(--text-secondary)">{{ auth('customer')->user()->address ?? 'No address configured yet.' }}</p>
                            <div style="display:flex;gap:14px;margin-top:12px;padding-top:12px;border-top:1px solid var(--border)">
                                <button class="text-sm font-medium" style="color:var(--primary);background:none;border:none;cursor:pointer;padding:0">Edit</button>
                                <button class="text-sm font-medium" style="color:var(--text-muted);background:none;border:none;cursor:pointer;padding:0">View on Map</button>
                            </div>
                        </div>
                        <div style="padding:16px;border-radius:var(--radius-sm);border:1px dashed var(--border);text-align:center;cursor:pointer" onmouseover="this.style.background='var(--primary-50)'" onmouseout="this.style.background='transparent'">
                            <p style="font-size:0.8rem;color:var(--text-muted);margin-bottom:4px">+ Add another address</p>
                            <p style="font-size:0.72rem;color:var(--text-muted)">e.g. Office, Work, etc.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. SECURITY --}}
            <div x-show="activeTab === 'security'" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="card" style="padding:28px">
                    <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border)">
                        <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary)">Security</h2>
                        <p style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">Manage your password and account security.</p>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:20px">
                        <div>
                            <h3 style="font-size:0.875rem;font-weight:600;color:var(--text-primary);margin-bottom:14px">Change Password</h3>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                                <div style="grid-column:span 2">
                                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Current Password</label>
                                    <input type="password" placeholder="Enter current password" class="super-input">
                                </div>
                                <div>
                                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">New Password</label>
                                    <input type="password" placeholder="Min. 8 characters" class="super-input">
                                </div>
                                <div>
                                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Confirm New Password</label>
                                    <input type="password" placeholder="Repeat new password" class="super-input">
                                </div>
                            </div>
                            <div style="margin-top:14px">
                                <button class="btn btn-primary">Update Password</button>
                            </div>
                        </div>
                        <hr class="divider">
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border)">
                            <div>
                                <p style="font-size:0.875rem;font-weight:600;color:var(--text-primary)">Two-Factor Authentication</p>
                                <p style="font-size:0.75rem;color:var(--text-muted);margin-top:2px">Add an extra layer of security to your account.</p>
                            </div>
                            <button class="btn btn-secondary btn-sm">Enable</button>
                        </div>
                        <div>
                            <p style="font-size:0.875rem;font-weight:600;color:var(--text-primary);margin-bottom:12px">Active Sessions</p>
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:14px;border-radius:var(--radius-sm);background:var(--primary-50);border:1px solid var(--border)">
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:var(--primary-100);display:flex;align-items:center;justify-content:center">
                                        <svg style="width:18px;height:18px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p style="font-size:0.8rem;font-weight:600;color:var(--text-primary)">Current Session</p>
                                        <p style="font-size:0.7rem;color:var(--text-muted)">Active now · {{ request()->ip() }}</p>
                                    </div>
                                </div>
                                <span class="badge badge-green">Active</span>
                            </div>
                            <form method="POST" action="{{ route('customer.logout') }}" style="margin-top:14px">
                                @csrf
                                <button type="submit" style="font-size:0.8rem;font-weight:600;color:#ef4444;background:none;border:none;cursor:pointer">Sign out of all sessions</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. SUPPORT --}}
            <div x-show="activeTab === 'support'" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="card" style="padding:28px">
                    <div style="margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border)">
                        <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary)">Help & Support</h2>
                        <p style="font-size:0.8rem;color:var(--text-muted);margin-top:3px">Get in touch with our team.</p>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin-bottom:24px">
                        @php
                        $company = \App\Models\CompanyProfile::first();
                        $phone   = $company?->phone   ?: Setting::get('support_phone', '+91 98765 43210');
                        $email   = $company?->email   ?: Setting::get('support_email', 'support@thambu.in');
                        $address = $company?->address ?: Setting::get('company_address', 'Chennai, TN');

                        $contacts = [
                            ['icon' => '📞', 'title' => 'Call Us',       'val' => $phone, 'sub' => 'Mon–Sat · 9AM–7PM'],
                            ['icon' => '📧', 'title' => 'Email',         'val' => $email, 'sub' => 'Reply within 24h'],
                            ['icon' => '💬', 'title' => 'Live Chat',     'val' => 'AI Assistant',    'sub' => 'Available 24/7'],
                            ['icon' => '📍', 'title' => 'Our Location',  'val' => Str::limit($address, 30), 'sub' => 'Visit us in person'],
                        ];
                        @endphp
                        @foreach($contacts as $c)
                        <div style="padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);cursor:pointer;transition:all 0.15s" onmouseover="this.style.background='var(--primary-50)';this.style.borderColor='var(--primary-200)'" onmouseout="this.style.background='transparent';this.style.borderColor='var(--border)'">
                            <p style="font-size:1.3rem;margin-bottom:8px">{{ $c['icon'] }}</p>
                            <p style="font-size:0.8rem;font-weight:600;color:var(--text-primary)">{{ $c['title'] }}</p>
                            <p style="font-size:0.75rem;color:var(--primary);margin-top:2px;font-weight:500">{{ $c['val'] }}</p>
                            <p style="font-size:0.7rem;color:var(--text-muted)">{{ $c['sub'] }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div style="padding:20px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--primary-50)">
                        <p style="font-size:0.875rem;font-weight:600;color:var(--text-primary);margin-bottom:14px">Send a Message</p>
                        <div style="display:flex;flex-direction:column;gap:10px">
                            <input type="text" placeholder="Subject" class="super-input">
                            <textarea placeholder="Describe your issue…" class="super-input" rows="4" style="resize:none"></textarea>
                            <button class="btn btn-primary">Send Message</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
@media (max-width:768px) {
    .settings-wrap { flex-direction: column !important; }
    .settings-sidebar { width:100% !important; }
}
</style>
@endsection

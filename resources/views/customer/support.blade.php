@extends('layouts.customer')
@section('title', 'Support')

@section('content')
<div class="animate-slide-up" style="max-width:960px">

    <div class="page-header">
        <h1 class="page-title">Support Centre</h1>
        <p class="page-sub">Get help with your repairs, orders and devices. We're here for you.</p>
    </div>

    {{-- Quick Help Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:28px">
        <div class="card" style="padding:20px;text-align:center;cursor:pointer;transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">
            <div style="width:48px;height:48px;border-radius:var(--radius-sm);background:var(--primary-50);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                <svg style="width:24px;height:24px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            </div>
            <h3 style="font-size:0.875rem;font-weight:600;color:var(--text-primary);margin-bottom:6px">Track Order</h3>
            <p style="font-size:0.75rem;color:var(--text-muted);line-height:1.5">Real-time status updates for your repairs and deliveries.</p>
        </div>
        <a href="{{ route('customer.warranty.index') }}" class="card" style="padding:20px;text-align:center;text-decoration:none;display:block;cursor:pointer;transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">
            <div style="width:48px;height:48px;border-radius:var(--radius-sm);background:#f0fdf4;margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                <svg style="width:24px;height:24px;color:#16a34a" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <h3 style="font-size:0.875rem;font-weight:600;color:var(--text-primary);margin-bottom:6px">Warranty Claim</h3>
            <p style="font-size:0.75rem;color:var(--text-muted);line-height:1.5">File a warranty claim for your covered devices and parts.</p>
        </a>
        <div class="card" style="padding:20px;text-align:center;cursor:pointer;transition:box-shadow 0.2s" onmouseover="this.style.boxShadow='var(--shadow-md)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'" onclick="document.getElementById('ai-widget-btn').click()">
            <div style="width:48px;height:48px;border-radius:var(--radius-sm);background:var(--primary-50);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                <svg style="width:24px;height:24px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <h3 style="font-size:0.875rem;font-weight:600;color:var(--text-primary);margin-bottom:6px">AI Assistant</h3>
            <p style="font-size:0.75rem;color:var(--text-muted);line-height:1.5">Chat with our AI for instant answers to common questions.</p>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px" class="support-grid">

        {{-- FAQ --}}
        <div>
            <div class="page-header" style="margin-bottom:16px">
                <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary)">Frequently Asked Questions</h2>
            </div>
            <div style="display:flex;flex-direction:column;gap:8px" x-data="{ active: 1 }">

                @php $faqs = [
                    [1,'How long does a repair take?','Standard repairs (software, upgrades) are usually completed within 24–48 hours. Complex hardware repairs may take up to 7 business days.'],
                    [2,'Are spare parts genuine?','Yes. We source directly from OEM manufacturers or certified distributors. All products include an official warranty.'],
                    [3,'Do you offer home pickup?','Yes. You can select pickup/delivery during the booking process. Our team will coordinate the logistics.'],
                    [4,'How can I check repair status?','Log in to your portal and visit "My Repairs" or enter your job ID on our public tracking page.'],
                ]; @endphp

                @foreach($faqs as [$id,$q,$a])
                <div class="card" style="overflow:hidden">
                    <button @click="active = (active === {{ $id }} ? 0 : {{ $id }})"
                            style="width:100%;text-align:left;padding:14px 18px;font-size:0.875rem;font-weight:600;color:var(--text-primary);background:none;border:none;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:12px">
                        {{ $q }}
                        <svg :class="active === {{ $id }} ? 'rotate-180' : ''" style="width:16px;height:16px;color:var(--text-muted);flex-shrink:0;transition:transform 0.2s" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === {{ $id }}" x-collapse style="border-top:1px solid var(--border)">
                        <p style="padding:14px 18px;font-size:0.8rem;color:var(--text-secondary);line-height:1.6">{{ $a }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Contact Form --}}
        <div>
            <div class="page-header" style="margin-bottom:16px">
                <h2 style="font-size:1rem;font-weight:700;color:var(--text-primary)">Send a Message</h2>
            </div>
            <div class="card" style="padding:24px">
                <form class="space-y-4" style="display:flex;flex-direction:column;gap:14px">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div>
                            <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Your Name</label>
                            <input type="text" class="super-input" placeholder="John Doe" value="{{ auth('customer')->user()->name }}">
                        </div>
                        <div>
                            <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Email</label>
                            <input type="email" class="super-input" placeholder="john@example.com" value="{{ auth('customer')->user()->email }}">
                        </div>
                    </div>
                    <div>
                        <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Subject</label>
                        <select class="super-input" style="appearance:none">
                            <option>Service Query</option>
                            <option>Order Issue</option>
                            <option>Warranty Claim</option>
                            <option>General Feedback</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Message</label>
                        <textarea class="super-input" rows="5" placeholder="Describe your issue…" style="resize:vertical;line-height:1.5"></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" style="width:100%;justify-content:center" onclick="alert('Message sent! We\'ll get back to you soon.')">
                        Send Message →
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width:768px) { .support-grid { grid-template-columns: 1fr !important; } }
</style>
@endsection

@extends('layouts.customer')
@section('title', 'Book a Service')

@section('content')
<div class="animate-slide-up" style="max-width:840px">

    <div class="page-header">
        <h1 class="page-title">Book a Service</h1>
        <p class="page-sub">Submit a repair request and our certified technicians will take care of the rest.</p>
    </div>

    {{-- Technicians Available --}}
    <div class="card" style="padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
        <div style="display:flex;align-items:center;gap:10px">
            <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;display:inline-block;animation:pulse 2s infinite"></span>
            <span style="font-size:0.8rem;font-weight:500;color:var(--text-secondary)">3 certified technicians currently available</span>
        </div>
        <div style="display:flex;gap:-4px">
            <div style="width:32px;height:32px;border-radius:50%;background:var(--primary);border:2px solid #fff;display:inline-flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:700;color:#fff;margin-right:-6px">TC1</div>
            <div style="width:32px;height:32px;border-radius:50%;background:#0369a1;border:2px solid #fff;display:inline-flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:700;color:#fff;margin-right:-6px">TC2</div>
            <div style="width:32px;height:32px;border-radius:50%;background:#94a3b8;border:2px solid #fff;display:inline-flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:700;color:#fff">TC3</div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                @foreach ($errors->all() as $error)
                    <p class="alert-text">{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.service.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="repair">

        <div class="card" style="padding:28px;margin-bottom:16px">
            <h3 style="font-size:0.875rem;font-weight:700;color:var(--text-primary);margin-bottom:18px;display:flex;align-items:center;gap:8px">
                <span style="width:20px;height:20px;border-radius:50%;background:var(--primary);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0">1</span>
                Device Information
            </h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div>
                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Device Type</label>
                    <select name="device_type" class="super-input" style="appearance:none;cursor:pointer">
                        <option value="laptop">Laptop</option>
                        <option value="desktop">Desktop / PC</option>
                        <option value="printer">Printer</option>
                        <option value="other">Other Device</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Brand / Manufacturer</label>
                    <input type="text" name="brand" placeholder="e.g. Dell, HP, Apple, Lenovo" class="super-input">
                </div>
                <div>
                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Model</label>
                    <input type="text" name="model" placeholder="e.g. MacBook Pro 14, Inspiron 15" class="super-input">
                </div>
                <div>
                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Upload Photos (optional)</label>
                    <label for="photos-upload" class="super-input" style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;padding:10px 14px !important">
                        <span id="photo-label" style="font-size:0.8rem;color:var(--text-muted)">Choose photos…</span>
                        <svg style="width:16px;height:16px;color:var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </label>
                    <input type="file" name="photos[]" multiple accept="image/*" class="sr-only" id="photos-upload" onchange="updatePhotoCount(this)" style="display:none">
                </div>
            </div>
        </div>

        <div class="card" style="padding:28px;margin-bottom:16px">
            <h3 style="font-size:0.875rem;font-weight:700;color:var(--text-primary);margin-bottom:18px;display:flex;align-items:center;gap:8px">
                <span style="width:20px;height:20px;border-radius:50%;background:var(--primary);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0">2</span>
                Problem Description
            </h3>
            <textarea name="problem" required rows="5"
                      placeholder="Describe the issue in detail — e.g. screen flickering, won't boot, keyboard not working, battery draining fast…"
                      class="super-input" style="resize:none;line-height:1.6"></textarea>
        </div>

        <div class="card" style="padding:28px;margin-bottom:16px">
            <h3 style="font-size:0.875rem;font-weight:700;color:var(--text-primary);margin-bottom:18px;display:flex;align-items:center;gap:8px">
                <span style="width:20px;height:20px;border-radius:50%;background:var(--primary);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0">3</span>
                Preferred Schedule
            </h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div>
                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Preferred Date</label>
                    <input type="date" name="preferred_date" class="super-input" min="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Preferred Time Slot</label>
                    <select name="preferred_time" class="super-input" style="appearance:none;cursor:pointer">
                        <option value="morning">Morning (9:00 AM – 12:00 PM)</option>
                        <option value="afternoon">Afternoon (1:00 PM – 4:00 PM)</option>
                        <option value="evening">Evening (5:00 PM – 8:00 PM)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card" style="padding:28px;margin-bottom:20px" x-data="{ deliveryType: 'take_away' }">
            <h3 style="font-size:0.875rem;font-weight:700;color:var(--text-primary);margin-bottom:18px;display:flex;align-items:center;gap:8px">
                <span style="width:20px;height:20px;border-radius:50%;background:var(--primary);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0">4</span>
                Service Method
            </h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
                <label style="cursor:pointer">
                    <input type="radio" name="delivery_type" value="take_away" x-model="deliveryType" class="sr-only" style="display:none">
                    <div :style="deliveryType === 'take_away' ? 'border-color:var(--primary);background:var(--primary-50)' : 'border-color:var(--border)'"
                         style="padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);text-align:center;transition:all 0.15s;cursor:pointer">
                        <p style="font-size:1.5rem;margin-bottom:8px">🏬</p>
                        <p style="font-size:0.875rem;font-weight:600;color:var(--text-primary)">Drop Off</p>
                        <p style="font-size:0.72rem;color:var(--text-muted)">Bring device to our service center</p>
                    </div>
                </label>
                <label style="cursor:pointer">
                    <input type="radio" name="delivery_type" value="delivery" x-model="deliveryType" class="sr-only" style="display:none">
                    <div :style="deliveryType === 'delivery' ? 'border-color:var(--primary);background:var(--primary-50)' : 'border-color:var(--border)'"
                         style="padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border);text-align:center;transition:all 0.15s;cursor:pointer">
                        <p style="font-size:1.5rem;margin-bottom:8px">🛵</p>
                        <p style="font-size:0.875rem;font-weight:600;color:var(--text-primary)">Home Pickup</p>
                        <p style="font-size:0.72rem;color:var(--text-muted)">We'll collect from your address</p>
                    </div>
                </label>
            </div>
            <div x-show="deliveryType === 'delivery'" x-cloak x-transition>
                <label style="font-size:0.72rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:5px">Pickup Address</label>
                <textarea name="delivery_address" rows="2" class="super-input" style="resize:none"
                          placeholder="Enter your full address for pickup…">{{ auth('customer')->user()->address ?? '' }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;padding:13px">
            Submit Repair Request →
        </button>
        <p style="text-align:center;font-size:0.72rem;color:var(--text-muted);margin-top:10px">
            By submitting, you agree to our service terms and diagnostic fee policy.
        </p>
    </form>
</div>

<script>
function updatePhotoCount(input) {
    const label = document.getElementById('photo-label');
    label.textContent = input.files.length > 0
        ? `${input.files.length} photo(s) selected`
        : 'Choose photos…';
    label.style.color = input.files.length > 0 ? 'var(--primary)' : 'var(--text-muted)';
}
</script>
@endsection

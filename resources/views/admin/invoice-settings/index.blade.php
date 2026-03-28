@extends('layouts.admin')
@section('title', 'Invoice Settings')

@section('content')
<style>
/* Custom Toggle Checkbox */
.toggle-checkbox:checked {
  right: 0;
  border-color: #68D391;
}
.toggle-checkbox:checked + .toggle-label {
  background-color: #6366f1;
}
.toggle-checkbox {
  right: 0;
  z-index: 1;
  border-color: transparent;
  width: 1.25rem;
  height: 1.25rem;
  appearance: none;
  background-color: transparent;
}
.toggle-checkbox:focus {
  outline: none;
  box-shadow: none;
}
</style>

<div class="fade-up">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Invoice Layout & Engine</h1>
            <p class="text-sm text-gray-400 mt-1">Configure company branding, taxation display, and print template Engine.</p>
        </div>
        <a href="{{ route('admin.invoices.index') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" x-show="!darkMode" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Invoices
        </a>
    </div>

    <!-- Live Design Form -->
    <form action="{{ route('admin.invoice-settings.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 xl:grid-cols-3 gap-8 pb-10">
        @csrf

        <!-- LEFT COLUMN: CONTROLS -->
        <div class="xl:col-span-2 space-y-6">

            <!-- Company Profile Card (Glassmorphism) -->
            <div class="card p-6 border-t-2 border-t-indigo-500 shadow-2xl shadow-indigo-500/10 transition-all hover:bg-white/[0.03]">
                <h2 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                    <div class="p-2 bg-indigo-500/20 rounded-lg text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    Company Branding
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Company Name</label>
                        <input type="text" name="company_name" value="{{ $company->name }}" class="w-full text-sm py-2 px-3" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">GST Number</label>
                        <input type="text" name="company_gst_number" value="{{ $company->gst_number }}" class="w-full text-sm py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Phone Number</label>
                        <input type="text" name="company_phone" value="{{ $company->phone }}" class="w-full text-sm py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Email Address</label>
                        <input type="email" name="company_email" value="{{ $company->email }}" class="w-full text-sm py-2 px-3">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Office Address</label>
                        <textarea name="company_address" rows="2" class="w-full text-sm py-2 px-3">{{ $company->address }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Company Logo</label>
                        <div class="flex items-center gap-4 bg-black/20 p-3 rounded-xl border border-white/5">
                            @if($company->logo)
                                <img src="{{ asset($company->logo) }}" class="h-14 w-auto object-contain rounded bg-white/10 p-1.5 ring-1 ring-white/10 shadow-lg" alt="Logo">
                            @endif
                            <input type="file" name="logo" class="text-sm file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-500/20 file:text-indigo-400 hover:file:bg-indigo-500/40 file:transition-all text-gray-400 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layout & Appearance -->
            <div class="card p-6 border-t-2 border-t-fuchsia-500 shadow-2xl shadow-fuchsia-500/10 transition-all hover:bg-white/[0.03]">
                <h2 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                    <div class="p-2 bg-fuchsia-500/20 rounded-lg text-fuchsia-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    Layout & Appearance
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Template Engine</label>
                        <select name="default_template" class="w-full text-sm py-2 px-3">
                            <option value="standard" {{ $setting->default_template == 'standard' ? 'selected' : '' }}>Standard Professional</option>
                            <option value="modern" {{ $setting->default_template == 'modern' ? 'selected' : '' }}>Modern Clean</option>
                            <option value="compact" {{ $setting->default_template == 'compact' ? 'selected' : '' }}>Compact (A5)</option>
                            <option value="thermal" {{ $setting->default_template == 'thermal' ? 'selected' : '' }}>Thermal Receipt (80mm)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Theme Color (Hex)</label>
                        <div class="flex gap-2">
                            <input type="color" name="theme_color" value="{{ $setting->theme_color }}" class="h-10 w-12 rounded cursor-pointer border-0 p-0 bg-transparent">
                            <input type="text" value="{{ $setting->theme_color }}" class="w-full text-sm py-2 px-3" disabled>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Base Font Size</label>
                        <select name="font_size" class="w-full text-sm py-2 px-3">
                            <option value="12px" {{ $setting->font_size == '12px' ? 'selected' : '' }}>Small (12px)</option>
                            <option value="14px" {{ $setting->font_size == '14px' ? 'selected' : '' }}>Medium (14px)</option>
                            <option value="16px" {{ $setting->font_size == '16px' ? 'selected' : '' }}>Large (16px)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pt-4 border-t border-white/5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Global Header text</label>
                        <input type="text" name="header_text" value="{{ $setting->header_text }}" class="w-full text-sm py-2 px-3" placeholder="Tax Invoice / Bill of Supply">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Thank you message</label>
                        <input type="text" name="footer_message" value="{{ $setting->footer_message }}" class="w-full text-sm py-2 px-3" placeholder="Thank you for your business!">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Terms & Conditions</label>
                    <textarea name="terms_conditions" rows="3" class="w-full text-sm py-2 px-3">{{ $setting->terms_conditions }}</textarea>
                </div>
            </div>

            <!-- Numbering & Toggles -->
            <div class="card p-6 border-t-2 border-t-emerald-500 shadow-2xl shadow-emerald-500/10 transition-all hover:bg-white/[0.03]">
                <h2 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                    <div class="p-2 bg-emerald-500/20 rounded-lg text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    Engine & Format
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Prefix Series</label>
                        <input type="text" name="invoice_prefix" value="{{ $setting->invoice_prefix }}" placeholder="INV-" class="w-full text-sm py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1.5 uppercase tracking-wider">Digits Length</label>
                        <input type="number" name="invoice_number_length" value="{{ $setting->invoice_number_length }}" class="w-full text-sm py-2 px-3" required min="3" max="10">
                    </div>
                    <div class="flex items-center pt-6">
                        <label class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="auto_reset_fy" class="sr-only" {{ $setting->auto_reset_fy ? 'checked' : '' }}>
                                <div class="block bg-gray-600 w-10 h-6 rounded-full"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition {{ $setting->auto_reset_fy ? 'transform translate-x-4 bg-indigo-500' : '' }}"></div>
                            </div>
                            <div class="ml-3 text-sm font-medium text-gray-300">
                                Auto Reset FY
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Features Toggles Grid -->
                <div class="p-5 bg-black/20 rounded-xl border border-white/5">
                    <h3 class="text-sm font-semibold text-white mb-4">Print Features</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-y-4 gap-x-6">
                        
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="show_hsn_sac" class="sr-only toggle-checkbox" {{ $setting->show_hsn_sac ? 'checked' : '' }}>
                                <div class="block w-9 h-5 bg-white/10 rounded-full group-hover:bg-white/20 transition-all toggle-label"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-3 h-3 rounded-full transition-transform"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-300 uppercase tracking-widest">HSN/SAC</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="show_discount" class="sr-only toggle-checkbox" {{ $setting->show_discount ? 'checked' : '' }}>
                                <div class="block w-9 h-5 bg-white/10 rounded-full group-hover:bg-white/20 transition-all toggle-label"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-3 h-3 rounded-full transition-transform"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-300 uppercase tracking-widest">Discount</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="show_tax_breakup" class="sr-only toggle-checkbox" {{ $setting->show_tax_breakup ? 'checked' : '' }}>
                                <div class="block w-9 h-5 bg-white/10 rounded-full group-hover:bg-white/20 transition-all toggle-label"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-3 h-3 rounded-full transition-transform"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-300 uppercase tracking-widest">Tax Split</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="show_signature" class="sr-only toggle-checkbox" {{ $setting->show_signature ? 'checked' : '' }}>
                                <div class="block w-9 h-5 bg-white/10 rounded-full group-hover:bg-white/20 transition-all toggle-label"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-3 h-3 rounded-full transition-transform"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-300 uppercase tracking-widest">Signature Box</span>
                        </label>

                    </div>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-primary w-full py-3.5 text-sm uppercase tracking-widest flex justify-center items-center gap-2 group shadow-[0_0_20px_rgba(99,102,241,0.4)]">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Deploy Hyper-Settings
            </button>
        </div>

        <!-- RIGHT COLUMN: LIVE PREVIEW PANELS -->
        <div class="xl:col-span-1 border border-white/5 rounded-2xl bg-black/40 overflow-hidden relative shadow-2xl backdrop-blur-md sticky top-6 self-start">
            <div class="bg-gradient-to-r from-indigo-500/20 to-purple-500/20 p-4 border-b border-white/5 flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></div>
                <h3 class="text-xs font-bold text-white uppercase tracking-widest">Syntax Hologram Preview</h3>
            </div>
            
            <div class="p-6">
                <!-- Preview Canvas -->
                <div class="bg-white rounded p-4 text-black shadow-lg relative transform transition-all duration-300" 
                     style="font-family: Arial, sans-serif; font-size: {{ $setting->font_size }};">
                     
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-6 border-b pb-4" style="border-color: {{ $setting->theme_color }}">
                        <div>
                            @if($company->logo)
                                <img src="{{ asset($company->logo) }}" class="h-8 w-auto mb-2 opacity-80" alt="Logo">
                            @else
                                <h1 class="font-black text-xl m-0 leading-tight" style="color: {{ $setting->theme_color }}">{{ $company->name }}</h1>
                            @endif
                            <p class="text-[10px] text-gray-500 m-0">{{ $company->phone }} | {{ $company->email }}</p>
                            <p class="text-[10px] text-gray-500 m-0 leading-tight w-3/4">{{ $company->address }}</p>
                            @if($company->gst_number)<p class="text-[10px] font-bold text-gray-700 m-0 mt-1">GSTIN: {{ $company->gst_number }}</p>@endif
                        </div>
                        <div class="text-right">
                            <h2 class="font-bold text-lg m-0 uppercase" style="color: {{ $setting->theme_color }}">{{ $setting->header_text ?: 'INVOICE' }}</h2>
                            <p class="text-[11px] font-bold m-0 mt-1">#{{ $setting->invoice_prefix }}{{ str_pad(2026, $setting->invoice_number_length, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[10px] text-gray-500 m-0">Date: 14 Aug 2026</p>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <table class="w-full text-left mb-4 border-collapse">
                        <thead>
                            <tr class="text-[10px] uppercase border-b-2" style="border-color: {{ $setting->theme_color }}">
                                <th class="py-1">Description</th>
                                @if($setting->show_hsn_sac)<th class="py-1">HSN</th>@endif
                                <th class="py-1 text-right">Qty</th>
                                <th class="py-1 text-right">Rate</th>
                                <th class="py-1 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="text-[11px]">
                            <tr class="border-b border-gray-100">
                                <td class="py-1.5 font-medium">RTX 4090 GPU</td>
                                @if($setting->show_hsn_sac)<td class="py-1.5 text-gray-500 text-[9px]">8473</td>@endif
                                <td class="py-1.5 text-right">1</td>
                                <td class="py-1.5 text-right">$1,599.00</td>
                                <td class="py-1.5 text-right font-bold">$1,599.00</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Totals -->
                    <div class="flex justify-end mb-6">
                        <div class="w-2/3 border-t border-gray-200 pt-2 text-[11px]">
                            <div class="flex justify-between mb-1"><span class="text-gray-500">Subtotal:</span><span>$1,599.00</span></div>
                            @if($setting->show_discount)<div class="flex justify-between mb-1 text-red-500"><span>Discount:</span><span>-$100.00</span></div>@endif
                            @if($setting->show_tax_breakup)
                            <div class="flex justify-between mb-1"><span class="text-gray-500">CGST (9%):</span><span>$134.91</span></div>
                            <div class="flex justify-between mb-1"><span class="text-gray-500">SGST (9%):</span><span>$134.91</span></div>
                            @else
                            <div class="flex justify-between mb-1"><span class="text-gray-500">Tax (18%):</span><span>$269.82</span></div>
                            @endif
                            <div class="flex justify-between font-bold text-[13px] pt-1 mt-1 border-t-2" style="border-color: {{ $setting->theme_color }}">
                                <span>Total:</span><span style="color: {{ $setting->theme_color }}">$1,768.82</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 relative">
                        <!-- Custom CSS logic for checkbox bindings makes checkbox visual, 
                             so in preview we rely just on DB value -->
                        <div class="text-center w-full">
                            <p class="text-[9px] font-bold m-0" style="color: {{ $setting->theme_color }}">{{ $setting->footer_message }}</p>
                            <p class="text-[8px] text-gray-500 m-0 px-4">{{ Str::limit($setting->terms_conditions, 40) }}</p>
                        </div>
                        
                        @if($setting->show_signature)
                        <div class="absolute bottom-6 right-0 text-right w-1/3 border-t border-gray-300 pt-1">
                            <p class="text-[8px] text-gray-400 font-bold m-0">Authorized Signature</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
// For pure UI aesthetics in AlpineJS
document.addEventListener('alpine:init', () => {
    // You can bind Alpine state for a real live preview matching input values
});
</script>
@endsection

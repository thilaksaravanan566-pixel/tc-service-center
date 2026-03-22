@extends('layouts.admin')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-3">
        <a href="{{ route('admin.notifications.index') }}" class="p-2 bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        Edit: {{ $template->name }}
    </h2>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl shadow-2xl overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-700/50 bg-gradient-to-r from-pink-900/20 to-transparent">
            <h3 class="text-lg font-bold text-gray-100">{{ $template->name }}</h3>
            <p class="text-xs text-gray-500 font-mono mt-1">Trigger Event: <span class="text-pink-400">{{ $template->event_trigger }}</span></p>
        </div>

        <form action="{{ route('admin.notifications.update', $template->id) }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            {{-- Available Variables Notice --}}
            <div class="flex items-start gap-3 p-4 bg-indigo-500/10 border border-indigo-500/30 rounded-xl">
                <svg class="w-5 h-5 text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="text-sm font-semibold text-indigo-300 mb-1">Available Template Variables</p>
                    <p class="text-xs text-gray-400">Use these placeholders in your messages — they'll be replaced with live data when sent:</p>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach(['{customer_name}', '{order_id}', '{service_id}', '{status}', '{amount}', '{company_name}', '{support_phone}', '{tracking_link}'] as $var)
                        <code class="text-xs bg-gray-800 text-pink-400 border border-gray-700 px-2 py-0.5 rounded">{{ $var }}</code>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Email Section --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-700/50">
                    <span class="text-xs bg-blue-500/20 text-blue-400 border border-blue-500/30 px-3 py-1 rounded-full font-bold uppercase tracking-wider">Email Channel</span>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Subject Line</label>
                    <input type="text" name="email_subject" value="{{ old('email_subject', $template->email_subject) }}"
                        placeholder="e.g. Your order #{order_id} has been confirmed!"
                        class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-3 text-gray-200 placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50 transition">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Email Body (HTML supported)</label>
                    <textarea name="email_body" rows="7" placeholder="Write your email content here. HTML is supported."
                        class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-3 text-gray-200 placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/50 transition font-mono text-sm resize-none">{{ old('email_body', $template->email_body) }}</textarea>
                </div>
            </div>

            {{-- SMS Section --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-700/50">
                    <span class="text-xs bg-green-500/20 text-green-400 border border-green-500/30 px-3 py-1 rounded-full font-bold uppercase tracking-wider">SMS Channel</span>
                    <span class="text-xs text-gray-500">Max 400 characters</span>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">SMS Message</label>
                    <textarea name="sms_body" rows="3" maxlength="400" placeholder="Short message for SMS delivery. Keep it concise."
                        class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-3 text-gray-200 placeholder-gray-600 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/50 transition text-sm resize-none">{{ old('sms_body', $template->sms_body) }}</textarea>
                </div>
            </div>

            {{-- WhatsApp Section --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-700/50">
                    <span class="text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-3 py-1 rounded-full font-bold uppercase tracking-wider">WhatsApp Channel</span>
                    <span class="text-xs text-gray-500">Max 700 characters</span>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">WhatsApp Message</label>
                    <textarea name="whatsapp_body" rows="4" maxlength="700" placeholder="Message for WhatsApp. You can use *bold*, _italic_ formatting."
                        class="w-full bg-gray-800/60 border border-gray-600/60 rounded-xl px-4 py-3 text-gray-200 placeholder-gray-600 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 transition text-sm resize-none">{{ old('whatsapp_body', $template->whatsapp_body) }}</textarea>
                </div>
            </div>

            {{-- Active Toggle --}}
            <div class="flex items-center justify-between p-4 bg-gray-800/40 rounded-xl border border-gray-700/50">
                <div>
                    <p class="font-semibold text-gray-200 text-sm">Enable This Template</p>
                    <p class="text-xs text-gray-500 mt-0.5">When disabled, no notifications will be sent for this event.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                        {{ $template->is_active ? 'checked' : '' }}>
                    <div class="w-14 h-7 bg-gray-700 rounded-full peer-checked:bg-emerald-500 transition-all after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:rounded-full after:h-[22px] after:w-[22px] after:transition-all peer-checked:after:translate-x-7 shadow-inner"></div>
                </label>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-700/50">
                <a href="{{ route('admin.notifications.index') }}" class="text-gray-400 hover:text-white text-sm transition">← Cancel</a>
                <button type="submit"
                    class="flex items-center gap-2 bg-pink-600 hover:bg-pink-500 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-pink-500/30 transition-all hover:scale-105">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Notification Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('header')
<div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-3">
        <a href="{{ route('admin.customization.index') }}?tab=notifications" class="p-2 bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="p-2 bg-pink-600/20 rounded-xl border border-pink-500/30">
            <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        Notification Templates
    </h2>
</div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto py-6">
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 bg-emerald-500/15 border border-emerald-500/40 text-emerald-400 px-5 py-3.5 rounded-xl">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="space-y-4">
        @foreach($templates as $tpl)
        <div class="bg-gray-900/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl overflow-hidden shadow-xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-700/30">
                <div>
                    <h3 class="font-bold text-gray-100 text-lg">{{ $tpl->name }}</h3>
                    <p class="text-xs text-gray-500 font-mono mt-1">Event: {{ $tpl->event_trigger }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.notifications.toggle', $tpl->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $tpl->is_active ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-red-500/20 hover:text-red-400 hover:border-red-500/30' : 'bg-gray-700/50 text-gray-400 border border-gray-600 hover:bg-emerald-500/20 hover:text-emerald-400 hover:border-emerald-500/30' }}">
                            <div class="w-1.5 h-1.5 rounded-full {{ $tpl->is_active ? 'bg-emerald-400' : 'bg-gray-500' }}"></div>
                            {{ $tpl->is_active ? 'Active' : 'Disabled' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.notifications.edit', $tpl->id) }}" class="flex items-center gap-1.5 bg-indigo-500/20 hover:bg-indigo-500/40 text-indigo-400 border border-indigo-500/30 px-4 py-1.5 rounded-lg text-sm font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Template
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-700/30">
                {{-- Email Preview --}}
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs bg-blue-500/20 text-blue-400 border border-blue-500/30 px-2 py-0.5 rounded-full font-medium">EMAIL</span>
                    </div>
                    @if($tpl->email_subject)
                        <p class="font-semibold text-gray-300 text-sm mb-2 truncate">{{ $tpl->email_subject }}</p>
                        <p class="text-xs text-gray-500 line-clamp-3">{{ strip_tags($tpl->email_body) }}</p>
                    @else
                        <p class="text-xs text-gray-600 italic">Not configured</p>
                    @endif
                </div>
                {{-- SMS Preview --}}
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded-full font-medium">SMS</span>
                    </div>
                    @if($tpl->sms_body)
                        <p class="text-xs text-gray-400 line-clamp-4">{{ $tpl->sms_body }}</p>
                    @else
                        <p class="text-xs text-gray-600 italic">Not configured</p>
                    @endif
                </div>
                {{-- WhatsApp Preview --}}
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded-full font-medium">WhatsApp</span>
                    </div>
                    @if($tpl->whatsapp_body)
                        <p class="text-xs text-gray-400 line-clamp-4">{{ $tpl->whatsapp_body }}</p>
                    @else
                        <p class="text-xs text-gray-600 italic">Not configured</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

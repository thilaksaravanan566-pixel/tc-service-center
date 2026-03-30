@extends('layouts.customer')
@section('title', 'AI Chat Bot')

@php $customerName = auth('customer')->user()->name ?? 'Customer'; @endphp

@section('content')
<div class="animate-slide-up" x-data="chatBot()" style="max-width:800px;margin:0 auto">
    
    <div class="page-header" style="text-align:center">
        <h1 class="page-title" style="display:flex;align-items:center;justify-content:center;gap:12px">
            <span style="font-size:1.5rem">🤖</span>
            Thambu AI Assistant
        </h1>
        <p class="page-sub">Ask me about your repairs, booking a service, or any tech questions!</p>
    </div>

    <div class="card" style="height:600px;display:flex;flex-direction:column;border:1px solid var(--border)">
        {{-- Chat Messages Area --}}
        <div style="flex:1;padding:24px;overflow-y:auto;background:#f8fafc;position:relative" id="chat-window">
            
            <template x-for="(msg, index) in messages" :key="index">
                <div :style="msg.type === 'user' ? 'display:flex;justify-content:flex-end;margin-bottom:16px' : 'display:flex;justify-content:flex-start;margin-bottom:16px'">
                    <div :style="msg.type === 'user'
                        ? 'background:#4f46e5;color:#fff;border-radius:18px 18px 4px 18px;padding:12px 18px;box-shadow:0 2px 8px rgba(79,70,229,0.3);max-width:80%'
                        : 'background:#fff;color:#1e293b;border:1px solid #e2e8f0;border-radius:18px 18px 18px 4px;padding:12px 18px;box-shadow:0 1px 4px rgba(0,0,0,0.06);max-width:80%'">
                        <p style="font-size:0.875rem;font-weight:500;line-height:1.5;margin:0" x-text="msg.text"></p>
                        <span style="font-size:0.65rem;opacity:0.6;display:block;margin-top:4px" :style="msg.type === 'user' ? 'text-align:right' : 'text-align:left'" x-text="msg.time"></span>
                    </div>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div x-show="isTyping" style="display:flex;justify-content:flex-start;margin-bottom:16px" x-transition>
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:18px 18px 18px 4px;padding:14px 18px;box-shadow:0 1px 4px rgba(0,0,0,0.06);display:flex;gap:6px;align-items:center">
                    <span style="width:8px;height:8px;border-radius:50%;background:#818cf8;display:inline-block;animation:bounce 0.8s infinite"></span>
                    <span style="width:8px;height:8px;border-radius:50%;background:#818cf8;display:inline-block;animation:bounce 0.8s 0.2s infinite"></span>
                    <span style="width:8px;height:8px;border-radius:50%;background:#818cf8;display:inline-block;animation:bounce 0.8s 0.4s infinite"></span>
                </div>
            </div>
            
        </div>

        {{-- Chat Input Area --}}
        <div style="padding:16px;background:#fff;border-top:1px solid #e2e8f0">
            <form @submit.prevent="sendMessage" style="display:flex;align-items:center;gap:12px">
                <input type="text" x-model="newMessage" placeholder="Type your message here..." class="super-input" style="flex:1;border-radius:50px;padding:12px 20px" :disabled="isTyping">
                <button type="submit" class="btn btn-primary" style="border-radius:50%;width:48px;height:48px;padding:0;display:flex;align-items:center;justify-content:center;flex-shrink:0" :disabled="!newMessage.trim() || isTyping">
                    <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
}
</style>

<script>
(function() {
    var customerName = {{ json_encode($customerName) }};

    document.addEventListener('alpine:init', function() {
        Alpine.data('chatBot', function() {
            return {
                messages: [
                    {
                        type: 'bot',
                        text: 'Hello ' + customerName + '! I am Thambu AI. I can help you with your services, status tracking, or finding the right spare parts. How can I assist you today?',
                        time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                    }
                ],
                newMessage: '',
                isTyping: false,

                sendMessage: function() {
                    if (!this.newMessage.trim()) return;

                    var text = this.newMessage;
                    this.newMessage = '';

                    this.messages.push({
                        type: 'user',
                        text: text,
                        time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                    });
                    this.scrollToBottom();

                    this.isTyping = true;
                    this.scrollToBottom();

                    var self = this;
                    fetch('{{ route("customer.chat.message") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: text })
                    })
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        self.isTyping = false;
                        self.messages.push({
                            type: 'bot',
                            text: data.reply,
                            time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                        });
                        self.scrollToBottom();
                    })
                    .catch(function() {
                        self.isTyping = false;
                        self.messages.push({
                            type: 'bot',
                            text: 'Sorry, I am having trouble right now. Please try again later.',
                            time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                        });
                        self.scrollToBottom();
                    });
                },

                scrollToBottom: function() {
                    setTimeout(function() {
                        var el = document.getElementById('chat-window');
                        if (el) el.scrollTop = el.scrollHeight;
                    }, 50);
                }
            };
        });
    });
})();
</script>
@endsection

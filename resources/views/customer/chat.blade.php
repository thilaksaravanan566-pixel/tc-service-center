@extends('layouts.customer')
@section('title', 'AI Chat Bot')

@section('content')
<div class="animate-slide-up" x-data="chatBot()" style="max-width:800px;margin:0 auto">
    
    <div class="page-header text-center">
        <h1 class="page-title flex items-center justify-center gap-3">
            <span class="p-2 bg-indigo-500/10 text-indigo-500 rounded-xl">🤖</span>
            Thambu AI Assistant
        </h1>
        <p class="page-sub">Ask me about your repairs, booking a service, or any tech questions!</p>
    </div>

    <div class="card flex flex-col" style="height:600px;border:1px solid var(--border)">
        {{-- Chat Messages Area --}}
        <div class="flex-1 p-6 overflow-y-auto bg-slate-50 relative" id="chat-window">
            
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.type === 'user' ? 'flex justify-end mb-4' : 'flex justify-start mb-4'">
                    <div :class="msg.type === 'user' ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-sm px-5 py-3 shadow-md max-w-[80%]' : 'bg-white text-slate-800 border border-slate-200 rounded-2xl rounded-tl-sm px-5 py-3 shadow-sm max-w-[80%]'">
                        <p class="text-sm font-medium leading-relaxed" x-text="msg.text"></p>
                        <span class="text-[10px] opacity-70 block mt-1" :class="msg.type === 'user' ? 'text-right' : 'text-left'" x-text="msg.time"></span>
                    </div>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div x-show="isTyping" class="flex justify-start mb-4" x-transition>
                <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm px-5 py-3 shadow-sm max-w-[80%] flex gap-1 items-center">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-bounce"></span>
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 0.4s"></span>
                </div>
            </div>
            
        </div>

        {{-- Chat Input Area --}}
        <div class="p-4 bg-white border-t border-slate-200">
            <form @submit.prevent="sendMessage" class="flex items-center gap-3">
                <input type="text" x-model="newMessage" placeholder="Type your message here..." class="super-input flex-1 !rounded-full !py-3 !px-5" :disabled="isTyping">
                <button type="submit" class="btn btn-primary !rounded-full !w-12 !h-12 !p-0 flex items-center justify-center flex-shrink-0" :disabled="!newMessage.trim() || isTyping">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9-18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('chatBot', () => ({
        messages: [
            { type: 'bot', text: 'Hello {{ auth('customer')->user()->name ?? 'Customer' }}! I am Thambu AI. I can help you with your services, status tracking, or finding the right spare parts. How can I assist you today?', time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }
        ],
        newMessage: '',
        isTyping: false,
        
        sendMessage() {
            if(!this.newMessage.trim()) return;
            
            const text = this.newMessage;
            this.newMessage = '';
            
            // Add user message
            this.messages.push({
                type: 'user',
                text: text,
                time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
            });
            this.scrollToBottom();
            
            this.isTyping = true;
            this.scrollToBottom();

            // Send to backend
            fetch('{{ route('customer.chat.message') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: text })
            })
            .then(res => res.json())
            .then(data => {
                this.isTyping = false;
                this.messages.push({
                    type: 'bot',
                    text: data.reply,
                    time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                });
                this.scrollToBottom();
            })
            .catch(err => {
                this.isTyping = false;
                this.messages.push({
                    type: 'bot',
                    text: 'Sorry, I am having trouble connecting to my neural network right now. Please try again later.',
                    time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                });
                this.scrollToBottom();
            });
        },
        
        scrollToBottom() {
            setTimeout(() => {
                const el = document.getElementById('chat-window');
                if(el) el.scrollTop = el.scrollHeight;
            }, 50);
        }
    }));
});
</script>
@endsection

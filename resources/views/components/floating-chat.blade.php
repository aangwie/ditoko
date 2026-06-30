@if(auth()->check())
@php
    $userRole = auth()->user()->role;
    $isAdmin = $userRole === 'admin';
    $isBuyer = $userRole === 'buyer';
    
    if ($isBuyer) {
        $admin = \App\Models\User::where('role', 'admin')->first();
        $chatPartnerId = $admin ? $admin->id : null;
        $chatPartnerName = $admin ? $admin->name : 'Admin';
    } else {
        // For admin, get first buyer with messages or null
        $firstBuyer = \App\Models\Message::where('receiver_id', auth()->id())
            ->orWhere('sender_id', auth()->id())
            ->with('sender', 'receiver')
            ->latest()
            ->first();
        $chatPartnerId = null; // Admin will be redirected to full chat page
        $chatPartnerName = 'Chat';
    }
@endphp

<!-- Floating Chat Widget -->
<div x-data="{
    open: false,
    messages: [],
    selectedBuyer: null,
    newMessage: '',
    loading: false,
    lastId: 0,
    unreadCount: {{ \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count() }},
    buyerId: {{ $chatPartnerId ?? 'null' }},
    isAdmin: {{ $isAdmin ? 'true' : 'false' }},
    chatUrl: '{{ route("chat.fetch", ":id") }}',
    
    init() {
        
        // Set chat URL based on user role
        if (this.buyerId) {
            this.chatUrl = this.chatUrl.replace(':id', this.buyerId);
            this.fetchMessages();
            setInterval(() => this.fetchMessages(), 4000);
        }
    },
    
    getChatUrl() {
        return this.chatUrl.replace(':id', this.buyerId);
    },
    
    async fetchMessages() {
        if (!this.buyerId) return;
        try {
            const r = await fetch(`/chat/fetch/${this.buyerId}?last_id=${this.lastId}`);
            const data = await r.json();
            if (data.length > 0) {
                this.messages = [...this.messages, ...data.filter(m => m.id > this.lastId)];
                this.lastId = Math.max(...this.messages.map(m => m.id));
                // Update unread count after fetching messages
                this.unreadCount = Math.max(0, this.unreadCount - data.length);
                this.$nextTick(() => this.scrollToBottom());
            }
        } catch (e) {
            console.error('Fetch error:', e);
        }
    },
    
    async sendMessage() {
        if (!this.newMessage.trim() || this.loading || !this.buyerId) return;
        this.loading = true;
        try {
            const r = await fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({receiver_id: this.buyerId, message: this.newMessage})
            });
            const msg = await r.json();
            this.messages.push(msg);
            this.lastId = msg.id;
            this.newMessage = '';
            this.unreadCount = 0;
            this.$nextTick(() => this.scrollToBottom());
        } catch (e) {
            console.error('Send error:', e);
        } finally {
            this.loading = false;
        }
    },
    
    scrollToBottom() {
        const c = document.getElementById('chat-messages');
        if (c) c.scrollTop = c.scrollHeight;
    },
    
    toggleChat() {
        // For admin, redirect to full chat page
        if (this.isAdmin) {
            window.location.href = '{{ route("chat.index") }}';
            return;
        }
        
        // For buyer, toggle the widget
        this.open = !this.open;
        if (this.open && this.unreadCount > 0) {
            this.unreadCount = 0;
        }
    }
}" 
x-init="init()"
class="fixed bottom-4 right-4 z-50 flex flex-col items-end"
style="z-index: 50;">

    <!-- Chat Widget Toggle Button -->
    <button @click="toggleChat()" 
            class="bg-ditoko-navy hover:bg-blue-900 text-white rounded-full p-4 shadow-lg transition-all duration-300 ease-in-out mb-4"
            :class="open ? 'rotate-90' : ''"
            style="width: 60px; height: 60px; transition: transform 0.3s ease-in-out;">
        <svg x-show="!open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg x-show="open" x-cloak class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <!-- Unread Badge -->
        <span x-show="unreadCount > 0" 
              class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center -mt-1 -mr-1 border-2 border-white"
              x-text="unreadCount">
        </span>
    </button>

    <!-- Chat Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-4"
         class="bg-white rounded-xl shadow-2xl w-80 md:w-96 overflow-hidden mb-4"
         style="max-height: 600px;">
        
    <!-- Chat Header -->
        <div class="bg-ditoko-navy text-white p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-ditoko-navy font-semibold">
                        @php
                            $adminName = $admin->name ?? \App\Models\User::where('role', 'admin')->first()?->name ?? 'Admin';
                        @endphp
                        <span>{{ substr($adminName, 0, 1) }}</span>
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold" x-text="{{ json_encode($adminName) }}"></p>
                        <p class="text-xs opacity-80 flex items-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                            Online
                        </p>
                    </div>
                </div>
                <button @click="open = false" class="text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div id="chat-messages" class="h-64 md:h-80 overflow-y-auto p-4 bg-gray-50 space-y-3">
            <template x-if="messages.length === 0">
                <div class="text-center text-gray-500 py-8">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-sm">Mulai percakapan sekarang!</p>
                </div>
            </template>
            <template x-for="msg in messages" :key="msg.id">
                <div :class="msg.sender_id == {{ auth()->id() }} ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="msg.sender_id == {{ auth()->id() }} ? 'bg-ditoko-orange text-white' : 'bg-white border border-gray-200'" 
                         class="max-w-[85%] px-3 py-2 rounded-lg shadow-sm text-sm">
                        <p x-text="msg.message"></p>
                        <p class="text-xs mt-0.5 opacity-75 text-right" x-text="new Date(msg.created_at).toLocaleString('id-ID', {hour: '2-digit', minute: '2-digit'})"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-200">
            <form @submit.prevent="sendMessage" class="flex gap-2">
                <input type="text" 
                       x-model="newMessage" 
                       placeholder="Ketik pesan..." 
                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-ditoko-navy focus:outline-none"
                       :disabled="loading"
                       maxlength="1000">
                <button type="submit" 
                        class="bg-ditoko-navy text-white rounded-lg px-3 py-2 hover:bg-blue-900 transition-colors disabled:opacity-50"
                        :disabled="loading || !newMessage.trim()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!loading">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <svg x-show="loading" x-cloak class="w-5 h-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
@endif

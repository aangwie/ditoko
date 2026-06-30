<x-buyer-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">💬 Chat dengan Admin</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div x-data="{
                        messages: [],
                        newMessage: '',
                        adminId: {{ $admin ? $admin->id : 'null' }},
                        loading: false,
                        lastId: 0,
                        async fetchMessages() {
                            if (!this.adminId) return;
                            try {
                                const r = await fetch(`/chat/fetch/${this.adminId}?last_id=${this.lastId}`);
                                const data = await r.json();
                                if (data.length > 0) {
                                    this.messages = [...this.messages, ...data.filter(m => m.id > this.lastId)];
                                    this.lastId = Math.max(...this.messages.map(m => m.id));
                                    this.$nextTick(() => this.scrollToBottom());
                                }
                            } catch (e) {
                                console.error(e);
                            }
                        },
                        async sendMessage() {
                            if (!this.newMessage.trim() || this.loading) return;
                            this.loading = true;
                            try {
                                const r = await fetch('/chat/send', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                    },
                                    body: JSON.stringify({receiver_id: this.adminId, message: this.newMessage})
                                });
                                const msg = await r.json();
                                this.messages.push(msg);
                                this.lastId = msg.id;
                                this.newMessage = '';
                                this.$nextTick(() => this.scrollToBottom());
                            } catch (e) {
                                alert('Gagal mengirim pesan');
                            } finally {
                                this.loading = false;
                            }
                        },
                        scrollToBottom() {
                            const c = document.getElementById('chat-messages');
                            if (c) c.scrollTop = c.scrollHeight;
                        }
                    }" x-init="fetchMessages(); setInterval(() => fetchMessages(), 4000)">
                        
                        @if($admin)
                            <div class="mb-4 pb-4 border-b">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-ditoko-navy flex items-center justify-center text-white font-semibold text-lg">
                                        {{ substr($admin->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-semibold text-lg">{{ $admin->name }}</h3>
                                        <p class="text-sm text-green-600 flex items-center">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                            Online
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div id="chat-messages" class="h-96 overflow-y-auto mb-4 space-y-3 bg-gray-50 rounded-lg p-4">
                                <template x-if="messages.length === 0">
                                    <div class="text-center text-gray-500 py-8">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <p>Belum ada pesan. Mulai percakapan sekarang!</p>
                                    </div>
                                </template>
                                <template x-for="msg in messages" :key="msg.id">
                                    <div :class="msg.sender_id == {{ auth()->id() }} ? 'flex justify-end' : 'flex justify-start'">
                                        <div :class="msg.sender_id == {{ auth()->id() }} ? 'bg-ditoko-orange text-white' : 'bg-white border border-gray-200'" 
                                             class="max-w-xs lg:max-w-md px-4 py-3 rounded-lg shadow-sm">
                                            <p class="text-sm" x-text="msg.message"></p>
                                            <p class="text-xs mt-1 opacity-75" x-text="new Date(msg.created_at).toLocaleString('id-ID', {hour: '2-digit', minute: '2-digit', day: 'numeric', month: 'short'})"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <form @submit.prevent="sendMessage" class="flex gap-3">
                                <input type="text" 
                                       x-model="newMessage" 
                                       placeholder="Ketik pesan Anda..." 
                                       class="flex-1 border-gray-300 rounded-lg focus:border-ditoko-orange focus:ring-ditoko-orange"
                                       maxlength="1000"
                                       :disabled="loading">
                                <button type="submit" 
                                        class="px-6 py-2 bg-ditoko-orange text-white rounded-lg hover:bg-orange-700 transition-colors disabled:opacity-50"
                                        :disabled="loading || !newMessage.trim()">
                                    <span x-show="!loading">Kirim</span>
                                    <span x-show="loading">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </form>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-red-600 font-semibold">Admin tidak ditemukan</p>
                                <p class="text-gray-500 text-sm mt-2">Silakan hubungi administrator sistem.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-buyer-layout>

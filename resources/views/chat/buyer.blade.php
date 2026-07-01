@extends('buyer.layouts.app')

@section('title', 'Chat dengan Admin')

@section('content')
    <div class="mb-6">
        <h2 class="font-semibold text-2xl text-gray-800">💬 Chat dengan Admin</h2>
    </div>

    <div class="max-w-4xl mx-auto" x-data="chatBuyer()" x-init="init()">
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-ditoko-navy text-white px-6 py-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-ditoko-navy font-bold text-lg">
                        {{ substr($admin->name ?? 'Admin', 0, 1) }}
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-lg">{{ $admin->name ?? 'Admin' }}</h3>
                        <p class="text-sm opacity-80">Online</p>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div id="chat-messages" class="h-96 overflow-y-auto p-6 bg-gray-50 space-y-4">
                <template x-if="messages.length === 0">
                    <div class="flex items-center justify-center h-full text-gray-500">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="text-lg font-medium">Belum ada pesan</p>
                            <p class="text-sm mt-1 opacity-75">Mulai percakapan dengan admin</p>
                        </div>
                    </div>
                </template>
                <template x-for="msg in messages" :key="msg.id">
                    <div :class="msg.sender_id == {{ auth()->id() }} ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.sender_id == {{ auth()->id() }} ? 'bg-ditoko-orange text-white' : 'bg-white border border-gray-200'"
                             class="max-w-xs lg:max-w-md px-4 py-3 rounded-lg shadow-sm">
                            <p class="text-sm break-words" x-text="msg.message"></p>
                            <!-- Attachment -->
                            <template x-if="msg.attachment">
                                <div class="mt-2">
                                    <template x-if="isImageFile(msg.attachment)">
                                        <img @click="openLightbox(attachmentUrl(msg.attachment), 'image')"
                                             :src="attachmentUrl(msg.attachment)"
                                             class="max-w-full h-auto rounded-lg max-h-48 object-cover cursor-pointer hover:opacity-90">
                                    </template>
                                    <template x-if="isPdfFile(msg.attachment)">
                                        <a @click.prevent="openLightbox(attachmentUrl(msg.attachment), 'pdf')"
                                           href="#"
                                           class="flex items-center gap-2 text-sm underline hover:opacity-75 cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            <span x-text="msg.attachment.split('/').pop()"></span>
                                        </a>
                                    </template>
                                    <template x-if="!isImageFile(msg.attachment) && !isPdfFile(msg.attachment)">
                                        <a @click.prevent="openLightbox(attachmentUrl(msg.attachment), 'other')"
                                           href="#"
                                           class="flex items-center gap-2 text-sm underline hover:opacity-75 cursor-pointer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span x-text="msg.attachment.split('/').pop()"></span>
                                        </a>
                                    </template>
                                </div>
                            </template>
                            <p class="text-xs mt-1 opacity-75" x-text="new Date(msg.created_at).toLocaleString('id-ID', {hour: '2-digit', minute: '2-digit', day: 'numeric', month: 'short'})"></p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Input -->
            <div class="border-t p-4 bg-white">
                <!-- Attachment preview -->
                <template x-if="attachmentFile">
                    <div class="mb-2 flex items-center gap-2 p-2 bg-gray-100 rounded-lg">
                        <template x-if="attachmentPreview">
                            <img :src="attachmentPreview" class="w-10 h-10 object-cover rounded">
                        </template>
                        <template x-if="!attachmentPreview">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </template>
                        <span class="text-sm truncate" x-text="attachmentName"></span>
                        <button @click="removeAttachment" type="button" class="ml-auto text-red-500 hover:text-red-700">&times;</button>
                    </div>
                </template>
                <form @submit.prevent="sendMessage" class="flex gap-3">
                    <input type="text"
                           x-model="newMessage"
                           placeholder="Ketik pesan..."
                           class="flex-1 border-gray-300 rounded-lg focus:border-ditoko-navy focus:ring-ditoko-navy"
                           :disabled="loading"
                           maxlength="1000">
                    <label class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 cursor-pointer transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        <input type="file" @change="handleFileSelect" ref="fileInput" class="hidden" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx">
                    </label>
                    <button type="submit"
                            class="px-6 py-2 bg-ditoko-navy text-white rounded-lg hover:bg-blue-900 transition-colors disabled:opacity-50"
                            :disabled="loading || (!newMessage.trim() && !attachmentFile)">
                        <span x-show="!loading">Kirim</span>
                        <span x-show="loading" x-cloak>
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Lightbox overlay -->
        <template x-if="lightboxOpen">
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80" @click.self="closeLightbox">
                <div class="relative mx-4"
                     :class="lightboxType === 'pdf' ? 'max-w-4xl w-full' : 'max-w-[400px] w-full'"
                     :style="lightboxType === 'pdf' ? 'height: auto' : 'height: 600px'">
                    <button @click="closeLightbox" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl z-10">&times;</button>
                    <template x-if="lightboxType === 'image'">
                        <img :src="lightboxSrc" class="w-full h-full object-contain rounded">
                    </template>
                    <template x-if="lightboxType === 'pdf'">
                        <iframe :src="lightboxSrc" class="w-full h-[600px] rounded" frameborder="0"></iframe>
                    </template>
                    <template x-if="lightboxType === 'other'">
                        <div class="flex flex-col items-center justify-center text-white" :class="lightboxType === 'pdf' ? '' : 'h-full'">
                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-lg mb-4">File tidak bisa ditampilkan</p>
                            <a :href="lightboxSrc" target="_blank" class="px-4 py-2 bg-white text-black rounded hover:bg-gray-200">Download</a>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('chatBuyer', () => ({
        messages: [],
        newMessage: '',
        loading: false,
        lastId: 0,
        adminId: {{ $admin->id ?? 'null' }},
        attachmentFile: null,
        attachmentPreview: null,
        attachmentName: '',
        lightboxOpen: false,
        lightboxSrc: '',
        lightboxType: '',

        init() {
            this.fetchMessages();
            setInterval(() => this.fetchMessages(), 4000);
        },

        async fetchMessages() {
            if (!this.adminId) return;
            try {
                const r = await fetch(`/chat/fetch/${this.adminId}?last_id=${this.lastId}`);
                const data = await r.json();
                if (data.length > 0) {
                    this.messages = [...this.messages, ...data.filter(m => m.id > this.lastId)];
                    this.lastId = Math.max(...this.messages.map(m => m.id));
                    this.scrollToBottom();
                }
            } catch (e) {
                console.error('Fetch error:', e);
            }
        },

        handleFileSelect(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.attachmentFile = file;
            this.attachmentName = file.name;
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (ev) => this.attachmentPreview = ev.target.result;
                reader.readAsDataURL(file);
            } else {
                this.attachmentPreview = null;
            }
        },

        removeAttachment() {
            this.attachmentFile = null;
            this.attachmentPreview = null;
            this.attachmentName = '';
            this.$refs.fileInput.value = '';
        },

        isImageFile(name) {
            return /\.(jpg|jpeg|png|gif|webp)$/i.test(name);
        },

        isPdfFile(name) {
            return /\.pdf$/i.test(name);
        },

        attachmentUrl(path) {
            return '/storage/' + path;
        },

        openLightbox(src, type) {
            this.lightboxSrc = src;
            this.lightboxType = type;
            this.lightboxOpen = true;
        },

        closeLightbox() {
            this.lightboxOpen = false;
            this.lightboxSrc = '';
            this.lightboxType = '';
        },

        async sendMessage() {
            if ((!this.newMessage.trim() && !this.attachmentFile) || !this.adminId) return;
            this.loading = true;
            try {
                const fd = new FormData();
                fd.append('receiver_id', this.adminId);
                fd.append('message', this.newMessage);
                if (this.attachmentFile) fd.append('attachment', this.attachmentFile);
                const r = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                    body: fd
                });
                const msg = await r.json();
                this.messages.push(msg);
                this.lastId = msg.id;
                this.newMessage = '';
                this.attachmentFile = null;
                this.attachmentPreview = null;
                this.attachmentName = '';
                this.scrollToBottom();
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
    }));
});
</script>
@endpush
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $favicon = \App\Models\Setting::get('site_favicon') ?: \App\Models\Setting::get('site_logo') ?: asset('favicon.ico'); @endphp
    <link rel="icon" type="image/svg+xml" href="{{ $favicon }}">
    <title>Syarat & Ketentuan - {{ \App\Models\Setting::get('site_name', 'DiToko') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-ditoko-navy text-white sticky top-0 z-50 shadow-lg">
        <div class="max-w-5xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-ditoko-navy font-bold text-xl">D</span>
                    </div>
                    <span class="text-2xl font-bold">DiToko</span>
                </a>
                <a href="{{ route('home') }}" class="px-4 py-2 bg-ditoko-orange rounded-lg hover:bg-orange-700 transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-ditoko-navy to-blue-800 text-white px-8 py-6">
                <h1 class="text-3xl font-bold">Syarat & Ketentuan</h1>
                <p class="text-blue-200 mt-2">Terakhir diperbarui: Juni 2026</p>
            </div>
            
            <div class="prose prose-lg max-w-none px-8 py-8 space-y-8 text-gray-700">
                <!-- Section 1 -->
                <section>
                    <h2 class="text-2xl font-bold text-ditoko-navy mb-4 flex items-center">
                        <span class="w-8 h-8 bg-ditoko-orange rounded-lg flex items-center justify-center text-white text-sm mr-3">1</span>
                        Pendahuluan
                    </h2>
                    <p class="leading-relaxed">
                        Selamat datang di DiToko. Dengan mengakses dan menggunakan layanan kami, Anda setuju untuk terikat oleh syarat dan ketentuan ini. Silakan baca dengan seksama sebelum menggunakan layanan kami.
                    </p>
                </section>

                <!-- Section 2 -->
                <section>
                    <h2 class="text-2xl font-bold text-ditoko-navy mb-4 flex items-center">
                        <span class="w-8 h-8 bg-ditoko-orange rounded-lg flex items-center justify-center text-white text-sm mr-3">2</span>
                        Produk Digital
                    </h2>
                    <div class="bg-blue-50 rounded-xl p-6 space-y-4">
                        <div>
                            <h3 class="font-semibold text-lg mb-2">2.1 Licensi Penggunaan</h3>
                            <p>Semua produk digital yang dibeli melalui DiToko diberikan dengan lisensi <strong>penggunaan pribadi</strong>. Anda berhak menggunakan produk untuk keperluan sendiri dan tidak diperbolehkan mendistribusikan, menjual, atau Mentransfer kepada pihak lain.</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2">2.2 Larangan Redistribusi</h3>
                            <p class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-2 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Dilarang keras mendistribusikan ulang, menjual, atau membagikan produk digital kepada orang lain baik secara gratis maupun berbayar.</span>
                            </p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-2">2.3 File Digital</h3>
                            <p>Produk digital berupa e-book, software, template, atau file lainnya hanya dapat diunduh oleh akun yang telah完成 pembayaran. Setiap akun bertanggung jawab untuk menjaga keamanan akses mereka.</p>
                        </div>
                    </div>
                </section>

                <!-- Section 3 -->
                <section>
                    <h2 class="text-2xl font-bold text-ditoko-navy mb-4 flex items-center">
                        <span class="w-8 h-8 bg-ditoko-orange rounded-lg flex items-center justify-center text-white text-sm mr-3">3</span>
                        Pembayaran
                    </h2>
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="font-semibold text-lg mb-3">3.1 Metode Pembayaran</h3>
                            <ul class="list-disc list-inside space-y-2">
                                <li>Midtrans - Pembayaran melalui berbagai channel (GoPay, OVO, Dana, Bank Transfer, dll)</li>
                                <li>Transfer Manual - Transfer ke rekening bank yang tertera</li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="font-semibold text-lg mb-3">3.2 Konfirmasi Pembayaran</h3>
                            <p>Untuk pembayaran transfer manual, Anda diwajibkan mengunggah bukti transfer. Pembayaran akan diverifikasi oleh admin dalam 1x24 jam kerja.</p>
                        </div>
                    </div>
                </section>

                <!-- Section 4 -->
                <section>
                    <h2 class="text-2xl font-bold text-ditoko-navy mb-4 flex items-center">
                        <span class="w-8 h-8 bg-ditoko-orange rounded-lg flex items-center justify-center text-white text-sm mr-3">4</span>
                        Kebijakan Refund
                    </h2>
                    <div class="bg-red-50 rounded-xl p-6 border border-red-200">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-lg text-red-800 mb-2">Tidak Ada Refund</h3>
                                <p class="text-red-700">Karena produk digital dapat diduplikasi, <strong>pembelian produk digital NÃO dapat dikembalikan/refund</strong>. Pastikan Anda telah membaca deskripsi produk dengan seksama sebelum membeli.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 5 -->
                <section>
                    <h2 class="text-2xl font-bold text-ditoko-navy mb-4 flex items-center">
                        <span class="w-8 h-8 bg-ditoko-orange rounded-lg flex items-center justify-center text-white text-sm mr-3">5</span>
                        Akun & Keamanan
                    </h2>
                    <div class="space-y-4">
                        <p>Anda bertanggung jawab untuk:</p>
                        <ul class="list-disc list-inside space-y-2 bg-gray-50 rounded-xl p-6">
                            <li>Menjaga kerahasiaan kata sandi akun Anda</li>
                            <li>Tidak membagikan akses akun kepada orang lain</li>
                            <li>Menggunakan produk hanya untuk akun yang telah 完成 pembayaran</li>
                            <li>Melaporkan immediately jika ada aktivitas mencurigakan</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 6 -->
                <section>
                    <h2 class="text-2xl font-bold text-ditoko-navy mb-4 flex items-center">
                        <span class="w-8 h-8 bg-ditoko-orange rounded-lg flex items-center justify-center text-white text-sm mr-3">6</span>
                        Dukungan & Kontak
                    </h2>
                    <div class="bg-ditoko-navy text-white rounded-xl p-6">
                        <p class="mb-4">Jika Anda memiliki pertanyaan tentang syarat dan ketentuan ini, silakan hubungi kami:</p>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('chat.index') }}" class="px-4 py-2 bg-ditoko-orange rounded-lg hover:bg-orange-700 transition-colors">
                                💬 Chat dengan Admin
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-ditoko-navy text-white py-8 mt-12">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center space-x-2 mb-4">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                    <span class="text-ditoko-navy font-bold">D</span>
                </div>
                <span class="text-xl font-bold">DiToko</span>
            </div>
            <p class="text-blue-200 text-sm">&copy; 2026 DiToko. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
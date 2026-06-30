@extends('admin.layouts.app')

@section('title', 'Dokumentasi Konfigurasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    {{-- Google OAuth --}}
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-xl font-bold text-ditoko-navy mb-6 flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Konfigurasi Google OAuth
            </h3>

            <div class="prose prose-blue max-w-none">
                <h4 class="text-lg font-semibold text-gray-800 mt-0">1. Buat Project di Google Cloud Console</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600">
                    <li>Buka <a href="https://console.cloud.google.com" target="_blank" class="text-ditoko-navy underline">Google Cloud Console</a></li>
                    <li>Buat project baru atau pilih project yang sudah ada</li>
                    <li>Navigasi ke <strong>APIs & Services > OAuth consent screen</strong></li>
                    <li>Pilih <strong>External</strong> (atau Internal jika domain terverifikasi)</li>
                    <li>Isi App name, Support email, dan Developer contact</li>
                    <li>Tambahkan scope: <code>email</code>, <code>profile</code></li>
                    <li>Tambahkan test users (email yang diizinkan saat mode testing)</li>
                </ol>

                <h4 class="text-lg font-semibold text-gray-800 mt-6">2. Buat OAuth 2.0 Client ID</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600">
                    <li>Navigasi ke <strong>APIs & Services > Credentials</strong></li>
                    <li>Klik <strong>Create Credentials > OAuth client ID</strong></li>
                    <li>Pilih Application type: <strong>Web application</strong></li>
                    <li>Isi Name (misal: "DiToko Login")</li>
                    <li>Tambahkan <strong>Authorized JavaScript origins</strong>:
                        <br><code class="bg-gray-100 px-2 py-0.5 rounded text-sm">{{ config('app.url') }}</code>
                    </li>
                    <li>Tambahkan <strong>Authorized redirect URIs</strong>:
                        <br><code class="bg-gray-100 px-2 py-0.5 rounded text-sm">{{ route('google.callback') }}</code>
                    </li>
                    <li>Klik <strong>Create</strong>, copy <strong>Client ID</strong> dan <strong>Client Secret</strong></li>
                </ol>

                <h4 class="text-lg font-semibold text-gray-800 mt-6">3. Input di Halaman Settings</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600">
                    <li>Buka menu <strong>Settings</strong> di sidebar admin</li>
                    <li>Scroll ke bagian <strong>Konfigurasi Google OAuth</strong></li>
                    <li>Isi <strong>Client ID</strong> dan <strong>Client Secret</strong> dari langkah sebelumnya</li>
                    <li>Klik <strong>Simpan Google OAuth</strong></li>
                </ol>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                    <p class="text-sm text-yellow-700">
                        <strong>Penting:</strong> Aplikasi harus dalam mode <strong>Testing</strong> atau <strong>Published</strong> agar Google login bisa digunakan oleh semua user.
                        Setelah siap production, publish project di halaman <strong>OAuth consent screen</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- WhatsApp API --}}
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-xl font-bold text-ditoko-navy mb-6 flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Konfigurasi WhatsApp Gateway
            </h3>

            <div class="prose prose-blue max-w-none">
                <h4 class="text-lg font-semibold text-gray-800 mt-0">Pilih Provider WhatsApp Gateway</h4>
                <p class="text-gray-600">Saat ini sistem mendukung provider berikut:</p>
                <ul class="list-disc list-inside space-y-2 text-gray-600">
                    <li><strong>Fonnte</strong> — <code>https://api.fonnte.com</code></li>
                    <li>Dapat dikembangkan untuk provider lain melalui kelas <code>App\Services\WhatsAppService</code></li>
                </ul>

                <h4 class="text-lg font-semibold text-gray-800 mt-6">1. Daftar / Login ke Fonnte</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600">
                    <li>Buka <a href="https://fonnte.com" target="_blank" class="text-ditoko-navy underline">fonnte.com</a></li>
                    <li>Daftar akun (gratis tersedia) dan login</li>
                    <li>Setelah login, navigasi ke dashboard</li>
                </ol>

                <h4 class="text-lg font-semibold text-gray-800 mt-6">2. Dapatkan API Key</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600">
                    <li>Di dashboard Fonnte, buka menu <strong>API / Token</strong> atau <strong>Settings</strong></li>
                    <li>Copy <strong>API Key / Token</strong> yang tersedia</li>
                    <li>Pastikan akun sudah <strong>terverifikasi</strong> (nomor & email)</li>
                    <li>Untuk testing, pastikan <strong>saldo mencukupi</strong> (Fonnte memberikan saldo gratis terbatas)</li>
                </ol>

                <h4 class="text-lg font-semibold text-gray-800 mt-6">3. Input di Halaman Settings</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600">
                    <li>Buka menu <strong>Settings</strong> di sidebar admin</li>
                    <li>Scroll ke bagian <strong>Konfigurasi WhatsApp Gateway</strong></li>
                    <li>Isi <strong>API Key</strong> dari Fonnte</li>
                    <li>Isi <strong>Nomor Pengirim</strong> — nomor WhatsApp yang akan mengirim notifikasi</li>
                    <li>Klik <strong>Simpan Pengaturan WhatsApp</strong></li>
                </ol>

                <h4 class="text-lg font-semibold text-gray-800 mt-6">4. Test Kirim Pesan</h4>
                <ol class="list-decimal list-inside space-y-2 text-gray-600">
                    <li>Setelah menyimpan konfigurasi, scroll ke bagian <strong>Test WhatsApp</strong></li>
                    <li>Masukkan nomor tujuan (format: <code>08123456789</code>)</li>
                    <li>Klik <strong>Test</strong></li>
                    <li>Jika berhasil, akan muncul log <strong>success</strong> di bawah form test</li>
                    <li>Jika gagal, periksa log error untuk debugging</li>
                </ol>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                    <p class="text-sm text-yellow-700">
                        <strong>Catatan:</strong> Nomor tujuan harus memiliki <strong>WhatsApp aktif</strong>.
                        Format nomor cukup angka, tanpa awalan <code>+</code> atau <code>62</code>.
                        Contoh: <code>08123456789</code>.
                    </p>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-4">
                    <p class="text-sm text-blue-700">
                        💡 <strong>Kustomisasi Provider:</strong> Untuk mengganti provider WhatsApp,
                        edit file <code>app/Services/WhatsAppService.php</code>.
                        Service saat ini menggunakan <strong>Fonnte API</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
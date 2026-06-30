<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $favicon = \App\Models\Setting::get('site_favicon') ?: \App\Models\Setting::get('site_logo') ?: asset('favicon.ico'); @endphp
    <link rel="icon" type="image/svg+xml" href="{{ $favicon }}">
    <title>403 - Akses Ditolak | {{ \App\Models\Setting::get('site_name', 'DiToko') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-ditoko-navy text-white shadow-lg">
        <div class="max-w-5xl mx-auto px-4 py-4">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-ditoko-navy font-bold text-xl">D</span>
                    </div>
                    <span class="text-2xl font-bold">DiToko</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="text-center max-w-lg mx-auto">
            <!-- Error Illustration -->
            <div class="mb-8">
                <div class="relative inline-block">
                    <div class="text-[180px] font-bold text-ditoko-navy opacity-10 leading-none">403</div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-32 h-32 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Akses Ditolak!</h1>
            <p class="text-gray-600 mb-8 text-lg">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator.
            </p>

            <!-- Alert Box -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-8 text-left">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-red-800">Akses Terbatas</p>
                        <p class="text-red-700 text-sm mt-1">Halaman ini hanya dapat diakses oleh pengguna tertentu.</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" class="px-6 py-3 bg-ditoko-navy text-white rounded-lg hover:bg-blue-900 transition-colors font-semibold shadow-lg hover:shadow-xl">
                    🏠 Kembali ke Beranda
                </a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.orders.index') }}" class="px-6 py-3 bg-ditoko-orange text-white rounded-lg hover:bg-orange-700 transition-colors font-semibold">
                            ⚙️ Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-ditoko-orange text-white rounded-lg hover:bg-orange-700 transition-colors font-semibold">
                            📊 Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-ditoko-orange text-white rounded-lg hover:bg-orange-700 transition-colors font-semibold">
                        🔐 Masuk
                    </a>
                @endauth
            </div>

            <!-- Help -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-gray-500 mb-4">Butuh bantuan?</p>
                <div class="flex flex-wrap gap-3 justify-center text-sm">
                    @auth
                        <a href="{{ route('chat.index') }}" class="text-ditoko-orange hover:underline">💬 Hubungi Admin</a>
                    @else
                        <a href="{{ route('register') }}" class="text-ditoko-orange hover:underline">📝 Daftar Akun</a>
                    @endauth
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('terms') }}" class="text-ditoko-orange hover:underline">📋 Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-ditoko-navy text-white py-6">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center space-x-2 mb-3">
                <div class="w-6 h-6 bg-white rounded flex items-center justify-center">
                    <span class="text-ditoko-navy font-bold text-xs">D</span>
                </div>
                <span class="text-sm font-bold">DiToko</span>
            </div>
            <p class="text-blue-200 text-xs">&copy; 2026 DiToko. Marketplace Produk Digital.</p>
        </div>
    </footer>
</body>
</html>
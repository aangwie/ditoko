<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $favicon = \App\Models\Setting::get('site_favicon') ?: \App\Models\Setting::get('site_logo') ?: asset('favicon.ico'); @endphp
    <link rel="icon" type="image/svg+xml" href="{{ $favicon }}">
    <title>404 - Halaman Tidak Ditemukan | {{ \App\Models\Setting::get('site_name', 'DiToko') }}</title>
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
                    <div class="text-[180px] font-bold text-ditoko-navy opacity-10 leading-none">404</div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-32 h-32 text-ditoko-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Oops! Halaman Tidak Ditemukan</h1>
            <p class="text-gray-600 mb-8 text-lg">
                Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman tersebut telah dipindahkan atau tidak ada.
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" class="px-6 py-3 bg-ditoko-navy text-white rounded-lg hover:bg-blue-900 transition-colors font-semibold shadow-lg hover:shadow-xl">
                    🏠 Kembali ke Beranda
                </a>
                <a href="{{ url()->previous() }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                    ← Halaman Sebelumnya
                </a>
            </div>

            <!-- Quick Links -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-gray-500 mb-4">Atau coba navigasi ini:</p>
                <div class="flex flex-wrap gap-3 justify-center text-sm">
                    <a href="{{ route('products.show', ['product' => 'contoh']) }}" class="text-ditoko-orange hover:underline">Katalog Produk</a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('dashboard') }}" class="text-ditoko-orange hover:underline">Dashboard</a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('terms') }}" class="text-ditoko-orange hover:underline">Syarat & Ketentuan</a>
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
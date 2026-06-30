<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DiToko - Portal Produk Digital</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    <!-- Navbar -->
    <nav class="bg-ditoko-navy shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="text-2xl font-bold tracking-wide">
                    <span class="text-white">Di</span><span class="text-ditoko-orange">Toko</span>
                </a>
                <div class="flex items-center gap-3">
                    <!-- Cart Icon -->
                    @php $cartCount = collect(session()->get('cart', []))->sum('quantity'); @endphp
                    <a href="{{ route('cart.index') }}" class="relative text-white hover:text-ditoko-orange transition p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                        </svg>
                        @if ($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-ditoko-orange text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                        @endif
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-ditoko-orange transition text-sm font-medium">Dashboard</a>
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.products.index') }}" class="text-white hover:text-ditoko-orange transition text-sm font-medium">Admin</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-ditoko-orange transition text-sm font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-ditoko-orange text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600 transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-ditoko-navy via-blue-900 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Marketplace <span class="text-ditoko-orange">Produk Digital</span> Terpercaya
                </h1>
                <p class="text-lg md:text-xl text-blue-200 mb-10">
                    Dapatkan e-book, software, template, dan berbagai produk digital berkualitas dengan harga terbaik.
                    Akses instan setelah pembayaran!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#products" class="bg-ditoko-orange text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-orange-600 transition shadow-lg">Jelajahi Produk</a>
                    @guest
                    <a href="{{ route('register') }}" class="bg-white/10 backdrop-blur-sm text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-white/20 transition border border-white/30">Daftar Sekarang</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section id="products" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800">Produk Digital</h2>
                <p class="text-gray-500 mt-2">Pilih produk digital favorit Anda</p>
            </div>

            @if ($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition group">
                        <a href="{{ route('products.show', $product) }}">
                            <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                                @if ($product->cover_image && file_exists(public_path('storage/' . $product->cover_image)))
                                    <img src="{{ asset('storage/' . $product->cover_image) }}" alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-5">
                            <a href="{{ route('products.show', $product) }}">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-ditoko-navy transition">{{ $product->name }}</h3>
                            </a>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ Str::limit($product->description, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-ditoko-orange">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('products.show', $product) }}" class="text-sm bg-ditoko-navy text-white px-4 py-2 rounded-lg hover:bg-blue-900 transition">Detail</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-gray-500 text-lg">Belum ada produk digital tersedia.</p>
                    <p class="text-gray-400 text-sm mt-1">Silakan kembali lagi nanti.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-ditoko-navy text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <span class="text-2xl font-bold">Di<span class="text-ditoko-orange">Toko</span></span>
                    <p class="text-blue-300 mt-2 text-sm">Portal produk digital terpercaya di Indonesia.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Navigasi</h4>
                    <ul class="space-y-2 text-sm text-blue-300">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="#products" class="hover:text-white transition">Produk</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Kontak</h4>
                    <ul class="space-y-2 text-sm text-blue-300">
                        <li>Email: support@ditoko.id</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-blue-800 mt-8 pt-8 text-center text-sm text-blue-400">
                &copy; {{ date('Y') }} DiToko. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>


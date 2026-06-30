<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} - DiToko</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-ditoko-navy shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="text-2xl font-bold tracking-wide">
                    <span class="text-white">Di</span><span class="text-ditoko-orange">Toko</span>
                </a>
                <div class="flex items-center gap-3">
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
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-ditoko-orange transition text-sm font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-ditoko-orange text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600 transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Product Detail -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-ditoko-navy mb-6 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Beranda
        </a>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                <!-- Cover Image -->
                <div class="bg-gray-100 flex items-center justify-center p-8">
                    @if ($product->cover_image && file_exists(public_path('storage/' . $product->cover_image)))
                        <img src="{{ asset('storage/' . $product->cover_image) }}" alt="{{ $product->name }}"
                             class="w-full max-w-md rounded-xl shadow-sm">
                    @else
                        <div class="w-full max-w-md aspect-[4/3] flex items-center justify-center text-gray-400 bg-gray-50 rounded-xl">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-8 md:p-10 flex flex-col justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
                        <div class="prose prose-gray max-w-none mb-6">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-3xl font-bold text-ditoko-orange">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>

                        @auth
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-ditoko-orange text-white py-3 px-6 rounded-xl text-lg font-semibold hover:bg-orange-600 transition shadow-md">
                                    + Keranjang
                                </button>
                            </form>
                            <a href="{{ route('cart.index') }}"
                               class="block w-full text-center mt-3 bg-ditoko-navy text-white py-3 px-6 rounded-xl text-lg font-semibold hover:bg-blue-900 transition shadow-md">
                                Lihat Keranjang
                            </a>
                        @else
                            <div class="space-y-3">
                                <a href="{{ route('login') }}"
                                   class="block w-full text-center bg-ditoko-orange text-white py-3 px-6 rounded-xl text-lg font-semibold hover:bg-orange-600 transition shadow-md">
                                    Masuk untuk Membeli
                                </a>
                                <p class="text-sm text-gray-500 text-center">Belum punya akun? <a href="{{ route('register') }}" class="text-ditoko-navy hover:underline">Daftar</a></p>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-ditoko-navy text-white py-8 mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-blue-400">
            &copy; {{ date('Y') }} DiToko. All rights reserved.
        </div>
    </footer>
</body>
</html>

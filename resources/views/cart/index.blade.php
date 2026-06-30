<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja - DiToko</title>
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
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
         x-data="{
            updateQuantity(id, qty) {
                qty = Math.max(1, qty);
                fetch('/cart/update/' + id, {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ quantity: qty })
                }).then(() => { window.location.reload(); });
            },
            removeItem(id) {
                if (confirm('Hapus produk ini dari keranjang?')) {
                    fetch('/cart/remove/' + id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }).then(() => { window.location.reload(); });
                }
            }
         }">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Keranjang Belanja</h1>
            <a href="{{ route('home') }}" class="text-sm text-ditoko-navy hover:underline">&larr; Lanjut Belanja</a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
        @endif

        @if (count($cart) > 0)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 text-left text-sm font-semibold text-gray-600">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Jumlah</th>
                            <th class="px-6 py-4">Subtotal</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($cart as $id => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if (!empty($item['cover_image']) && file_exists(public_path('storage/' . $item['cover_image'])))
                                        <img src="{{ asset('storage/' . $item['cover_image']) }}" class="w-14 h-14 object-cover rounded-lg">
                                    @else
                                        <div class="w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('products.show', $item['slug']) }}" class="font-medium text-gray-800 hover:text-ditoko-navy">{{ $item['name'] }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-ditoko-orange font-semibold">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button @click="updateQuantity({{ $id }}, {{ $item['quantity'] - 1 }})"
                                            class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition text-gray-600 font-bold">-</button>
                                    <span class="w-8 text-center font-medium">{{ $item['quantity'] }}</span>
                                    <button @click="updateQuantity({{ $id }}, {{ $item['quantity'] + 1 }})"
                                            class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition text-gray-600 font-bold">+</button>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <button @click="removeItem({{ $id }})" class="text-red-500 hover:text-red-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total & Checkout -->
            <div class="mt-6 bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Total Belanja</p>
                        <p class="text-3xl font-bold text-ditoko-orange">Rp {{ number_format($total, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex gap-3">
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-3 text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition">Kosongkan</button>
                        </form>
                        <a href="{{ route('checkout.index') }}" class="px-8 py-3 bg-ditoko-orange text-white rounded-xl font-semibold hover:bg-orange-600 transition shadow-md">Checkout</a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-xl shadow-md">
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
                <p class="text-gray-500 text-lg mb-2">Keranjang belanja masih kosong</p>
                <a href="{{ route('home') }}" class="text-ditoko-orange hover:underline">Jelajahi produk</a>
            </div>
        @endif
    </div>

    <footer class="bg-ditoko-navy text-white py-8 mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-blue-400">
            &copy; {{ date('Y') }} DiToko. All rights reserved.
        </div>
    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - DiToko</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (isset($midtransClientKey) && $midtransClientKey)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
    @endif
</head>
<body class="font-sans antialiased bg-gray-50">
    <nav class="bg-ditoko-navy shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="text-2xl font-bold tracking-wide">
                    <span class="text-white">Di</span><span class="text-ditoko-orange">Toko</span>
                </a>
                <a href="{{ route('cart.index') }}" class="text-white hover:text-ditoko-orange transition text-sm">&larr; Kembali</a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
         x-data="{ payment: 'manual_transfer' }">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h1>
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border-l-2 border-red-500 text-red-700 p-4 rounded-lg">{{ $errors->first() }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h2>
                <div class="bg-white rounded-xl shadow-md divide-y divide-gray-200">
                    @foreach ($cart as $id => $item)
                    <div class="flex items-center gap-4 p-4">
                        @if (!empty($item['cover_image']) && file_exists(public_path('storage/' . $item['cover_image'])))
                            <img src="{{ asset('storage/' . $item['cover_image']) }}" class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $item['quantity'] }}x @ Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        </div>
                        <p class="font-semibold text-ditoko-orange">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800">Total</span>
                        <span class="text-2xl font-bold text-ditoko-orange">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Metode Pembayaran</h2>
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" x-model="payment">
                    <div class="space-y-3">
                        @if (isset($midtransClientKey) && $midtransClientKey)
                        <label class="block bg-white rounded-xl shadow-md p-4 cursor-pointer hover:border-ditoko-orange border-2 border-transparent"
                               @click="payment = 'midtrans'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="_method_radio" value="midtrans" class="text-ditoko-orange focus:ring-ditoko-orange" @click="payment = 'midtrans'">
                                <div>
                                    <p class="font-semibold text-gray-800">Midtrans</p>
                                    <p class="text-sm text-gray-500">Kartu Kredit, Bank Transfer, E-Wallet</p>
                                </div>
                            </div>
                        </label>
                        @endif
                        <label class="block bg-white rounded-xl shadow-md p-4 cursor-pointer hover:border-ditoko-orange border-2 border-ditoko-orange"
                               @click="payment = 'manual_transfer'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="_method_radio" value="manual_transfer" class="text-ditoko-orange focus:ring-ditoko-orange" @click="payment = 'manual_transfer'" checked>
                                <div>
                                    <p class="font-semibold text-gray-800">Transfer Manual (BRI/BCA/Mandiri)</p>
                                    <p class="text-sm text-gray-500">Konfirmasi manual oleh admin</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    <button type="submit" class="w-full mt-6 bg-ditoko-orange text-white py-3 px-6 rounded-xl font-semibold hover:bg-orange-600 transition shadow-md text-lg">Buat Pesanan</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-ditoko-navy text-white py-8 mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-blue-400">
            &copy; {{ date('Y') }} DiToko. All rights reserved.
        </div>
    </footer>
</body>
</html>

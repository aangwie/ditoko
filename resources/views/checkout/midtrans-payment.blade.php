<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran - DiToko</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <nav class="bg-ditoko-navy shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="text-2xl font-bold tracking-wide">
                    <span class="text-white">Di</span><span class="text-ditoko-orange">Toko</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-lg mx-auto px-4 py-20 text-center"
         x-data="{ snapToken: '{{ $snapToken }}' }"
         x-init="
            setTimeout(() => {
                snap.pay(snapToken, {
                    onSuccess: function(result) { window.location.href = '{{ route("orders.show", $order) }}?status=success'; },
                    onPending: function(result) { window.location.href = '{{ route("orders.show", $order) }}?status=pending'; },
                    onError: function(result) { window.location.href = '{{ route("orders.show", $order) }}?status=error'; },
                    onClose: function() { window.location.href = '{{ route("orders.show", $order) }}'; }
                });
            }, 500);
         ">
        <div class="animate-spin w-12 h-12 border-4 border-ditoko-orange border-t-transparent rounded-full mx-auto mb-6"></div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Membuka Pembayaran...</h1>
        <p class="text-gray-500">Jendela pembayaran Midtrans akan muncul secara otomatis.</p>
        <p class="text-sm text-gray-400 mt-2">Pesanan: <strong>{{ $order->order_number }}</strong></p>
        <p class="text-sm text-gray-400">Total: <strong class="text-ditoko-orange">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
        <button onclick="snap.pay('{{ $snapToken }}')" class="mt-6 px-6 py-2 bg-ditoko-navy text-white rounded-lg hover:bg-blue-900 transition">
            Buka Pembayaran Lagi
        </button>
        <a href="{{ route('orders.show', $order) }}" class="block mt-3 text-sm text-ditoko-orange hover:underline">Lihat Status Pesanan</a>
    </div>
</body>
</html>

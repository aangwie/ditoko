@extends('buyer.layouts.app')

@section('content')
<div class="py-10 max-w-3xl mx-auto px-4">
    <a href="{{ route('orders.index') }}" class="text-sm text-ditoko-navy hover:underline mb-4 inline-block">&larr; Kembali</a>

    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Pesanan {{ $order->order_number }}</h1>
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <span class="px-4 py-1.5 rounded-full text-sm font-semibold
                @if ($order->payment_status === 'success') bg-green-100 text-green-700
                @elseif ($order->payment_status === 'failed') bg-red-100 text-red-700
                @else bg-yellow-100 text-yellow-700 @endif">
                {{ ucfirst($order->payment_status) }}
            </span>
        </div>

        <div class="border-t border-gray-200 pt-4">
            <p class="text-sm text-gray-500">Metode Pembayaran</p>
            <p class="font-medium">{{ $order->payment_method === 'midtrans' ? 'Midtrans' : 'Transfer Manual' }}</p>
        </div>

        <div class="border-t border-gray-200 pt-4 mt-4">
            <p class="text-sm text-gray-500">Total Pembayaran</p>
            <p class="text-2xl font-bold text-ditoko-orange">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Products in Order -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="font-semibold text-gray-800 mb-4">Produk Dibeli</h2>
        <div class="divide-y divide-gray-200">
            @foreach ($order->items as $item)
            <div class="flex items-center justify-between py-3">
                <div>
                    <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                    <p class="text-sm text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                </div>
                @if ($order->payment_status === 'success')
                    <a href="{{ route('download.product', [$order, $item->product]) }}"
                       class="px-4 py-2 bg-ditoko-orange text-white rounded-lg hover:bg-orange-600 transition text-sm font-medium">
                        Download
                    </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Manual Transfer: Bank Info & Upload Proof -->
    @if ($order->payment_method === 'manual_transfer' && $order->payment_status === 'pending')
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="font-semibold text-gray-800 mb-4">Instruksi Pembayaran Transfer Manual</h2>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <p class="text-sm text-blue-800 font-medium">Silakan transfer ke rekening berikut:</p>
            <div class="mt-3 space-y-2 text-sm">
                <p><strong>Bank BRI</strong> — 1234-5678-9012-3456 a.n. DiToko</p>
                <p><strong>Bank BCA</strong> — 9876-5432-1098-7654 a.n. DiToko</p>
                <p><strong>Bank Mandiri</strong> — 5647-3829-1047-2839 a.n. DiToko</p>
            </div>
            <p class="text-xs text-blue-600 mt-3">Total transfer: <strong class="text-ditoko-orange">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
        </div>

        @if ($order->proof_of_payment)
            <div class="mb-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran Terunggah:</p>
                            <img src="{{ Storage::url($order->proof_of_payment) }}" class="w-48 h-48 object-cover rounded-lg border">
                <p class="text-xs text-gray-500 mt-1">Menunggu verifikasi admin.</p>
            </div>
        @else
            <form action="{{ route('orders.proof', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer</label>
                <input type="file" name="proof" accept="image/*" required
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-ditoko-orange focus:ring-ditoko-orange">
                @error('proof') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <button type="submit" class="mt-3 px-6 py-2 bg-ditoko-navy text-white rounded-lg hover:bg-blue-900 transition">Upload</button>
            </form>
        @endif
    </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
    @endif
</div>
@endsection

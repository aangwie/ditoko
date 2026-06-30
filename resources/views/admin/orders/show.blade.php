@extends('admin.layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<a href="{{ route('admin.orders.index') }}" class="text-sm text-ditoko-navy hover:underline mb-4 inline-block">&larr; Kembali</a>

<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
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

    <div class="grid grid-cols-2 gap-4 border-t border-gray-200 pt-4">
        <div>
            <p class="text-sm text-gray-500">Pembeli</p>
            <p class="font-medium">{{ $order->user->name }}</p>
            <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Metode Pembayaran</p>
            <p class="font-medium">{{ $order->payment_method === 'midtrans' ? 'Midtrans' : 'Transfer Manual' }}</p>
            <p class="text-lg font-bold text-ditoko-orange mt-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<!-- Products -->
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h2 class="font-semibold text-gray-800 mb-4">Produk</h2>
    <div class="divide-y divide-gray-200">
        @foreach ($order->items as $item)
        <div class="flex items-center justify-between py-3">
            <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
            <p class="text-ditoko-orange font-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
        </div>
        @endforeach
    </div>
</div>

<!-- Proof of Payment -->
@if ($order->proof_of_payment)
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h2 class="font-semibold text-gray-800 mb-4">Bukti Pembayaran</h2>
    <img src="{{ Storage::url($order->proof_of_payment) }}" class="max-w-md rounded-lg border shadow-sm">
</div>
@endif

<!-- Verify Button (for manual transfer) -->
<div class="flex gap-4">
    @if ($order->payment_method === 'manual_transfer' && $order->payment_status === 'pending')
    <form action="{{ route('admin.orders.verify', $order) }}" method="POST" onsubmit="return confirm('Verifikasi pembayaran ini?')">
        @csrf
        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
            Verifikasi & Setujui Pembayaran
        </button>
    </form>
    @endif
    
    @if ($order->payment_status === 'pending')
    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
        @csrf
        <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
            Batalkan Pesanan
        </button>
    </form>
    @endif
</div>
@endsection

@extends('buyer.layouts.app')

@section('content')
<div class="py-10 max-w-4xl mx-auto px-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Pesanan</h1>

    @if (session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
    @endif

    @forelse ($orders as $order)
    <div class="bg-white rounded-xl shadow-md p-6 mb-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-gray-500">Pesanan</p>
                <p class="font-semibold text-gray-800">{{ $order->order_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                    @if ($order->payment_status === 'success') bg-green-100 text-green-700
                    @elseif ($order->payment_status === 'failed') bg-red-100 text-red-700
                    @else bg-yellow-100 text-yellow-700 @endif">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-4 flex items-center justify-between">
            <div>
                <p class="text-lg font-bold text-ditoko-orange">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500">{{ $order->payment_method === 'midtrans' ? 'Midtrans' : 'Transfer Manual' }}</p>
            </div>
            <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 bg-ditoko-navy text-white rounded-lg hover:bg-blue-900 transition text-sm">Detail</a>
        </div>
    </div>
    @empty
    <div class="text-center py-16 bg-white rounded-xl shadow-md">
        <p class="text-gray-500 text-lg">Belum ada pesanan</p>
        <a href="{{ route('home') }}" class="text-ditoko-orange hover:underline mt-2 inline-block">Jelajahi produk</a>
    </div>
    @endforelse

    @if ($orders->hasPages())
    <div class="mt-6">{{ $orders->links() }}</div>
    @endif
</div>
@endsection

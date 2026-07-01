@extends('admin.layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Pesanan</h1>
</div>

@if (session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 text-left text-sm font-semibold text-gray-600">
            <tr>
                <th class="px-6 py-4">Order</th>
                <th class="px-6 py-4">Pembeli</th>
                <th class="px-6 py-4">Total</th>
                <th class="px-6 py-4">Metode</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $order->order_number }}</td>
                <td class="px-6 py-4">{{ $order->user->name }}</td>
                <td class="px-6 py-4 text-ditoko-orange font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm">{{ $order->payment_method === 'midtrans' ? 'Midtrans' : 'Manual' }}</td>
                <td class="px-6 py-4">
                    @php
                        $needsConfirm = $order->payment_status === 'pending' && $order->proof_of_payment;
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if ($order->payment_status === 'success') bg-green-100 text-green-700
                        @elseif ($order->payment_status === 'failed') bg-red-100 text-red-700
                        @elseif ($needsConfirm) bg-orange-100 text-orange-700
                        @else bg-yellow-100 text-yellow-700 @endif">
                        {{ $needsConfirm ? 'Need Confirmation' : ucfirst($order->payment_status) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1.5 text-sm bg-ditoko-navy text-white rounded-lg hover:bg-blue-900 transition">Detail</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Belum ada pesanan.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if ($orders->hasPages())
    <div class="px-6 py-4 border-t">{{ $orders->links() }}</div>
    @endif
</div>
@endsection

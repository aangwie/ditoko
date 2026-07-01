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

    @php $bankSettings = \App\Models\Setting::getByGroup('bank'); @endphp

    <!-- Payment Instructions -->
    @if ($order->payment_status === 'pending' && in_array($order->payment_method, ['bank_transfer', 'qris']))
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="font-semibold text-gray-800 mb-4">Instruksi Pembayaran</h2>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            @if ($order->payment_method === 'bank_transfer')
                <p class="text-sm text-blue-800 font-medium">Silakan transfer ke rekening berikut:</p>
                <div class="mt-3 space-y-2 text-sm">
                    @if (!empty($bankSettings['bank_name']) && !empty($bankSettings['bank_account']))
                    <p><strong>{{ $bankSettings['bank_name'] }}</strong> — {{ $bankSettings['bank_account'] }} a.n. {{ $bankSettings['bank_holder'] ?? '-' }}</p>
                    @endif
                </div>
            @elseif ($order->payment_method === 'qris' && !empty($bankSettings['qris_image']))
                <p class="text-sm text-blue-800 font-medium mb-3">Scan QRIS berikut untuk pembayaran:</p>
                <img src="{{ $bankSettings['qris_image'] }}" alt="QRIS" class="w-48 h-48 object-contain rounded-lg border bg-white cursor-pointer hover:opacity-90 transition" onclick="document.getElementById('qris-modal').classList.remove('hidden')">
                <!-- Lightbox Modal -->
                <div id="qris-modal" class="fixed inset-0 z-50 hidden bg-black/70" onclick="if(event.target===this)document.getElementById('qris-modal').classList.add('hidden')">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px]">
                        <img src="{{ $bankSettings['qris_image'] }}" alt="QRIS" class="w-full h-auto object-contain rounded-lg bg-white p-2">
                        <button onclick="document.getElementById('qris-modal').classList.add('hidden')" class="absolute -top-3 -right-3 text-white bg-black/70 rounded-full w-7 h-7 flex items-center justify-center text-lg hover:bg-black">&times;</button>
                    </div>
                </div>
            @endif
            @if (!empty($bankSettings['payment_instructions']))
                <div class="mt-4 text-sm text-blue-800">{!! nl2br(e($bankSettings['payment_instructions'])) !!}</div>
            @endif
            <p class="text-xs text-blue-600 mt-3">Total pembayaran: <strong class="text-ditoko-orange">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
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

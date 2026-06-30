<x-buyer-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Buyer</h1>
            <p class="text-gray-500 mt-1">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>

        @php
            $totalOrders = \App\Models\Order::where('user_id', auth()->id())->count();
            $pendingOrders = \App\Models\Order::where('user_id', auth()->id())->where('payment_status', 'pending')->count();
            $successOrders = \App\Models\Order::where('user_id', auth()->id())->where('payment_status', 'success')->count();
            $pendingVerification = \App\Models\Order::where('user_id', auth()->id())->where('status', 'pending_verification')->count();
            $totalSpent = \App\Models\Order::where('user_id', auth()->id())->where('payment_status', 'success')->sum('total_price');
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <div class="text-sm text-gray-500">Total Pesanan</div>
                <div class="text-2xl font-bold text-ditoko-navy">{{ $totalOrders }}</div>
                <a href="{{ route('orders.index') }}" class="text-xs text-ditoko-orange">Lihat</a>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <div class="text-sm text-gray-500">Pending</div>
                <div class="text-2xl font-bold text-yellow-600">{{ $pendingOrders }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <div class="text-sm text-gray-500">Sukses</div>
                <div class="text-2xl font-bold text-green-600">{{ $successOrders }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <div class="text-sm text-gray-500">Total Belanja</div>
                <div class="text-2xl font-bold text-blue-600">Rp{{ number_format($totalSpent, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Recent Orders -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Pesanan Terakhir</h2>
                @php
                    $recentOrders = \App\Models\Order::where('user_id', auth()->id())->with('orderItems.product')->latest()->take(5)->get();
                @endphp
                @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <div class="border-b last:border-0 pb-4 last:pb-0">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-800">Order #{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-800">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        <span class="inline-block px-2 py-1 text-xs rounded-full
                                            @if($order->payment_status == 'success') bg-green-100 text-green-800
                                            @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $order->orderItems->count() }} produk
                                </div>
                                <a href="{{ route('orders.show', $order) }}" class="text-sm text-ditoko-navy font-medium mt-2 inline-block">Lihat Detail</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Belum ada pesanan</p>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('home') }}" class="flex items-center px-4 py-3 bg-ditoko-navy text-white rounded-lg hover:bg-ditoko-navy-dark transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM15 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1h-6a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <span>Jelajahi Produk</span>
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-3 bg-ditoko-orange text-white rounded-lg hover:bg-ditoko-orange-dark transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <span>Riwayat Pesanan</span>
                    </a>
                    <a href="{{ route('chat.index') }}" class="flex items-center px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>Chat with Admin</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Edit Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-buyer-layout>
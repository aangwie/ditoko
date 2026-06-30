@auth
    @if (Auth::user()->role === 'admin')
        <x-admin-layout>
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
                <p class="text-gray-500 mt-1">Selamat datang, {{ Auth::user()->name }}!</p>
            </div>
            @php
                $totalProducts = \App\Models\Product::count();
                $totalOrders = \App\Models\Order::count();
                $pendingOrders = \App\Models\Order::where('payment_status', 'pending')->count();
                $successOrders = \App\Models\Order::where('payment_status', 'success')->count();
                $recentOrders = \App\Models\Order::with('user')->latest()->take(5)->get();
            @endphp
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-sm border p-5">
                    <div class="text-sm text-gray-500">Total Produk</div>
                    <div class="text-2xl font-bold text-ditoko-navy">{{ $totalProducts }}</div>
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-ditoko-orange">Kelola</a>
                </div>
                <div class="bg-white rounded-xl shadow-sm border p-5">
                    <div class="text-sm text-gray-500">Total Pesanan</div>
                    <div class="text-2xl font-bold text-ditoko-navy">{{ $totalOrders }}</div>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-ditoko-orange">Lihat</a>
                </div>
                <div class="bg-white rounded-xl shadow-sm border p-5">
                    <div class="text-sm text-gray-500">Pending</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $pendingOrders }}</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border p-5">
                    <div class="text-sm text-gray-500">Sukses</div>
                    <div class="text-2xl font-bold text-green-600">{{ $successOrders }}</div>
                </div>
            </div>
        </x-admin-layout>
    @else
        <x-app-layout>
            <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Dashboard</h2></x-slot>
            <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p>Selamat datang, {{ Auth::user()->name }}!</p>
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-ditoko-navy text-white rounded-lg">Jelajahi Produk</a>
                    <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-ditoko-orange text-white rounded-lg">Riwayat</a>
                </div>
            </div>
        </x-app-layout>
    @endif
@endauth

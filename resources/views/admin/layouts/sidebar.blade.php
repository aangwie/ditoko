<aside class="fixed md:static inset-y-0 left-0 z-40 bg-ditoko-navy text-white flex flex-col transition-all duration-300 ease-in-out"
       :class="sidebarOpen ? 'w-64' : 'w-0 md:w-16'"
       x-show="sidebarOpen || window.innerWidth >= 768"
       x-cloak>
    <!-- Logo -->
    @php $siteName = \App\Models\Setting::get('site_name', 'DiToko'); $siteLogo = \App\Models\Setting::get('site_logo'); @endphp
    <div class="flex-shrink-0 px-4 md:px-6 py-5 border-b border-blue-800 flex items-center justify-between min-h-[65px]">
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 text-xl font-bold tracking-wide whitespace-nowrap"
           x-show="sidebarOpen">
            @if($siteLogo)
                <img src="{{ $siteLogo }}" alt="Logo" class="h-8 w-auto">
            @else
                <span class="text-white">{{ substr($siteName, 0, 2) }}</span><span class="text-ditoko-orange">{{ substr($siteName, 2) }}</span>
            @endif
            <span class="text-xs text-gray-300 ml-1">Admin</span>
        </a>
        <a href="{{ route('admin.products.index') }}" class="hidden md:block"
           x-show="!sidebarOpen">
            @if($siteLogo)
                <img src="{{ $siteLogo }}" alt="Logo" class="h-8 w-auto">
            @else
                <span class="text-ditoko-orange font-bold text-lg">{{ substr($siteName, 0, 1) }}</span>
            @endif
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2 md:px-4 py-6 space-y-1 overflow-y-auto">
        @php
            $navItems = [
                ['route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard', 'routeName' => 'dashboard'],
                ['route' => 'admin.products.index', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'label' => 'Produk Digital', 'routeName' => 'admin.products.*'],
                ['route' => 'admin.orders.index', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'Pesanan', 'routeName' => 'admin.orders.*'],
                ['route' => 'admin.docs', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Dokumentasi', 'routeName' => 'admin.docs'],
                ['route' => 'admin.update-web.index', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'label' => 'Update Web', 'routeName' => 'admin.update-web.*'],
                ['route' => 'admin.settings.index', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Settings', 'routeName' => 'admin.settings.*'],
            ];
            $currentRoute = request()->route() ? request()->route()->getName() : '';
        @endphp

        @foreach ($navItems as $item)
            @php
                $isActive = $item['routeName'] === 'dashboard'
                    ? $currentRoute === 'dashboard'
                    : request()->routeIs($item['routeName']);
            @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200 group"
               :class="sidebarOpen ? 'px-4' : 'md:px-3 md:justify-center'"
               :title="sidebarOpen ? '' : '{{ $item['label'] }}'"
               style="{{ $isActive ? 'background-color: rgba(30, 64, 175, 0.8);' : '' }}"
               onmouseover="this.style.backgroundColor='rgba(30, 64, 175, 0.5)'"
               onmouseout="this.style.backgroundColor='{{ $isActive ? 'rgba(30, 64, 175, 0.8)' : 'transparent' }}'">
                <svg class="w-5 h-5 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                <span x-show="sidebarOpen" class="text-sm whitespace-nowrap {{ $isActive ? 'text-white font-medium' : 'text-gray-300' }}">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach

        <div class="pt-4 mt-4 border-t border-blue-800/50" x-show="sidebarOpen">
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-blue-800/50 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="text-sm">Website</span>
            </a>
        </div>
    </nav>

    <!-- User Info -->
    <div class="flex-shrink-0 px-4 md:px-6 py-4 border-t border-blue-800">
        <div class="flex items-center gap-3" :class="sidebarOpen ? '' : 'md:justify-center'">
            <div class="w-8 h-8 rounded-full bg-ditoko-orange flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="sidebarOpen" class="text-sm min-w-0">
                <p class="font-medium truncate">{{ Auth::user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-gray-400 hover:text-orange-400 transition">Logout</button>
                </form>
            </div>
        </div>
    </div>
</aside>

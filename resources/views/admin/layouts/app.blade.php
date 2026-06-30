<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $favicon = \App\Models\Setting::get('site_favicon') ?: \App\Models\Setting::get('site_logo') ?: asset('favicon.ico');
    @endphp
    <link rel="icon" type="image/svg+xml" href="{{ $favicon }}">
    <title>@yield('title', 'Admin ' . \App\Models\Setting::get('site_name', 'DiToko')) - {{ config('app.name', 'DiToko') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-100"
      x-data="{ sidebarOpen: true, mobileOpen: false }"
      x-init="
        if (window.innerWidth < 768) sidebarOpen = false;
        window.addEventListener('resize', () => {
            sidebarOpen = window.innerWidth >= 768;
        });
      "
      :class="{ 'overflow-hidden': mobileOpen }">
    <div x-show="mobileOpen" @click="mobileOpen = false"
         x-cloak class="fixed inset-0 z-30 bg-black/50 md:hidden" x-transition.opacity>
    </div>
    <div class="flex h-screen overflow-hidden">
        @include('admin.layouts.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden w-full relative">
            @include('admin.layouts.topbar')
            <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    @include('admin.layouts.flash-swal')
    
    <!-- Chat Widget -->
    @include('components.floating-chat')
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            // Initialize chat widget on admin pages
            window.Livewire?.on('chat:loaded', () => {
                // Chat widget is already initialized
            });
        });
    </script>
    @endpush
</body>
</html>
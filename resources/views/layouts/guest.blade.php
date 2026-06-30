<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php $favicon = \App\Models\Setting::get('site_favicon') ?: \App\Models\Setting::get('site_logo') ?: asset('favicon.ico'); @endphp
        <link rel="icon" type="image/svg+xml" href="{{ $favicon }}">
        <title>{{ \App\Models\Setting::get('site_name', config('app.name', 'DiToko')) }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            .auth-gradient {
                background: #1e3a8a;
            }
            .pattern-dots {
                background-image: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
                background-size: 20px 20px;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Left Side - Branding (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 auth-gradient pattern-dots relative overflow-hidden">
                <div class="absolute inset-0 bg-black opacity-10"></div>
                <div class="relative z-10 flex flex-col justify-center items-center w-full px-12 text-white">
                    <!-- Logo/Brand -->
                    <div class="mb-8 flex flex-col items-center">
                        @php $desktopLogo = \App\Models\Setting::get('site_favicon') ?: \App\Models\Setting::get('site_logo') ?: asset('favicon.ico'); @endphp
                        <img src="{{ $desktopLogo }}" alt="Logo" class="h-24 w-auto mb-6">
                        <h1 class="text-5xl font-bold text-center mb-4 text-white">{{ \App\Models\Setting::get('site_name', 'DiToko') }}</h1>
                        <p class="text-xl text-center text-blue-100">Platform E-Commerce Modern & Terpercaya</p>
                    </div>
                    
                    <!-- Features -->
                    <div class="mt-12 space-y-6 max-w-md">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Mudah & Aman</h3>
                                <p class="text-blue-100 text-sm">Transaksi aman dengan sistem pembayaran terpercaya</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Cepat & Responsif</h3>
                                <p class="text-blue-100 text-sm">Pengalaman berbelanja yang lancar dan menyenangkan</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Dukungan 24/7</h3>
                                <p class="text-blue-100 text-sm">Tim support siap membantu kapan saja</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-gray-50">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden mb-8 text-center">
                        @php $mobileFavicon = \App\Models\Setting::get('site_favicon') ?: \App\Models\Setting::get('site_logo') ?: asset('favicon.ico'); @endphp
                        <img src="{{ $mobileFavicon }}" alt="Logo" class="mx-auto h-16 w-auto mb-4">
                        <h2 class="text-2xl font-bold text-black">{{ \App\Models\Setting::get('site_name', 'DiToko') }}</h2>
                    </div>

                    <!-- Form Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10">
                        {{ $slot }}
                    </div>
                    
                    <!-- Footer Links -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            © {{ date('Y') }} {{ \App\Models\Setting::get('site_name', config('app.name')) }}. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

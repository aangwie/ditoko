        <header class="bg-white shadow-sm border-b border-gray-200 h-16 flex-shrink-0">
            <div class="flex items-center justify-between h-full px-4 md:px-6">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="md:hidden">
                        @php $siteName = \App\Models\Setting::get('site_name', 'DiToko'); @endphp
                        <span class="text-lg font-bold text-ditoko-navy">{{ $siteName }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-ditoko-navy transition hidden sm:flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Website
                    </a>
                    <div class="w-px h-6 bg-gray-200 hidden sm:block"></div>
                    <span class="text-sm text-gray-600 hidden sm:block">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

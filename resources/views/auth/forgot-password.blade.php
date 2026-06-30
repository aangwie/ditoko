<x-guest-layout>
    <!-- Page Title -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Lupa Password?</h2>
        <p class="text-gray-600">Jangan khawatir, kami akan mengirimkan link reset password ke email Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
            <x-text-input id="email" 
                          class="block mt-2 w-full rounded-lg border-gray-300 focus:border-ditoko-navy focus:ring-ditoko-navy" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autofocus
                          placeholder="Masukkan email yang terdaftar" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-800">
                        Anda akan menerima email berisi link untuk mereset password Anda. Pastikan untuk memeriksa folder spam jika email tidak muncul di inbox.
                    </p>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                    class="w-full bg-ditoko-navy text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-ditoko-navy focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                Kirim Link Reset Password
            </button>
        </div>
    </form>

    <!-- Back to Login -->
    <div class="mt-8 text-center">
        <a href="{{ route('login') }}" 
           class="inline-flex items-center text-sm text-ditoko-navy hover:text-blue-800 font-semibold transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke halaman login
        </a>
    </div>
</x-guest-layout>

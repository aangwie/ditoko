<x-guest-layout>
    <!-- Page Title -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
        <p class="text-gray-600">Daftar untuk mulai berbelanja sekarang</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-gray-700 font-medium" />
            <x-text-input id="name" 
                          class="block mt-2 w-full rounded-lg border-gray-300 focus:border-ditoko-navy focus:ring-ditoko-navy" 
                          type="text" 
                          name="name" 
                          :value="old('name')" 
                          required 
                          autofocus 
                          autocomplete="name"
                          placeholder="Masukkan nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
            <x-text-input id="email" 
                          class="block mt-2 w-full rounded-lg border-gray-300 focus:border-ditoko-navy focus:ring-ditoko-navy" 
                          type="email" 
                          name="email" 
                          :value="old('email')" 
                          required 
                          autocomplete="username"
                          placeholder="Masukkan alamat email Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
            <x-text-input id="password" 
                          class="block mt-2 w-full rounded-lg border-gray-300 focus:border-ditoko-navy focus:ring-ditoko-navy"
                          type="password"
                          name="password"
                          required 
                          autocomplete="new-password"
                          placeholder="Buat password (min. 8 karakter)" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-medium" />
            <x-text-input id="password_confirmation" 
                          class="block mt-2 w-full rounded-lg border-gray-300 focus:border-ditoko-navy focus:ring-ditoko-navy"
                          type="password"
                          name="password_confirmation" 
                          required 
                          autocomplete="new-password"
                          placeholder="Ketik ulang password Anda" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms & Conditions -->
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input id="terms" 
                       type="checkbox" 
                       class="rounded border-gray-300 text-ditoko-navy focus:ring-ditoko-navy" 
                       required>
            </div>
            <div class="ml-3">
                <label for="terms" class="text-sm text-gray-600">
                    Saya menyetujui 
                    <a href="#" class="text-ditoko-navy hover:text-blue-800 font-medium">Syarat & Ketentuan</a> 
                    dan 
                    <a href="#" class="text-ditoko-navy hover:text-blue-800 font-medium">Kebijakan Privasi</a>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" 
                    class="w-full bg-ditoko-navy text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-ditoko-navy focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                Daftar Sekarang
            </button>
        </div>
    </form>

    <!-- Login Link -->
    <div class="mt-8 text-center">
        <p class="text-sm text-gray-600">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-ditoko-navy hover:text-blue-800 font-semibold transition-colors">
                Masuk di sini
            </a>
        </p>
    </div>
</x-guest-layout>

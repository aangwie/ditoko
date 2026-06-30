@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif

    {{-- Site Settings --}}
    <div class="bg-white shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-ditoko-navy">Pengaturan Toko</h3>
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                @foreach($generalSettings as $setting)
                    @if($setting->key === 'site_favicon')
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                            @php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
                            @if($favicon)
                                <div class="mb-3">
                                    <img src="{{ $favicon }}" alt="Favicon" class="h-10 w-auto rounded border">
                                </div>
                            @endif
                            <input type="file" name="favicon" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-ditoko-navy file:text-white hover:file:bg-blue-900">
                            <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, JPG, GIF, SVG, ICO. Maks 1MB.</p>
                        </div>
                    @elseif($setting->key === 'site_logo')
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo Toko</label>
                            @php $logo = \App\Models\Setting::get('site_logo'); @endphp
                            @if($logo)
                                <div class="mb-3">
                                    <img src="{{ $logo }}" alt="Logo" class="h-20 w-auto rounded border">
                                </div>
                            @endif
                            <input type="file" name="logo" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-ditoko-navy file:text-white hover:file:bg-blue-900">
                            <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, JPG, GIF, SVG. Maks 2MB.</p>
                        </div>
                    @else
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $setting->key === 'site_name' ? 'Nama Toko' : ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            <input type="text" name="settings[{{ $setting->key }}]" value="{{ old('settings.'.$setting->key, $setting->value) }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-ditoko-navy focus:ring-ditoko-navy"
                                   placeholder="{{ $setting->description }}">
                            <p class="mt-1 text-xs text-gray-500">{{ $setting->description }}</p>
                        </div>
                    @endif
                @endforeach

                <div class="pt-6 border-t">
                    <button type="submit" class="w-full bg-ditoko-navy text-white px-6 py-3 rounded-lg hover:bg-blue-900">💾 Simpan Pengaturan Toko</button>
                </div>
            </form>
        </div>
    </div>

    {{-- WhatsApp Settings --}}
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-ditoko-navy">Konfigurasi WhatsApp Gateway</h3>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf @method('PUT')
                @foreach($whatsappSettings as $setting)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ ucwords(str_replace('_', ' ', str_replace('whatsapp_', '', $setting->key))) }}</label>
                        @if($setting->type === 'boolean')
                            <div class="flex items-center">
                                <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                <input type="checkbox" name="settings[{{ $setting->key }}]" value="1" {{ $setting->value == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-ditoko-orange focus:ring-ditoko-orange">
                                <span class="ml-2 text-sm text-gray-600">{{ $setting->description }}</span>
                            </div>
                        @elseif($setting->key === 'whatsapp_api_key')
                            <div class="relative">
                                <input type="password" name="settings[{{ $setting->key }}]" value="{{ old('settings.'.$setting->key, $setting->value) }}"
                                       class="w-full border-gray-300 rounded-lg focus:border-ditoko-orange focus:ring-ditoko-orange pr-10"
                                       placeholder="{{ $setting->description }}" id="api_key_input">
                                <button type="button" onclick="toggleApiKey()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                                    <svg id="eye_icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="eye_off_icon" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            <script>
                            function toggleApiKey() {
                                const input = document.getElementById('api_key_input');
                                const eye = document.getElementById('eye_icon');
                                const eyeOff = document.getElementById('eye_off_icon');
                                if (input.type === 'password') {
                                    input.type = 'text';
                                    eye.classList.add('hidden');
                                    eyeOff.classList.remove('hidden');
                                } else {
                                    input.type = 'password';
                                    eye.classList.remove('hidden');
                                    eyeOff.classList.add('hidden');
                                }
                            }
                            </script>
                        @else
                            <input type="text" name="settings[{{ $setting->key }}]" value="{{ old('settings.'.$setting->key, $setting->value) }}"
                                   class="w-full border-gray-300 rounded-lg focus:border-ditoko-orange focus:ring-ditoko-orange"
                                   placeholder="{{ $setting->description }}">
                        @endif
                        <p class="mt-1 text-xs text-gray-500">{{ $setting->description }}</p>
                    </div>
                    @if($setting->key === 'whatsapp_api_key')
                    {{-- Sender Number --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Pengirim</label>
                        <input type="text" name="settings[whatsapp_sender_number]" value="{{ old('settings.whatsapp_sender_number', \App\Models\Setting::get('whatsapp_sender_number')) }}"
                               class="w-full border-gray-300 rounded-lg focus:border-ditoko-orange focus:ring-ditoko-orange"
                               placeholder="08xxxxxxxxxx">
                        <p class="mt-1 text-xs text-gray-500">Nomor Whatsapp pengirim untuk notifikasi</p>
                    </div>
                    @endif
                @endforeach
                <div class="mt-6 pt-6 border-t">
                    <div class="bg-blue-50 border-l-4 border-ditoko-navy p-4 mb-4">
                        <p class="text-sm text-gray-700">• Notifikasi WhatsApp otomatis untuk order & pembayaran<br>• Format nomor: 08123456789</p>
                    </div>
                    <button type="submit" class="w-full bg-ditoko-navy text-white px-6 py-3 rounded-lg hover:bg-blue-900">💾 Simpan Pengaturan WhatsApp</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Test WhatsApp --}}
    <div class="mt-6 bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-ditoko-navy">Test WhatsApp</h3>
            <form action="{{ route('admin.settings.test-whatsapp') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="phone" placeholder="08123456789" class="flex-1 border-gray-300 rounded-lg" required>
                <button type="submit" class="px-6 py-2 bg-ditoko-orange text-white rounded-lg hover:bg-orange-700">📱 Test</button>
            </form>
        </div>
    </div>

    {{-- Test Log --}}
    @if(session('whatsapp_log'))
    <div class="mt-6 bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-ditoko-navy">Log Test WhatsApp</h3>
            <pre class="bg-gray-900 text-green-400 p-4 rounded-lg text-sm overflow-x-auto" style="max-height: 300px;">@foreach(session('whatsapp_log') as $line)>{{ $line }}

@endforeach</pre>
        </div>
    </div>
    @endif
</div>
@endsection
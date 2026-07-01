@extends('admin.layouts.app')

@section('title', 'Pengaturan Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-ditoko-navy">Pengaturan Pembayaran (Bank & QRIS)</h3>
            <form action="{{ route('admin.settings.bank.update') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Bank --}}
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-700 mb-3">Transfer Bank</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                            <input type="text" name="settings[bank_name]" value="{{ $bankSettings['bank_name'] ?? '' }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                            <input type="text" name="settings[bank_account]" value="{{ $bankSettings['bank_account'] ?? '' }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Atas Nama</label>
                            <input type="text" name="settings[bank_holder]" value="{{ $bankSettings['bank_holder'] ?? '' }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                {{-- QRIS --}}
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-700 mb-3">QRIS</h4>
                    @php $qris = $bankSettings['qris_image'] ?? ''; @endphp
                    @if($qris)
                        <div class="mb-3">
                            <img src="{{ $qris }}" alt="QRIS" class="h-40 w-auto rounded border">
                            <label class="inline-flex items-center mt-2 text-sm text-gray-600">
                                <input type="checkbox" name="remove_qris" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                Hapus QRIS
                            </label>
                        </div>
                    @endif
                    <input type="file" name="qris_image" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-ditoko-navy file:text-white hover:file:bg-blue-900">
                    <p class="mt-1 text-xs text-gray-500">Format: JPEG, PNG, JPG. Maks 1MB.</p>
                </div>

                {{-- Payment Instructions --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instruksi Pembayaran</label>
                    <textarea name="settings[payment_instructions]" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ $bankSettings['payment_instructions'] ?? '' }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Instruksi yang akan ditampilkan ke buyer saat checkout.</p>
                </div>

                <button type="submit"
                        class="px-6 py-2.5 bg-ditoko-navy text-white rounded-lg hover:bg-blue-900 transition text-sm font-semibold">
                    Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
@extends('admin.layouts.app')

@section('title', 'Update Web')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
    @endif

    {{-- Token Settings --}}
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-ditoko-navy">Konfigurasi Token GitHub</h3>
            <form action="{{ route('admin.update-web.save-token') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">GitHub Personal Access Token</label>
                    <div class="relative">
                        <input type="password" name="github_token" value="{{ old('github_token', $token) }}"
                               class="w-full border-gray-300 rounded-lg focus:border-ditoko-navy focus:ring-ditoko-navy pr-10"
                               placeholder="github_pat_xxxxxxxxxxxx" id="github_token_input">
                        <button type="button" onclick="toggleTokenField()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                            <svg id="token_eye" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="token_eye_off" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Token dengan akses ke repository ini. Disimpan di database.</p>
                </div>
                <button type="submit" class="w-full bg-ditoko-navy text-white px-6 py-3 rounded-lg hover:bg-blue-900">💾 Simpan Token</button>
            </form>
        </div>
    </div>

    {{-- Update Web --}}
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-ditoko-navy">Update Web dari GitHub</h3>
            <p class="text-sm text-gray-600 mb-4">Menarik perubahan terbaru dari repository <code>main</code> branch.</p>
            <button id="updateBtn"
                    class="w-full bg-ditoko-navy text-white px-6 py-3 rounded-lg hover:bg-blue-900 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Update Web
            </button>
        </div>
    </div>

    {{-- Output Log --}}
    @if(session('update_output'))
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold mb-4 text-ditoko-navy">Output Update</h3>
            <pre class="bg-gray-900 text-green-400 p-4 rounded-lg text-sm overflow-x-auto whitespace-pre-wrap" style="max-height: 400px;">{{ session('update_output') }}</pre>
        </div>
    </div>
    @endif
</div>

<script>
function toggleTokenField() {
    const input = document.getElementById('github_token_input');
    const eye = document.getElementById('token_eye');
    const eyeOff = document.getElementById('token_eye_off');
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

document.getElementById('updateBtn')?.addEventListener('click', function() {
    Swal.fire({
        title: 'Update Web?',
        html: `
            <p class="mb-2">Tarik perubahan terbaru dari GitHub?</p>
            <p class="text-xs text-gray-500">Proses ini akan menjalankan <code>git pull origin main</code>.</p>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1e3a5f',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Mengupdate...',
                html: '<p class="text-sm text-gray-600">Menarik perubahan dari GitHub, mohon tunggu.</p>',
                didOpen: () => { Swal.showLoading(); },
                allowOutsideClick: false,
                allowEscapeKey: false
            });

            fetch('{{ route("admin.update-web.do-update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                }
                // Reload to show output
                setTimeout(() => window.location.reload(), 1500);
            })
            .catch(() => {
                Swal.close();
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server.' });
            });
        }
    });
});
</script>
@endsection
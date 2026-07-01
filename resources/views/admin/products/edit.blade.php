@extends('admin.layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Produk</h1>
    <p class="text-gray-500 mt-1">Perbarui informasi produk digital.</p>
</div>

<div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-ditoko-orange focus:ring-ditoko-orange @error('name') border-red-500 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="5" required
                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-ditoko-orange focus:ring-ditoko-orange @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0"
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-ditoko-orange focus:ring-ditoko-orange @error('price') border-red-500 @enderror">
            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Sampul (Max 400KB)</label>
            @if ($product->cover_image && file_exists(public_path('storage/' . $product->cover_image)))
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $product->cover_image) }}" class="w-32 h-32 object-cover rounded-lg">
                </div>
            @endif
            <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-ditoko-orange focus:ring-ditoko-orange @error('cover_image') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            @error('cover_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tautan Eksternal</label>
            <input type="url" name="external_link" value="{{ old('external_link', $product->external_link) }}" placeholder="https://drive.google.com/..."
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-ditoko-orange focus:ring-ditoko-orange @error('external_link') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Isi jika produk dari sumber luar (Google Drive, dll). Upload file atau isi tautan, tidak keduanya.</p>
            @if ($product->external_link)
                <p class="text-xs text-blue-600 mt-1">Tautan saat ini: <a href="{{ $product->external_link }}" target="_blank" class="underline">{{ $product->external_link }}</a></p>
            @endif
            @error('external_link') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">File Produk (PDF/ZIP/RAR/DOC - Max 100MB)</label>
            @if ($product->file_path)
                <p class="text-sm text-gray-600 mb-2">File saat ini: <strong>{{ basename($product->file_path) }}</strong></p>
            @endif
            <input type="file" name="file_product"
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-ditoko-orange focus:ring-ditoko-orange @error('file_product') border-red-500 @enderror">
            <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah file.</p>
            @error('file_product') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="px-6 py-2.5 bg-ditoko-orange text-white rounded-lg hover:bg-orange-600 transition font-medium">
                Update Produk
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 text-gray-600 hover:text-gray-800 transition">Batal</a>
        </div>
    </form>
</div>
@endsection

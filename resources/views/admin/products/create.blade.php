{{-- resources/views/admin/products/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Tambah Produk Baru</h1>
    <p class="text-gray-400 mt-1">Lengkapi data produk di bawah ini</p>
</div>

<div class="admin-card max-w-2xl">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            {{-- Name --}}
            <div>
                <label class="block text-gray-400 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="admin-input @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Variant --}}
            <div>
                <label class="block text-gray-400 mb-2">Varian <span class="text-red-500">*</span></label>
                <select name="variant" class="admin-input @error('variant') border-red-500 @enderror" required>
                    <option value="">Pilih Varian</option>
                    <option value="Tuna" {{ old('variant') == 'Tuna' ? 'selected' : '' }}>Tuna</option>
                    <option value="Cumi" {{ old('variant') == 'Cumi' ? 'selected' : '' }}>Cumi</option>
                    <option value="Bawang" {{ old('variant') == 'Bawang' ? 'selected' : '' }}>Bawang</option>
                </select>
                @error('variant')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-gray-400 mb-2">Deskripsi</label>
                <textarea name="description"
                          rows="4"
                          class="admin-input @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Price & Stock --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number"
                           name="price"
                           value="{{ old('price') }}"
                           class="admin-input @error('price') border-red-500 @enderror"
                           required>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-400 mb-2">Stok <span class="text-red-500">*</span></label>
                    <input type="number"
                           name="stock"
                           value="{{ old('stock') }}"
                           class="admin-input @error('stock') border-red-500 @enderror"
                           required>
                    @error('stock')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Image Upload --}}
            <div>
                <label class="block text-gray-400 mb-2">Gambar Produk</label>
                <div class="border-2 border-dashed border-gray-600 rounded-xl p-6 text-center hover:border-red-500 transition cursor-pointer"
                     onclick="document.getElementById('image').click()">
                    <input type="file"
                           id="image"
                           name="image"
                           accept="image/*"
                           class="hidden"
                           onchange="previewImage(this)">
                    <div id="upload-placeholder">
                        <span class="text-4xl mb-2 block">📸</span>
                        <p class="text-gray-400">Klik untuk upload gambar</p>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Max 2MB)</p>
                    </div>
                    <div id="image-preview" class="hidden">
                        <img id="preview" src="#" alt="Preview" class="max-h-48 mx-auto rounded-lg">
                        <p class="text-sm text-green-500 mt-2">✓ Gambar siap diupload</p>
                    </div>
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active Status --}}
            <div class="flex items-center">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-red-600 bg-gray-700 border-gray-600 rounded focus:ring-red-500">
                <label class="ml-2 text-gray-400">Aktif (produk akan ditampilkan)</label>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" class="btn-primary px-8 py-3">
                    Simpan Produk
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary px-8 py-3">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('upload-placeholder');
    const previewContainer = document.getElementById('image-preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            placeholder.classList.add('hidden');
            previewContainer.classList.remove('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
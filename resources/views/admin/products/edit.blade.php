{{-- resources/views/admin/products/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Edit Produk</h1>
    <p class="text-gray-400 mt-1">Update informasi produk</p>
</div>

<div class="admin-card max-w-2xl">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            {{-- Current Image --}}
            @if($product->image)
            <div>
                <label class="block text-gray-400 mb-2">Gambar Saat Ini</label>
                <img src="{{ asset('storage/' . $product->image) }}" class="w-32 h-32 rounded-lg object-cover">
            </div>
            @endif

            {{-- Name --}}
            <div>
                <label class="block text-gray-400 mb-2">Nama Produk</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $product->name) }}"
                       class="admin-input @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Variant --}}
            <div>
                <label class="block text-gray-400 mb-2">Varian</label>
                <select name="variant" class="admin-input @error('variant') border-red-500 @enderror" required>
                    <option value="Tuna" {{ old('variant', $product->variant) == 'Tuna' ? 'selected' : '' }}>Tuna</option>
                    <option value="Cumi" {{ old('variant', $product->variant) == 'Cumi' ? 'selected' : '' }}>Cumi</option>
                    <option value="Bawang" {{ old('variant', $product->variant) == 'Bawang' ? 'selected' : '' }}>Bawang</option>
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
                          class="admin-input @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Price & Stock --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 mb-2">Harga (Rp)</label>
                    <input type="number"
                           name="price"
                           value="{{ old('price', $product->price) }}"
                           class="admin-input @error('price') border-red-500 @enderror"
                           required>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-400 mb-2">Stok</label>
                    <input type="number"
                           name="stock"
                           value="{{ old('stock', $product->stock) }}"
                           class="admin-input @error('stock') border-red-500 @enderror"
                           required>
                    @error('stock')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Image Upload --}}
            <div>
                <label class="block text-gray-400 mb-2">Gambar Baru (Kosongkan jika tidak ingin mengubah)</label>
                <input type="file"
                       name="image"
                       accept="image/*"
                       class="admin-input @error('image') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Max 2MB)</p>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active Status --}}
            <div class="flex items-center">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-red-600 bg-gray-700 border-gray-600 rounded">
                <label class="ml-2 text-gray-400">Aktif</label>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" class="btn-primary px-8 py-3">
                    Update Produk
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary px-8 py-3">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
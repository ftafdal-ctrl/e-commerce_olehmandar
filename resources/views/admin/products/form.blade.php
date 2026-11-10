@extends('layouts.admin')

@section('title', $product ? 'Edit Produk' : 'Tambah Produk')
@section('page-title', $product ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}"
              method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if($product) @method('PUT') @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk *</label>
                    <input type="text" name="name" value="{{ old('name', $product?->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('name') border-red-400 @enderror" required>
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select name="category_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('category_id') border-red-400 @enderror" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product?->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga 500 Gram (Rp) *</label>
                    <input type="number" name="price_500g" value="{{ old('price_500g', $product?->price_500g ?? $product?->price) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('price_500g') border-red-400 @enderror" required>
                    @error('price_500g') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga 1 Kilo (Rp) *</label>
                    <input type="number" name="price_1kg" value="{{ old('price_1kg', $product?->price_1kg ?? ($product?->price ? $product->price * 2 : null)) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('price_1kg') border-red-400 @enderror" required>
                    @error('price_1kg') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product?->stock ?? 0) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('stock') border-red-400 @enderror" required>
                    @error('stock') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Berat (gram)</label>
                    <input type="text" name="weight" value="{{ old('weight', $product?->weight) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500"
                           placeholder="250">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">{{ old('description', $product?->description) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
                    @if($product?->image)
                        <img src="{{ $product->image_url }}" class="w-32 h-32 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">
                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 3MB.</p>
                    @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-mandar-600 focus:ring-mandar-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Produk Aktif (tampil di toko)</label>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit" class="btn-primary">{{ $product ? 'Simpan Perubahan' : 'Tambah Produk' }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

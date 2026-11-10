@extends('layouts.admin')

@section('title', $category ? 'Edit Kategori' : 'Tambah Kategori')
@section('page-title', $category ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
              method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if($category) @method('PUT') @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
                <input type="text" name="name" value="{{ old('name', $category?->name) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('name') border-red-400 @enderror" required>
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">{{ old('description', $category?->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Kategori</label>
                @if($category?->image)
                    <img src="{{ asset('storage/' . $category->image) }}" class="w-24 h-24 object-cover rounded-lg mb-2">
                @endif
                <input type="file" name="image" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">
                @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">{{ $category ? 'Simpan Perubahan' : 'Tambah Kategori' }}</button>
                <a href="{{ route('admin.categories.index') }}" class="btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

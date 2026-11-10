@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Kategori')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $categories->count() }} kategori</p>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary text-sm">+ Tambah Kategori</a>
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jumlah Produk</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 bg-cream-50 rounded-lg flex items-center justify-center text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                                    </svg>
                                </div>
                            @endif
                            <span class="font-medium text-gray-800">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $category->slug }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $category->products_count }}</td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-mandar-600 hover:underline text-xs">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada kategori</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

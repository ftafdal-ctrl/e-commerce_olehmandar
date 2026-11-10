@extends('layouts.admin')

@section('title', 'Manajemen Produk')
@section('page-title', 'Produk')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <form action="{{ route('admin.products.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 w-48">
        <select name="category" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary py-2 px-4 text-sm">Filter</button>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn-primary text-sm">+ Tambah Produk</a>
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Produk</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Harga</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stok</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-cream-50 rounded-lg overflow-hidden flex-shrink-0">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <span class="font-medium text-gray-800 max-w-[160px] truncate">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $product->category->name }}</td>
                    <td class="px-4 py-3 text-xs text-mandar-600">
                        <div>500 Gram: Rp {{ number_format($product->getPriceForWeight('500g'), 0, ',', '.') }}</div>
                        <div>1 Kilo: Rp {{ number_format($product->getPriceForWeight('1kg'), 0, ',', '.') }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="{{ $product->stock <= 5 ? 'text-red-500 font-semibold' : 'text-gray-700' }}">{{ $product->stock }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($product->is_active)
                            <span class="badge-confirmed">Aktif</span>
                        @else
                            <span class="badge-cancelled">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-mandar-600 hover:underline text-xs">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada produk</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-50">
        {{ $products->links() }}
    </div>
</div>
@endsection

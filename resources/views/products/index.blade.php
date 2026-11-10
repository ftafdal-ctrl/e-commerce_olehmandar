@extends('layouts.app')

@section('title', 'Semua Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-mandar-700">Semua Produk</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $products->total() }} produk tersedia</p>
        </div>

        {{-- Search --}}
        <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari produk..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 w-48">
            <button type="submit" class="btn-primary py-2 px-4 text-sm">Cari</button>
        </form>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar Filter --}}
        <aside class="w-full lg:w-56 flex-shrink-0">
            <div class="card p-4 sticky top-20">
                <h3 class="font-semibold text-gray-700 mb-3 text-sm uppercase tracking-wide">Kategori</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('products.index', array_merge(request()->except('category', 'page'), [])) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition {{ !request('category') ? 'bg-mandar-600 text-white font-medium' : 'text-gray-600 hover:bg-cream-50 hover:text-mandar-600' }}">
                            Semua Produk
                        </a>
                    </li>
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('products.index', array_merge(request()->except('category', 'page'), ['category' => $category->slug])) }}"
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition {{ request('category') === $category->slug ? 'bg-mandar-600 text-white font-medium' : 'text-gray-600 hover:bg-cream-50 hover:text-mandar-600' }}">
                                <span>{{ $category->name }}</span>
                                <span class="text-xs opacity-70">{{ $category->products_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="border-t border-gray-100 mt-4 pt-4">
                    <h3 class="font-semibold text-gray-700 mb-3 text-sm uppercase tracking-wide">Urutkan</h3>
                    <form action="{{ route('products.index') }}" method="GET">
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        <select name="sort" onchange="this.form.submit()"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">
                            <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga: Termurah</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga: Termahal</option>
                        </select>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Product Grid --}}
        <div class="flex-1">
            @if($products->count())
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($products as $product)
                        @include('products._card', ['product' => $product])
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-20 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-medium">Produk tidak ditemukan</p>
                    <a href="{{ route('products.index') }}" class="text-mandar-600 text-sm hover:underline mt-2 inline-block">Lihat semua produk</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

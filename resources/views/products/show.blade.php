@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-mandar-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-mandar-600">Produk</a>
        <span>/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-mandar-600">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        {{-- Product Image --}}
        <div class="card overflow-hidden">
            @if($product->image)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full aspect-square object-cover">
            @else
                <div class="aspect-square bg-cream-50 flex items-center justify-center text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div class="flex flex-col">
            <span class="text-mandar-500 font-medium text-sm mb-2">{{ $product->category->name }}</span>
            <h1 class="text-3xl font-bold text-gray-800 mb-3">{{ $product->name }}</h1>

            <div class="flex items-center gap-4 mb-4">
                <span class="text-sm text-gray-500">Mulai dari</span>
                <span class="text-3xl font-bold text-mandar-600">{{ $product->getFormattedPriceForWeight('500g') }}</span>
            </div>

            @if($product->stock > 0)
                <span class="inline-flex items-center gap-1 text-green-600 text-sm font-medium mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Tersedia ({{ $product->stock }} tersisa)
                </span>
            @else
                <span class="inline-flex items-center gap-1 text-red-500 text-sm font-medium mb-4">
                    Stok Habis
                </span>
            @endif

            <p class="text-gray-600 text-sm leading-relaxed mb-6">{{ $product->description }}</p>

            @if($product->stock > 0)
                <form action="{{ route('cart.store') }}" method="POST" class="space-y-4" id="addToCartForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Pilihan Berat --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Berat</label>
                        <div class="flex gap-3">
                            <label class="weight-option flex-1 cursor-pointer">
                                <input type="radio" name="weight_option" value="500g" class="sr-only" checked>
                                <div class="weight-label border-2 border-mandar-600 bg-mandar-50 rounded-xl p-3 text-center transition">
                                    <p class="font-bold text-mandar-700 text-sm">500 Gram</p>
                                    <p class="text-mandar-600 font-semibold text-base mt-0.5">{{ $product->getFormattedPriceForWeight('500g') }}</p>
                                </div>
                            </label>
                            <label class="weight-option flex-1 cursor-pointer">
                                <input type="radio" name="weight_option" value="1kg" class="sr-only">
                                <div class="weight-label border-2 border-gray-200 rounded-xl p-3 text-center transition">
                                    <p class="font-bold text-gray-700 text-sm">1 Kilo</p>
                                    <p class="text-gray-600 font-semibold text-base mt-0.5">{{ $product->getFormattedPriceForWeight('1kg') }}</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Qty & Tombol --}}
                    <div class="flex gap-3 items-center">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button type="button" onclick="decrementQty()" class="px-4 py-2.5 text-gray-600 hover:text-mandar-600 font-bold text-lg">−</button>
                            <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-16 text-center border-x border-gray-300 py-2.5 text-sm focus:outline-none">
                            <button type="button" onclick="incrementQty({{ $product->stock }})" class="px-4 py-2.5 text-gray-600 hover:text-mandar-600 font-bold text-lg">+</button>
                        </div>
                        <button type="submit" class="flex-1 btn-primary py-2.5">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </form>

                <style>
                    input[type="radio"]:checked + .weight-label {
                        border-color: #8B1A1A;
                        background-color: #fdf4f4;
                    }
                    input[type="radio"]:checked + .weight-label p { color: #8B1A1A; }
                    .weight-label { transition: all 0.15s ease; }
                </style>
            @else
                <button disabled class="w-full bg-gray-200 text-gray-400 font-semibold py-3 px-5 rounded-lg cursor-not-allowed">
                    Stok Habis
                </button>
            @endif
        </div>
    </div>

    {{-- Related Products --}}
    @if($related->count())
        <div class="mt-14">
            <h2 class="text-xl font-bold text-mandar-700 mb-6">Produk Serupa</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach($related as $rel)
                    @include('products._card', ['product' => $rel])
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
function decrementQty() {
    const input = document.getElementById('qty');
    if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
}
function incrementQty(max) {
    const input = document.getElementById('qty');
    if (parseInt(input.value) < max) input.value = parseInt(input.value) + 1;
}
</script>
@endsection

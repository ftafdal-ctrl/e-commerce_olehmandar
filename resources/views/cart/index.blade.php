@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-mandar-700 mb-6">Keranjang Belanja</h1>

    @if($cart && $cart->items->count())
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart->items as $item)
                    <div class="card p-4 flex gap-4">
                        {{-- Product Image --}}
                        <a href="{{ route('products.show', $item->product->slug) }}" class="flex-shrink-0">
                            <div class="w-20 h-20 bg-cream-50 rounded-lg overflow-hidden">
                                @if($item->product->image)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </a>

                        {{-- Product Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-mandar-500">{{ $item->product->category->name }}</p>
                            <a href="{{ route('products.show', $item->product->slug) }}" class="font-semibold text-gray-800 text-sm hover:text-mandar-600 transition">{{ $item->product->name }}</a>
                            <span class="inline-block mt-1 text-xs bg-cream-50 border border-mandar-200 text-mandar-700 font-medium px-2 py-0.5 rounded-full">
                                {{ $item->weight_label }}
                            </span>
                            <p class="text-mandar-600 font-bold text-sm mt-1">
                                Rp {{ number_format($item->price_at_add ?? $item->product->getPriceForWeight($item->weight_option ?? '500g'), 0, ',', '.') }}
                                <span class="text-gray-400 font-normal text-xs">/ pcs</span>
                            </p>
                        </div>

                        {{-- Quantity & Actions --}}
                        <div class="flex flex-col items-end justify-between gap-2">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-gray-300 rounded-lg">
                                @csrf @method('PATCH')
                                <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}"
                                        class="px-3 py-1.5 text-gray-600 hover:text-mandar-600 font-bold">−</button>
                                <span class="px-3 py-1.5 text-sm font-medium border-x border-gray-300">{{ $item->quantity }}</span>
                                <button type="submit" name="quantity" value="{{ min($item->quantity + 1, $item->product->stock) }}"
                                        class="px-3 py-1.5 text-gray-600 hover:text-mandar-600 font-bold">+</button>
                            </form>

                            <p class="text-sm font-semibold text-gray-700">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>

                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-20">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">Ringkasan Pesanan</h2>

                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        @foreach($cart->items as $item)
                            <div class="flex justify-between">
                                <span class="truncate max-w-[160px]">{{ $item->product->name }} ({{ $item->weight_label }}) ×{{ $item->quantity }}</span>
                                <span class="font-medium text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 pt-4 mb-5">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span class="text-mandar-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('checkout.index') }}" class="w-full btn-primary block text-center">
                            Lanjut ke Checkout
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full btn-primary block text-center">
                            Masuk untuk Checkout
                        </a>
                        <p class="text-xs text-center text-gray-400 mt-2">
                            Belum punya akun? <a href="{{ route('register') }}" class="text-mandar-600 hover:underline">Daftar gratis</a>
                        </p>
                    @endauth
                </div>
            </div>
        </div>

    @else
        <div class="text-center py-20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h2 class="text-xl font-semibold text-gray-500 mb-2">Keranjang masih kosong</h2>
            <p class="text-gray-400 text-sm mb-6">Tambahkan produk oleh-oleh Mandar favorit Anda</p>
            <a href="{{ route('products.index') }}" class="btn-primary">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection

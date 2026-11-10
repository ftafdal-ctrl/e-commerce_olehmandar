@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- Hero Section --}}
<section class="bg-gradient-to-br from-mandar-700 via-mandar-600 to-mandar-800 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-10">
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                Cita Rasa Autentik<br>
                <span class="text-gold-400">Oleh-Oleh Khas Mandar</span>
            </h1>
            <p class="text-cream-100 text-lg mb-8 max-w-lg">
                Nikmati kelezatan Baje, Golla Kambu, Kasippi, dan produk khas Mandar lainnya. Dikirim langsung dari Sulawesi Barat ke seluruh Indonesia.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                <a href="{{ route('products.index') }}" class="btn-secondary text-center">
                    Lihat Produk
                </a>
                <a href="{{ route('products.index') }}" class="border-2 border-cream-50 text-white hover:bg-white hover:text-mandar-700 font-semibold py-2 px-5 rounded-lg transition duration-200 text-center">
                    Belanja Sekarang
                </a>
            </div>
        </div>
        <div class="flex-1 flex justify-center">
            <img src="{{ asset('images/logo.svg') }}" alt="OlehMandar" class="h-56 w-auto drop-shadow-2xl brightness-0 invert opacity-90">
        </div>
    </div>
</section>

{{-- Keunggulan --}}
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6">
                <div class="w-12 h-12 bg-mandar-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-mandar-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">100% Produk Asli</h3>
                <p class="text-sm text-gray-500">Dibuat langsung oleh pengrajin lokal Mandar dengan resep turun-temurun.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-12 h-12 bg-gold-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Pengiriman ke Seluruh Indonesia</h3>
                <p class="text-sm text-gray-500">Kami kirim ke seluruh wilayah Indonesia dengan berbagai pilihan ekspedisi.</p>
            </div>
            <div class="text-center p-6">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Pembayaran Aman</h3>
                <p class="text-sm text-gray-500">Transfer bank dengan konfirmasi manual yang terpercaya dan terverifikasi.</p>
            </div>
        </div>
    </div>
</section>

{{-- Produk Unggulan --}}
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-mandar-700">Produk Unggulan</h2>
            <p class="text-gray-500 mt-2">Pilihan terbaik oleh-oleh khas Mandar yang paling diminati</p>
        </div>

        @if(isset($featuredProducts) && $featuredProducts->count())
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    @include('products._card', ['product' => $product])
                @endforeach
            </div>
        @else
            <div class="text-center py-16 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p>Produk sedang dalam persiapan</p>
            </div>
        @endif

        <div class="text-center mt-10">
            <a href="{{ route('products.index') }}" class="btn-outline">Lihat Semua Produk</a>
        </div>
    </div>
</section>

@endsection

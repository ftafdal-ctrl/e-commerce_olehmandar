@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-mandar-700 mb-6">Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Form Pengiriman --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">Informasi Pengiriman</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima *</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', Auth::user()->name) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('shipping_name') border-red-400 @enderror" required>
                            @error('shipping_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP *</label>
                            <input type="text" name="shipping_phone" value="{{ old('shipping_phone', Auth::user()->phone) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('shipping_phone') border-red-400 @enderror" required>
                            @error('shipping_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                            <textarea name="shipping_address" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('shipping_address') border-red-400 @enderror"
                                      required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten *</label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('shipping_city') border-red-400 @enderror" required>
                            @error('shipping_city') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi *</label>
                            <input type="text" name="shipping_province" value="{{ old('shipping_province') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 @error('shipping_province') border-red-400 @enderror" required>
                            @error('shipping_province') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                            <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                            <input type="text" name="notes" value="{{ old('notes') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500"
                                   placeholder="Instruksi khusus untuk kurir...">
                        </div>
                    </div>
                </div>

                {{-- Info Pembayaran --}}
                <div class="card p-6 bg-amber-50 border border-amber-200">
                    <h2 class="font-bold text-amber-800 text-lg mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Informasi Pembayaran
                    </h2>
                    <p class="text-sm text-amber-700">
                        Pembayaran hanya menggunakan QRIS. Setelah pesanan dibuat, pindai QRIS toko di halaman detail pesanan dan unggah bukti pembayaran.
                        @unless (file_exists(public_path('images/qris.png')))
                            <strong>QRIS toko belum tersedia.</strong>
                        @endunless
                    </p>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-20">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">Ringkasan Pesanan</h2>

                    <div class="space-y-2 text-sm text-gray-600 mb-4 max-h-48 overflow-y-auto">
                        @foreach($cart->items as $item)
                            <div class="flex justify-between gap-2">
                                <span class="text-gray-700 flex-1">{{ $item->product->name }} ({{ $item->weight_label }}) <span class="text-gray-400">×{{ $item->quantity }}</span></span>
                                <span class="font-medium text-gray-800 whitespace-nowrap">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 pt-4 mb-5">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span class="text-mandar-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full btn-primary">
                        Buat Pesanan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

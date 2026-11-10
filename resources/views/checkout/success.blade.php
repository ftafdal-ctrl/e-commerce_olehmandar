@extends('layouts.app')

@section('title', 'Pesanan Berhasil Dibuat')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Success Header --}}
    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Pesanan Berhasil Dibuat!</h1>
        <p class="text-gray-500 text-sm mt-2">Terima kasih telah berbelanja di <span class="text-mandar-600 font-semibold">olehMandar</span></p>
    </div>

    {{-- Order Details --}}
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-gray-700">Detail Pesanan</h2>
            <span class="badge-pending">Menunggu Pembayaran</span>
        </div>

        <dl class="space-y-2 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-500">Nomor Pesanan</dt>
                <dd class="font-bold text-mandar-600">{{ $order->order_number }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Tanggal</dt>
                <dd class="font-medium text-gray-700">{{ $order->created_at->isoFormat('D MMMM Y, HH:mm') }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500">Total Pembayaran</dt>
                <dd class="font-bold text-lg text-mandar-600">{{ $order->formatted_total }}</dd>
            </div>
        </dl>

        <div class="border-t border-gray-100 mt-4 pt-4 space-y-2">
            @foreach($order->items as $item)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>{{ $item->product_name }} ×{{ $item->quantity }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Instruksi QRIS --}}
    <div class="card p-6 mb-6 border-l-4 border-gold-500">
        <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold-500" viewBox="0 0 20 20" fill="currentColor">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
            </svg>
            Instruksi Pembayaran QRIS
        </h2>

        <p class="text-sm text-gray-600 mb-4">Bayar tepat sebesar <strong class="text-mandar-600">{{ $order->formatted_total }}</strong> menggunakan QRIS berikut.</p>
        @if (file_exists(public_path('images/qris.png')))
            <img src="{{ asset('images/qris.png') }}" alt="QRIS pembayaran olehMandar" class="w-64 max-w-full mx-auto rounded-lg border border-gray-200">
        @else
            <p class="rounded-lg bg-amber-50 p-4 text-sm text-amber-800">
                QRIS toko belum tersedia. Pembayaran QRIS belum dapat dilakukan sampai QRIS resmi ditambahkan ke <code>public/images/qris.png</code>.
            </p>
        @endif
        <p class="text-xs text-gray-400 mt-3">Setelah membayar, unggah bukti pembayaran pada detail pesanan. Nomor pesanan: <strong>{{ $order->order_number }}</strong>.</p>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('orders.show', $order->order_number) }}" class="flex-1 btn-primary text-center">
            Unggah Bukti Pembayaran QRIS
        </a>
        <a href="{{ route('orders.index') }}" class="flex-1 btn-outline text-center">
            Lihat Semua Pesanan
        </a>
        <a href="{{ route('home') }}" class="flex-1 border border-gray-300 text-gray-600 hover:bg-gray-50 font-semibold py-2 px-5 rounded-lg transition text-center text-sm">
            Kembali Belanja
        </a>
    </div>
</div>
@endsection

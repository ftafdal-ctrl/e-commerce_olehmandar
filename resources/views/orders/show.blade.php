@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <a href="{{ route('orders.index') }}" class="text-sm text-mandar-600 hover:underline flex items-center gap-1 mb-2">
                ← Kembali ke Pesanan
            </a>
            <h1 class="text-2xl font-bold text-mandar-700">{{ $order->order_number }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $order->created_at->isoFormat('D MMMM Y, HH:mm') }}</p>
        </div>
        <span class="badge-{{ $order->status }} text-sm py-1 px-3">{{ $order->status_label }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Items + Payment --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Items --}}
            <div class="card p-6">
                <h2 class="font-bold text-gray-700 mb-4">Produk yang Dipesan</h2>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="py-3 flex justify-between items-center gap-4">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800 text-sm">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-400">Rp {{ number_format($item->product_price, 0, ',', '.') }} × {{ $item->quantity }}</p>
                            </div>
                            <p class="font-semibold text-gray-700 text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 mt-2 pt-3 flex justify-between font-bold">
                    <span>Total</span>
                    <span class="text-mandar-600">{{ $order->formatted_total }}</span>
                </div>
            </div>

            {{-- Upload Bukti Transfer --}}
            @if(!$order->payment && $order->status === 'pending')
                <div class="card p-6 border-l-4 border-gold-500">
                    <h2 class="font-bold text-gray-800 mb-1">Upload Bukti Transfer</h2>
                    <p class="text-sm text-gray-500 mb-4">Transfer ke rekening BRI: <strong class="text-mandar-600">1234-5678-9012</strong> a.n. olehMandar Store sebesar <strong>{{ $order->formatted_total }}</strong></p>

                    <form action="{{ route('orders.payment.store', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Nama Bank *</label>
                                <input type="text" name="bank_name" placeholder="BRI / BCA / Mandiri..."
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Rekening Pengirim *</label>
                                <input type="text" name="account_number"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Atas Nama *</label>
                                <input type="text" name="account_name"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Foto Bukti Transfer *</label>
                                <input type="file" name="proof_image" accept="image/*"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500" required>
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                            </div>
                        </div>
                        <button type="submit" class="btn-secondary">Kirim Bukti Transfer</button>
                    </form>
                </div>
            @elseif($order->payment)
                <div class="card p-6">
                    <h2 class="font-bold text-gray-700 mb-3">Status Pembayaran</h2>
                    <div class="flex items-center gap-3">
                        @if($order->payment->status === 'confirmed')
                            <span class="badge-confirmed">Pembayaran Dikonfirmasi</span>
                        @elseif($order->payment->status === 'rejected')
                            <span class="badge-cancelled">Pembayaran Ditolak</span>
                        @else
                            <span class="badge-pending">Menunggu Verifikasi Admin</span>
                        @endif
                    </div>
                    @if($order->payment->proof_image_url)
                        <a href="{{ $order->payment->proof_image_url }}" target="_blank" class="mt-3 inline-block text-sm text-mandar-600 hover:underline">
                            Lihat bukti transfer →
                        </a>
                    @endif
                    @if($order->payment->rejection_reason)
                        <p class="mt-2 text-sm text-red-600">Alasan penolakan: {{ $order->payment->rejection_reason }}</p>
                    @endif
                </div>
            @endif

        </div>

        {{-- Right: Shipping Info + Tracking --}}
        <div class="space-y-6">

            {{-- Alamat --}}
            <div class="card p-5">
                <h2 class="font-bold text-gray-700 mb-3 text-sm">Alamat Pengiriman</h2>
                <p class="font-semibold text-gray-800 text-sm">{{ $order->shipping_name }}</p>
                <p class="text-sm text-gray-600">{{ $order->shipping_phone }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                @if($order->notes)
                    <p class="text-xs text-gray-400 mt-2 italic">Catatan: {{ $order->notes }}</p>
                @endif
            </div>

            {{-- Tracking --}}
            @if($order->shippingStatuses->count())
                <div class="card p-5">
                    <h2 class="font-bold text-gray-700 mb-4 text-sm">Tracking Pengiriman</h2>
                    <div class="relative">
                        <div class="absolute left-2.5 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        <div class="space-y-4">
                            @foreach($order->shippingStatuses as $status)
                                <div class="relative pl-8">
                                    <div class="absolute left-0 top-1 w-5 h-5 bg-mandar-600 rounded-full border-2 border-white flex items-center justify-center">
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                    <p class="font-semibold text-sm text-gray-800">{{ $status->status_label }}</p>
                                    @if($status->description)
                                        <p class="text-xs text-gray-500">{{ $status->description }}</p>
                                    @endif
                                    @if($status->tracking_number)
                                        <p class="text-xs text-mandar-600">Resi: {{ $status->tracking_number }} ({{ $status->courier }})</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $status->created_at->isoFormat('D MMM Y, HH:mm') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

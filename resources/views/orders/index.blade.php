@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-mandar-700 mb-6">Pesanan Saya</h1>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="mb-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-700">{{ session('warning') }}</div>
    @endif

    @if($orders->count())
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="card p-5 hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="font-bold text-mandar-600">{{ $order->order_number }}</span>
                                <span class="badge-{{ $order->status }}">{{ $order->status_label }}</span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $order->created_at->isoFormat('D MMMM Y, HH:mm') }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $order->items->count() ?? '?' }} produk</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-mandar-600">{{ $order->formatted_total }}</p>
                            @if($order->payment)
                                <span class="text-xs {{ $order->payment->status === 'confirmed' ? 'text-green-600' : ($order->payment->status === 'rejected' ? 'text-red-500' : 'text-amber-600') }}">
                                    Pembayaran: {{ ucfirst($order->payment->status) }}
                                </span>
                            @else
                                <span class="text-xs text-red-500">Belum ada bukti pembayaran</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap justify-end gap-4">
                        <a href="{{ route('orders.show', $order->order_number) }}" class="text-sm text-mandar-600 hover:underline font-medium">
                            Lihat Detail →
                        </a>
                        @if($order->status === 'delivered')
                            <form action="{{ route('orders.destroy', $order->order_number) }}" method="POST"
                                  onsubmit="return confirm('Hapus riwayat pesanan ini? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline font-medium">
                                    Hapus Riwayat
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>

    @else
        <div class="text-center py-20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h2 class="text-xl font-semibold text-gray-500 mb-2">Belum ada pesanan</h2>
            <p class="text-gray-400 text-sm mb-6">Mulai belanja oleh-oleh khas Mandar sekarang!</p>
            <a href="{{ route('products.index') }}" class="btn-primary">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_number)
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-mandar-600 hover:underline">← Kembali ke Pesanan</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kiri: Items + Pembayaran --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Order Info --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-lg text-mandar-600">{{ $order->order_number }}</h2>
                    <p class="text-sm text-gray-500">{{ $order->created_at->isoFormat('D MMMM Y, HH:mm') }}</p>
                </div>
                <div class="text-right">
                    <span class="badge-{{ $order->status }}">{{ $order->status_label }}</span>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Produk Dipesan</h3>
                <div class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                        <div class="py-2.5 flex justify-between text-sm">
                            <div>
                                <span class="font-medium text-gray-800">{{ $item->product_name }}</span>
                                <span class="text-gray-400 ml-1">×{{ $item->quantity }}</span>
                            </div>
                            <span class="font-semibold text-gray-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 mt-2 pt-2 flex justify-between font-bold">
                    <span>Total</span>
                    <span class="text-mandar-600">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Update Status Pesanan</h3>
            <form action="{{ route('admin.orders.updateStatus', $order->order_number) }}" method="POST" class="flex gap-3">
                @csrf @method('PATCH')
                <select name="status" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">
                    @foreach(\App\Models\Order::$statusLabels as $value => $label)
                        <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary text-sm py-2 px-4">Update</button>
            </form>
        </div>

        {{-- Pembayaran --}}
        @if($order->payment)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-3">Bukti Pembayaran</h3>
                <dl class="grid grid-cols-2 gap-2 text-sm mb-4">
                    @if($order->payment->payment_method === 'qris')
                        <dt class="text-gray-500">Metode</dt>
                        <dd class="font-medium text-gray-800">QRIS</dd>
                    @else
                        <dt class="text-gray-500">Bank</dt>
                        <dd class="font-medium text-gray-800">{{ $order->payment->bank_name }}</dd>
                        <dt class="text-gray-500">No. Rekening</dt>
                        <dd class="font-medium text-gray-800">{{ $order->payment->account_number }}</dd>
                        <dt class="text-gray-500">Atas Nama</dt>
                        <dd class="font-medium text-gray-800">{{ $order->payment->account_name }}</dd>
                    @endif
                    <dt class="text-gray-500">Jumlah</dt>
                    <dd class="font-bold text-mandar-600">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</dd>
                    <dt class="text-gray-500">Status</dt>
                    <dd>
                        @if($order->payment->status === 'confirmed')
                            <span class="badge-confirmed">Dikonfirmasi</span>
                        @elseif($order->payment->status === 'rejected')
                            <span class="badge-cancelled">Ditolak</span>
                        @else
                            <span class="badge-pending">Menunggu Verifikasi</span>
                        @endif
                    </dd>
                </dl>

                @if($order->payment->proof_image_url)
                    <a href="{{ $order->payment->proof_image_url }}" target="_blank">
                        <img src="{{ $order->payment->proof_image_url }}" class="w-48 h-48 object-cover rounded-lg border border-gray-200 mb-4">
                    </a>
                @endif

                @if($order->payment->status === 'pending')
                    <div class="flex gap-3">
                        <form action="{{ route('admin.payments.confirm', $order->payment) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary text-sm" onclick="return confirm('Konfirmasi pembayaran ini?')">
                                ✓ Konfirmasi
                            </button>
                        </form>
                        <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')"
                                class="btn-outline text-sm text-red-600 border-red-400 hover:bg-red-50">
                            ✕ Tolak
                        </button>
                    </div>
                    <form id="reject-form" action="{{ route('admin.payments.reject', $order->payment) }}" method="POST" class="hidden mt-3 space-y-2">
                        @csrf
                        <textarea name="rejection_reason" rows="2" placeholder="Alasan penolakan..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required></textarea>
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-1.5 px-4 rounded-lg">Kirim Penolakan</button>
                    </form>
                @endif
            </div>
        @else
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-700">
                Pembeli belum mengirim bukti pembayaran.
            </div>
        @endif

        {{-- Tambah Status Pengiriman --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Tambah Update Pengiriman</h3>
            <form action="{{ route('admin.shipping.store', $order->order_number) }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status *</label>
                        <select name="status"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500" required>
                            <option value="">Pilih status pengiriman</option>
                            @foreach(\App\Models\ShippingStatus::STATUSES as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $order->shippingStatuses->first()?->status) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nomor Resi</label>
                        <input type="text" name="tracking_number"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Kurir</label>
                        <input type="text" name="courier" placeholder="JNE / J&T / SiCepat..."
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan</label>
                        <input type="text" name="description"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">
                    </div>
                </div>
                <button type="submit" class="btn-primary text-sm">Tambah Update</button>
            </form>
        </div>
    </div>

    {{-- Kanan: Info Pembeli + Tracking --}}
    <div class="space-y-5">
        {{-- Info Pembeli --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-3 text-sm">Informasi Pembeli</h3>
            <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
            <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
            <p class="text-sm text-gray-500">{{ $order->user->phone }}</p>
        </div>

        {{-- Alamat --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-3 text-sm">Alamat Pengiriman</h3>
            <p class="font-semibold text-gray-800 text-sm">{{ $order->shipping_name }}</p>
            <p class="text-sm text-gray-600">{{ $order->shipping_phone }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
            @if($order->notes)
                <p class="text-xs text-gray-400 mt-2 italic">{{ $order->notes }}</p>
            @endif
        </div>

        {{-- Tracking --}}
        @if($order->shippingStatuses->count())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-4 text-sm">Riwayat Pengiriman</h3>
                <div class="relative">
                    <div class="absolute left-2.5 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    <div class="space-y-4">
                        @foreach($order->shippingStatuses as $s)
                            <div class="relative pl-8">
                                <div class="absolute left-0 top-1 w-5 h-5 bg-mandar-600 rounded-full border-2 border-white"></div>
                                <p class="font-semibold text-sm text-gray-800">{{ $s->status_label }}</p>
                                @if($s->description) <p class="text-xs text-gray-500">{{ $s->description }}</p> @endif
                                @if($s->tracking_number) <p class="text-xs text-mandar-600">Resi: {{ $s->tracking_number }} ({{ $s->courier }})</p> @endif
                                <p class="text-xs text-gray-400 mt-0.5">{{ $s->created_at->isoFormat('D MMM Y, HH:mm') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

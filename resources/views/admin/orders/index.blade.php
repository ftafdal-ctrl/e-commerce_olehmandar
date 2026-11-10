@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Pesanan')

@section('content')
{{-- Filter --}}
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. pesanan / nama..."
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500 w-52">
        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-mandar-500">
            <option value="">Semua Status</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ \App\Models\Order::$statusLabels[$s] }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary py-2 px-4 text-sm">Filter</button>
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.orders.index') }}" class="btn-outline py-2 px-4 text-sm">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No. Pesanan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pembeli</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pembayaran</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-mandar-600">{{ $order->order_number }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->user->phone }}</p>
                    </td>
                    <td class="px-4 py-3 font-semibold">{{ $order->formatted_total }}</td>
                    <td class="px-4 py-3"><span class="badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                    <td class="px-4 py-3">
                        @if($order->payment)
                            <span class="{{ $order->payment->status === 'confirmed' ? 'text-green-600' : ($order->payment->status === 'rejected' ? 'text-red-500' : 'text-amber-600') }} text-xs font-medium">
                                {{ ucfirst($order->payment->status) }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.orders.show', $order->order_number) }}" class="text-mandar-600 hover:underline text-xs">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">Belum ada pesanan</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-50">
        {{ $orders->links() }}
    </div>
</div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('payment')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(string $orderNumber)
    {
        $order = Order::with(['items.product', 'payment', 'shippingStatuses.updatedBy'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function destroy(string $orderNumber)
    {
        $order = Order::with('payment')
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->firstOrFail();

        $proofImage = $order->payment?->proof_image;

        DB::transaction(function () use ($order) {
            $order->delete();
        });

        if ($proofImage && ! Storage::disk('public')->delete($proofImage)) {
            Log::warning('Could not delete payment proof for deleted order.', [
                'order_number' => $orderNumber,
                'proof_image' => $proofImage,
            ]);

            return redirect()->route('orders.index')
                ->with('warning', 'Riwayat pesanan dihapus, tetapi file bukti pembayaran gagal dihapus.');
        }

        return redirect()->route('orders.index')->with('success', 'Riwayat pesanan berhasil dihapus.');
    }
}

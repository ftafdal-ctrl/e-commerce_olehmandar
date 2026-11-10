<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment) {
            return back()->with('error', 'Bukti pembayaran QRIS sudah dikirim sebelumnya.');
        }

        if (! file_exists(public_path('images/qris.png'))) {
            return back()->with('error', 'QRIS toko belum tersedia. Pembayaran QRIS belum dapat dilakukan.');
        }

        $request->validate([
            'proof_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $request->file('proof_image')->store('payments/proofs', 'public');

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'qris',
            // These legacy columns remain required for existing bank-payment records.
            'bank_name' => 'QRIS',
            'account_number' => 'QRIS',
            'account_name' => 'QRIS',
            'amount' => $order->total_amount,
            'proof_image' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Bukti pembayaran QRIS berhasil dikirim. Admin akan memverifikasi dalam 1×24 jam.');
    }
}

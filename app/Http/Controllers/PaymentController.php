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
            return back()->with('error', 'Bukti pembayaran sudah dikirim sebelumnya.');
        }

        $request->validate([
            'bank_name'      => 'required|string|max:50',
            'account_number' => 'required|string|max:30',
            'account_name'   => 'required|string|max:100',
            'proof_image'    => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $request->file('proof_image')->store('payments/proofs', 'public');

        Payment::create([
            'order_id'       => $order->id,
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'amount'         => $order->total_amount,
            'proof_image'    => $path,
            'status'         => 'pending',
        ]);

        return back()->with('success', 'Bukti transfer berhasil dikirim. Admin akan memverifikasi dalam 1×24 jam.');
    }
}

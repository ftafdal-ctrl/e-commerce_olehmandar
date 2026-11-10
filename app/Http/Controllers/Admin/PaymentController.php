<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function confirm(Payment $payment)
    {
        $payment->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $payment->order->update(['status' => 'processing']);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi. Pesanan masuk ke proses.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['rejection_reason' => 'required|string|max:300']);

        $payment->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Pembayaran ditolak. Pembeli akan diberitahu.');
    }
}

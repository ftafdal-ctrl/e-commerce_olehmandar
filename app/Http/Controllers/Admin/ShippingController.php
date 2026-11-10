<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShippingStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ShippingController extends Controller
{
    public function store(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $validated = $request->validate([
            'status'          => ['required', Rule::in(array_keys(ShippingStatus::STATUSES))],
            'description'     => 'nullable|string|max:500',
            'tracking_number' => 'nullable|string|max:100',
            'courier'         => 'nullable|string|max:50',
        ]);

        ShippingStatus::create([
            'order_id'        => $order->id,
            'status'          => $validated['status'],
            'description'     => $validated['description'] ?? null,
            'tracking_number' => $validated['tracking_number'] ?? null,
            'courier'         => $validated['courier'] ?? null,
            'updated_by'      => Auth::id(),
        ]);

        $order->update([
            'status' => $validated['status'] === 'delivered' ? 'delivered' : 'shipped',
        ]);

        return back()->with('success', 'Status pengiriman berhasil ditambahkan.');
    }
}

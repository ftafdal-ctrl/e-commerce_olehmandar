<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index()
    {
        $cart = $this->cartService->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda masih kosong.');
        }

        $total = $cart->items->sum(fn($item) => $item->subtotal);

        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_name'        => 'required|string|max:100',
            'shipping_phone'       => 'required|string|max:20',
            'shipping_address'     => 'required|string|max:500',
            'shipping_city'        => 'required|string|max:100',
            'shipping_province'    => 'required|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:10',
            'notes'                => 'nullable|string|max:500',
        ]);

        $cart = $this->cartService->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda masih kosong.');
        }

        $total = $cart->items->sum(fn($item) => $item->subtotal);

        DB::transaction(function () use ($validated, $cart, $total) {
            // Buat order
            $order = Order::create([
                'user_id'              => Auth::id(),
                'order_number'         => $this->generateOrderNumber(),
                'status'               => 'pending',
                'total_amount'         => $total,
                'shipping_name'        => $validated['shipping_name'],
                'shipping_phone'       => $validated['shipping_phone'],
                'shipping_address'     => $validated['shipping_address'],
                'shipping_city'        => $validated['shipping_city'],
                'shipping_province'    => $validated['shipping_province'],
                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                'notes'                => $validated['notes'] ?? null,
            ]);

            // Buat order items
            foreach ($cart->items as $item) {
                $weightOption = $item->weight_option ?? '500g';
                $price        = $item->price_at_add ?? $item->product->getPriceForWeight($weightOption);

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'product_name'  => $item->product->name . ' (' . ($weightOption === '1kg' ? '1 Kg' : '500 Gram') . ')',
                    'product_price' => $price,
                    'quantity'      => $item->quantity,
                    'weight_option' => $weightOption,
                    'subtotal'      => $price * $item->quantity,
                ]);

                // Kurangi stok
                $item->product->decrement('stock', $item->quantity);
            }

            // Kosongkan cart
            $this->cartService->clearCart();

            session(['last_order_number' => $order->order_number]);
        });

        return redirect()->route('checkout.success', session('last_order_number'));
    }

    public function success(string $orderNumber)
    {
        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    protected function generateOrderNumber(): string
    {
        $date   = now()->format('Ymd');
        $prefix = 'MDR-' . $date . '-';
        $last   = Order::where('order_number', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}

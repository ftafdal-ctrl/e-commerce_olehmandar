<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index()
    {
        $cart = $this->cartService->getCart();
        $total = 0;

        if ($cart) {
            $total = $cart->items->sum(fn($item) => $item->subtotal);
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'weight_option' => 'required|in:500g,1kg',
        ]);

        $this->cartService->addItem(
            $request->product_id,
            $request->quantity,
            $request->weight_option
        );

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, int $id)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);

        $this->cartService->updateItem($id, $request->quantity);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->cartService->removeItem($id);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}

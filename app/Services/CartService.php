<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartService
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getOrCreateCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = $this->request->session()->get('cart_session_id');
        if (!$sessionId) {
            $sessionId = \Illuminate\Support\Str::uuid();
            $this->request->session()->put('cart_session_id', $sessionId);
        }

        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * Tambah produk ke keranjang dengan opsi berat.
     */
    public function addItem(int $productId, int $quantity = 1, string $weightOption = '500g'): void
    {
        $product      = Product::findOrFail($productId);
        $cart         = $this->getOrCreateCart();
        $priceAtAdd   = $product->getPriceForWeight($weightOption);

        // Cari item dengan produk & berat yang SAMA
        $item = $cart->items()
            ->where('product_id', $productId)
            ->where('weight_option', $weightOption)
            ->first();

        if ($item) {
            $newQty = $item->quantity + $quantity;
            $item->update(['quantity' => min($newQty, $product->stock)]);
        } else {
            $cart->items()->create([
                'product_id'    => $productId,
                'quantity'      => min($quantity, $product->stock),
                'weight_option' => $weightOption,
                'price_at_add'  => $priceAtAdd,
            ]);
        }

        $this->updateCartCount($cart);
    }

    public function updateItem(int $cartItemId, int $quantity): void
    {
        $item = CartItem::findOrFail($cartItemId);
        $this->authorizeItem($item);

        if ($quantity < 1) {
            $item->delete();
        } else {
            $item->update(['quantity' => min($quantity, $item->product->stock)]);
        }

        $this->updateCartCount($item->cart);
    }

    public function removeItem(int $cartItemId): void
    {
        $item = CartItem::findOrFail($cartItemId);
        $this->authorizeItem($item);
        $cart = $item->cart;
        $item->delete();
        $this->updateCartCount($cart);
    }

    public function getCart(): ?Cart
    {
        if (Auth::check()) {
            return Cart::with('items.product.category')
                ->where('user_id', Auth::id())
                ->first();
        }

        $sessionId = $this->request->session()->get('cart_session_id');
        if (!$sessionId) return null;

        return Cart::with('items.product.category')
            ->where('session_id', $sessionId)
            ->first();
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();
        if ($cart) {
            $cart->items()->delete();
            $this->request->session()->put('cart_count', 0);
        }
    }

    protected function updateCartCount(Cart $cart): void
    {
        $count = $cart->items()->sum('quantity');
        $this->request->session()->put('cart_count', $count);
    }

    protected function authorizeItem(CartItem $item): void
    {
        $cart = $this->getOrCreateCart();
        if ($item->cart_id !== $cart->id) {
            abort(403);
        }
    }
}

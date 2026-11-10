<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'weight_option', 'price_at_add'];

    protected $casts = [
        'price_at_add' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute(): float
    {
        $price = $this->price_at_add ?? $this->product->getPriceForWeight($this->weight_option ?? '500g');
        return $price * $this->quantity;
    }

    public function getWeightLabelAttribute(): string
    {
        return $this->weight_option === '1kg' ? '1 Kg' : '500 Gram';
    }
}

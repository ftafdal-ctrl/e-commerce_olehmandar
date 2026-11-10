<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'price_500g',
        'price_1kg',
        'stock',
        'weight',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'price_500g' => 'decimal:2',
        'price_1kg'  => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getPriceForWeight(string $weightOption): float
    {
        return match($weightOption) {
            '1kg'  => (float) ($this->price_1kg ?? $this->price * 2),
            default => (float) ($this->price_500g ?? $this->price),
        };
    }

    public function getFormattedPriceForWeight(string $weightOption): string
    {
        return 'Rp ' . number_format($this->getPriceForWeight($weightOption), 0, ',', '.');
    }

    public function hasWeightOptions(): bool
    {
        return $this->price_500g !== null || $this->price_1kg !== null;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/product-placeholder.png');
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}

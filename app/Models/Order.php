<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public static $statusLabels = [
        'pending'    => 'Menunggu Pembayaran',
        'confirmed'  => 'Pembayaran Dikonfirmasi',
        'processing' => 'Sedang Diproses',
        'shipped'    => 'Dalam Pengiriman',
        'delivered'  => 'Terkirim',
        'cancelled'  => 'Dibatalkan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shippingStatuses()
    {
        return $this->hasMany(ShippingStatus::class)->orderBy('created_at', 'desc');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }
}

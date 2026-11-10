<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingStatus extends Model
{
    public const STATUSES = [
        'handed_to_courier' => 'Pesanan sudah diserahkan ke kurir',
        'in_transit' => 'Pesanan dalam pengiriman',
        'out_for_delivery' => 'Pesanan sedang dalam proses pengantaran',
        'delivered' => 'Pesanan sudah diterima',
    ];

    protected $fillable = [
        'order_id',
        'status',
        'description',
        'tracking_number',
        'courier',
        'updated_by',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}

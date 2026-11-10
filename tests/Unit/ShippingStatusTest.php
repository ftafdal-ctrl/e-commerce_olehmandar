<?php

namespace Tests\Unit;

use App\Models\ShippingStatus;
use PHPUnit\Framework\TestCase;

class ShippingStatusTest extends TestCase
{
    public function test_shipping_statuses_have_customer_facing_labels(): void
    {
        $this->assertSame([
            'handed_to_courier' => 'Pesanan sudah diserahkan ke kurir',
            'in_transit' => 'Pesanan dalam pengiriman',
            'out_for_delivery' => 'Pesanan sedang dalam proses pengantaran',
            'delivered' => 'Pesanan sudah diterima',
        ], ShippingStatus::STATUSES);

        foreach (ShippingStatus::STATUSES as $value => $label) {
            $status = new ShippingStatus(['status' => $value]);
            $this->assertSame($label, $status->status_label);
        }
    }

    public function test_existing_free_text_shipping_statuses_remain_visible(): void
    {
        $status = new ShippingStatus(['status' => 'Paket tiba di gudang']);

        $this->assertSame('Paket tiba di gudang', $status->status_label);
    }
}

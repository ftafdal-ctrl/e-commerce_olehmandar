<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\ShippingStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteCompletedOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_delete_their_delivered_order_and_related_records(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $order = $this->createOrder($user, 'delivered');
        $proofImage = UploadedFile::fake()->image('proof.png');
        $proofPath = $proofImage->store('payments/proofs', 'public');

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'qris',
            'bank_name' => 'QRIS',
            'account_number' => 'QRIS',
            'account_name' => 'QRIS',
            'amount' => $order->total_amount,
            'proof_image' => $proofPath,
            'status' => 'confirmed',
        ]);
        $shippingStatus = ShippingStatus::create([
            'order_id' => $order->id,
            'status' => 'delivered',
        ]);

        $response = $this->actingAs($user)->delete(route('orders.destroy', $order->order_number));

        $response->assertRedirect(route('orders.index'))
            ->assertSessionHas('success', 'Riwayat pesanan berhasil dihapus.');
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
        $this->assertDatabaseMissing('shipping_statuses', ['id' => $shippingStatus->id]);
        Storage::disk('public')->assertMissing($proofPath);
    }

    public function test_customer_cannot_delete_an_order_that_is_not_delivered(): void
    {
        $user = User::factory()->create();
        $order = $this->createOrder($user, 'processing');

        $this->actingAs($user)
            ->delete(route('orders.destroy', $order->order_number))
            ->assertNotFound();

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    public function test_customer_cannot_delete_another_users_order(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $order = $this->createOrder($owner, 'delivered');

        $this->actingAs($otherUser)
            ->delete(route('orders.destroy', $order->order_number))
            ->assertNotFound();

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    private function createOrder(User $user, string $status): Order
    {
        return Order::create([
            'user_id' => $user->id,
            'order_number' => 'MDR-'.uniqid(),
            'status' => $status,
            'total_amount' => 10000,
            'shipping_name' => 'Pembeli',
            'shipping_phone' => '08123456789',
            'shipping_address' => 'Alamat',
            'shipping_city' => 'Majene',
            'shipping_province' => 'Sulawesi Barat',
        ]);
    }
}

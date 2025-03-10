<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testPaymentMethodIsImmediatelyUpdated()
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => Item::STATUS_AVAILABLE,
        ]);

        $this->post(route('purchase.update-payment', ['id' => $item->id]), [
            'payment_method' => 'konbini',
        ]);

        $response = $this->get(route('purchase.show', ['id' => $item->id]));

        $response->assertSeeInOrder(['<td class="info-group__text">', 'コンビニ支払い', '</td>'], false);

        $this->post(route('purchase.update-payment', ['id' => $item->id]), [
            'payment_method' => 'card',
        ]);

        $response = $this->get(route('purchase.show', ['id' => $item->id]));

        $response->assertSeeInOrder(['<td class="info-group__text">', 'カード支払い', '</td>'], false);
    }
}

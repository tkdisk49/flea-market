<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testUpdatedAddressIsReflectedOnPurchasePage()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $existingAddress = Address::factory()->create([
            'user_id' => $user->id,
            'name' => 'old address',
            'post_code' => '100-0001',
            'address' => '東京都千代田区旧町',
            'building' => '旧ビル101号室',
        ]);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => Item::STATUS_AVAILABLE,
        ]);

        $response = $this->get(route('purchase.change-address', ['id' => $item->id]));
        $response->assertStatus(200);

        $this->patch(route('purchase.address.update'), [
            'name' => 'new address',
            'post_code' => '200-0002',
            'address' => '東京都新宿区新町',
            'building' => '新ビル202号室',
        ]);

        $response = $this->get(route('purchase.show', ['id' => $item->id]));

        $response->assertSee('new address');
        $response->assertSee('200-0002');
        $response->assertSee('東京都新宿区新町');
        $response->assertSee('新ビル202号室');
    }

    public function testPurchasedItemIsLinkedWithShippingAddress()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'name' => 'test',
            'post_code' => '123-4567',
            'address' => '東京都新宿区テスト町1-1-1',
            'building' => 'テストビル101号室',
        ]);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => Item::STATUS_AVAILABLE,
        ]);

        $response = $this->post(route('purchase.store', [
            'id' => $item->id,
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'address_id' => $address->id,
        ]));

        $response->assertRedirect(route('purchase.complete'));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
            'payment_method' => 'card',
        ]);
    }
}

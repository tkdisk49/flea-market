<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testUserCanPurchaseItem()
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

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

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => Item::STATUS_SOLD,
        ]);
    }

    public function testPurchasedItemDisplaysSoldLabel()
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => Item::STATUS_AVAILABLE,
        ]);

        $this->post(route('purchase.store', [
            'id' => $item->id,
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'address_id' => $address->id,
        ]));

        $response = $this->get(route('home'));

        $response->assertSee('<p class="item__sold">Sold</p>', false);
    }

    public function testPurchasedItemIsAddedToProfilePurchaseList()
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => Item::STATUS_AVAILABLE,
        ]);

        $this->post(route('purchase.store', [
            'id' => $item->id,
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'address_id' => $address->id,
        ]));

        $response = $this->get(route('mypage', ['page' => 'buy']));

        $response->assertSee($item->name);
    }
}

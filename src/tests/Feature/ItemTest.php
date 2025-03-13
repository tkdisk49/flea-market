<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testCanFetchItemList()
    {
        $user = User::factory()->create();

        $items = Item::factory()->count(10)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);

        $response->assertViewIs('items.index');

        $response->assertViewHas('items');

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function testSoldItemsDisplaySoldLabel()
    {
        $user = User::factory()->create();

        Item::factory()->create([
            'user_id' => $user->id,
            'status' => Item::STATUS_SOLD,
        ]);

        $response = $this->get(route('home'));

        $response->assertSee('sold');
    }

    public function testUserCannotSeeOwnItems()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $items = Item::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $otherUser = User::factory()->create();
        $otherItems = Item::factory()->count(3)->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->get(route('home'));

        foreach ($items as $item) {
            $response->assertDontSee("<p class=\"item__name\">{$item->name}</p>", false);
        }

        foreach ($otherItems as $item) {
            $response->assertSee($item->name, false);
        }

        $response->assertStatus(200);
    }
}

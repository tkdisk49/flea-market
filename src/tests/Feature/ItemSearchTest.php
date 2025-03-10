<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testSearchDisplaysMatchingItems()
    {
        $user = User::factory()->create();

        $matchingItems = Item::factory()->count(3)->create([
            'name' => 'MacBook Pro',
            'user_id' => $user->id,
        ]);

        $nonMatchingItems = Item::factory()->count(2)->create([
            'name' => 'Windows PC',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('home', ['query' => 'Mac']));

        foreach ($matchingItems as $item) {
            $response->assertSee($item->name, false);
        }

        foreach ($nonMatchingItems as $item) {
            $response->assertDontSee("<p class=\"item__name\">{$item->name}</p>", false);
        }

        $response->assertStatus(200);
    }

    public function testSearchQueryIsRetainedInMyList()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $matchingItems = Item::factory()->count(3)->create([
            'name' => 'MacBook Pro',
            'user_id' => $otherUser->id,
        ]);

        $homeResponse = $this->get(route('home', ['query' => 'Mac']));
        $homeResponse->assertSee('MacBook Pro');

        $mylistResponse = $this->get(route('home', ['page' => 'mylist', 'query' => 'Mac']));

        $mylistResponse->assertSee('<input type="text" name="query" value="Mac"', false);
    }
}

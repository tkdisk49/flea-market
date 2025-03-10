<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testLikedItemsAreDisplayedInMyList()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $items = Item::factory()->count(10)->create([
            'user_id' => $otherUser->id,
        ]);

        $likeItems = $items->slice(0, 3);
        foreach ($likeItems as $item) {
            Like::factory()->create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        $response = $this->get(route('home', ['page' => 'mylist']));

        foreach ($likeItems as $item) {
            $response->assertSee($item->name, false);
        }

        $response->assertStatus(200);
    }

    public function testSoldLikedItemsDisplaySoldLabelInMyList()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $otherUser->id,
            'status' => Item::STATUS_SOLD,
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('home', ['page' => 'mylist']));

        $response->assertSee('sold');
    }

    public function testUserCannotSeeOwnItemsInMyList()
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

        $response = $this->get(route('home', ['page' => 'mylist']));

        foreach ($items as $item) {
            $response->assertDontSee("<p class=\"item__name\">{$item->name}</p>", false);
        }

        foreach ($otherItems as $item) {
            $response->assertSee($item->name, false);
        }

        $response->assertStatus(200);
    }

    public function testGuestIsRedirectedToLoginWhenAccessingMyList()
    {
        $response = $this->get(route('home', ['page' => 'mylist']));

        $response->assertRedirect(route('login'));
    }
}

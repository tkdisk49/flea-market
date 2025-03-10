<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testUserCanLikeItem()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $likeCount = Like::where('item_id', $item->id)->count();
        $this->assertEquals(0, $likeCount);

        $response = $this->post(route('like.toggle', ['id' => $item->id]));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $updatedLikeCount = Like::where('item_id', $item->id)->count();
        $this->assertEquals(1, $updatedLikeCount);

        $response = $this->get(route('detail', ['id' => $item->id]));
        $response->assertSee('<p class="like__count">1</p>', false);

        $response->assertStatus(200);
    }

    public function testLikeIconChangesColorOnLike()
    {
        $seller = User::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $response = $this->get(route('detail', ['id' => $item->id]));
        $response->assertDontSee('<img class="like__icon liked"', false);

        $this->post(route('like.toggle', ['id' => $item->id]));

        $response = $this->get(route('detail', ['id' => $item->id]));
        $response->assertSee('<img class="like__icon liked"', false);
    }

    public function testLikeIconTogglesAndCountUpdates()
    {
        $seller = User::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $response = $this->get(route('detail', ['id' => $item->id]));
        $response->assertDontSee('<img class="like__icon liked"', false);
        $response->assertSee('<p class="like__count">0</p>', false);

        $this->post(route('like.toggle', ['id' => $item->id]));

        $response = $this->get(route('detail', ['id' => $item->id]));
        $response->assertSee('<img class="like__icon liked"', false);
        $response->assertSee('<p class="like__count">1</p>', false);

        $this->post(route('like.toggle', ['id' => $item->id]));

        $response = $this->get(route('detail', ['id' => $item->id]));
        $response->assertDontSee('<img class="like__icon liked"', false);
        $response->assertSee('<p class="like__count">0</p>', false);
    }
}

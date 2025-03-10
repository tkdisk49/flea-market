<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testLoggedInUserCanPostComment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $commentData = [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'この商品はまだありますか？',
        ];

        $response = $this->post(route('comment.store', ['id' => $item->id]), $commentData);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'この商品はまだありますか？',
        ]);

        $response = $this->get(route('detail', ['id' => $item->id]));

        $response->assertSee($user->name, false);
        $response->assertSee('この商品はまだありますか？', false);
        $response->assertSee('<p class="comment__count">1</p>', false);
    }

    public function testGuestCannotPostComment()
    {
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $commentData = [
            'item_id' => $item->id,
            'content' => 'この商品はまだありますか？',
        ];

        $response = $this->post(route('comment.store', ['id' => $item->id]), $commentData);

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => 'この商品はまだありますか？',
        ]);
    }

    public function testCannotPostEmptyComment()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $commentData = [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => '',
        ];

        $response = $this->post(route('comment.store', ['id' => $item->id]), $commentData);

        $response->assertSessionHasErrors(['content' => 'コメントを入力してください']);

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => '',
        ]);
    }

    public function testCannotPostCommentExceeding255Characters()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $longText = str_repeat('あ', 256);
        $commentData = [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => $longText,
        ];

        $response = $this->post(route('comment.store', ['id' => $item->id]), $commentData);

        $response->assertSessionHasErrors(['content' => '255文字以内で入力してください']);

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => $longText,
        ]);
    }
}

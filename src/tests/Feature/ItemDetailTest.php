<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testItemDetailPageDisplaysCorrectInformation()
    {
        $seller = User::factory()->create();
        $category = Category::factory()->create(['content' => 'テストカテゴリー']);

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'MacBook Pro',
            'brand' => 'Apple',
            'price' => 250000,
            'image' => 'images/macbook.jpg',
            'description' => 'Appleの最新MacBook Proです。',
            'condition' => Item::CONDITION_GOOD,
        ]);

        $item->categories()->attach($category->id);

        Like::factory()->count(2)->create([
            'item_id' => $item->id,
        ]);

        $commentUser = User::factory()->create(['name' => 'コメントユーザー']);
        $comment = Comment::factory()->create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'content' => 'この商品はまだありますか？',
        ]);

        $response = $this->get(route('detail', ['id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->description);
        $response->assertSee($category->content);
        $response->assertSeeInOrder(['<td class="info__condition-content">', '良好', '</td>'], false);

        $response->assertSee('<p class="like__count">2</p>', false);
        $response->assertSee('<p class="comment__count">1</p>', false);

        $response->assertSee($commentUser->name);
        $response->assertSee($comment->content);

        $response->assertSee('<img src="' . asset('storage/' . $item->image) . '"', false);
    }

    public function testItemDetailPageDisplaysAllCategories()
    {
        $seller = User::factory()->create();
        $categories = Category::factory()->count(3)->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'MacBook Pro',
            'brand' => 'Apple',
            'price' => 250000,
            'description' => 'Appleの最新MacBook Proです。',
            'condition' => Item::CONDITION_GOOD,
        ]);

        $item->categories()->attach($categories->pluck('id')->toArray());

        $response = $this->get(route('detail', ['id' => $item->id]));

        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->content, false);
        }
    }
}

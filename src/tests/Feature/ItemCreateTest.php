<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testUserCanCreateItem()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'Apple',
            'description' => 'これはテスト商品の説明です。',
            'price' => 10000,
            'condition' => Item::CONDITION_GOOD,
            'image' => 'images/macbook.jpg',
        ]);

        $item->categories()->attach($category->id);

        $response = $this->post(route('items.store'));

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'Apple',
            'description' => 'これはテスト商品の説明です。',
            'price' => 10000,
            'condition' => Item::CONDITION_GOOD,
            'image' => 'images/macbook.jpg',
        ]);

        $this->assertDatabaseHas('category_item', [
            'category_id' => $category->id,
            'item_id' => $item->id,
        ]);
    }
}

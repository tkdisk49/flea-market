<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testProfilePageDisplaysCorrectInformation()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'images/profile.jpg',
        ]);
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'name' => 'test user',
        ]);
        $this->actingAs($user);

        $listedItems = Item::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $seller = User::factory()->create();
        $purchasedItems = Item::factory()->count(3)->create([
            'user_id' => $seller->id,
            'status' => Item::STATUS_AVAILABLE,
        ]);

        foreach ($purchasedItems as $item) {
            Purchase::factory()->create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'address_id' => $address->id,
            ]);
        }

        $response = $this->get(route('mypage', ['page' => 'sell']));
        $response->assertStatus(200);

        $response->assertSee('<img src="' . asset('storage/' . $profile->image) . '"', false);
        $response->assertSee('test user');

        foreach ($listedItems as $item) {
            $response->assertSee($item->name);
        }

        $buyResponse = $this->get(route('mypage', ['page' => 'buy']));

        foreach ($purchasedItems as $item) {
            $buyResponse->assertSee($item->name);
        }
    }

    public function testProfileEditPageDisplaysPreviousValues()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'images/profile.jpg',
        ]);
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'name' => 'テストユーザー',
            'post_code' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building' => 'テストビル101号室',
        ]);
        $this->actingAs($user);

        $response = $this->get(route('profile.edit'));

        $response->assertStatus(200);

        $response->assertSee('<img src="' . asset('storage/' . $profile->image) . '"', false);
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区1-1-1');
        $response->assertSee('テストビル101号室');
    }
}

<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'address_id' => Address::factory(),
            'payment_method' => 'card',
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => null,
            'name' => $this->faker->unique()->word(),
            'price' => $this->faker->randomNumber(4),
            'image' => $this->faker->imageUrl(640, 480),
            'description' => $this->faker->sentence(),
            'condition' => $this->faker->numberBetween(1, 4),
            'brand' => $this->faker->word(),
            'status' => 1,
        ];
    }
}

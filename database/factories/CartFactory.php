<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'food_id' => Food::inRandomOrder()->first()->id ?? Food::factory(),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}

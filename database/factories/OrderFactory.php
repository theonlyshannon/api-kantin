<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'food_id' => Food::inRandomOrder()->first()->id ?? Food::factory(),
            'quantity' => $this->faker->numberBetween(1, 5),
            'total_price' => $this->faker->numberBetween(5000, 50000),
            'status' => $this->faker->randomElement(['pending', 'order', 'success']),
        ];
    }
}


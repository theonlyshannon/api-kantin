<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        $carts = Cart::where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return [
                'user_id' => $user->id, // Pastikan user_id diisi
                'total_price' => 0, // Set total_price ke 0 jika cart kosong
                'status' => 'order',
            ];
        }

        $totalPrice = $carts->sum(fn ($cart) => $cart->food->price * $cart->quantity);

        return [
            'user_id' => $user->id, // Pastikan user_id diisi
            'total_price' => $totalPrice,
            'status' => 'order',
        ];
    }
}

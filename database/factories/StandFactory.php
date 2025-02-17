<?php

namespace Database\Factories;

use App\Models\Stand;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Stand::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = $this->faker->company; // Nama stand diambil dari nama perusahaan
        return [
            'user_id' => User::factory(), // Menggunakan factory user
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'description' => $this->faker->sentence(10),
            'deleted_at' => null, // Soft delete (bisa dibuat nullable)
        ];
    }
}

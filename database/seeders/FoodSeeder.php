<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\FoodFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodFactory::times(10)->create();
    }
}

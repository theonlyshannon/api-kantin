<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\CartFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CartFactory::times(10)->create();
    }
}

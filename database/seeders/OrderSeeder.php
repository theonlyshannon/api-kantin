<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Database\Factories\OrderFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        OrderFactory::new()->count(10)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Stand;
use Database\Factories\StandFactory;
use Illuminate\Database\Seeder;

class StandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StandFactory::times(10)->create();
    }
}

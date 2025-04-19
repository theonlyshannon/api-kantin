<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Stand;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('Stand');

        Stand::create([
            'user_id' => $admin->id,
            'name' => 'Stand Admin',
            'slug' => 'stand-admin',
            'description' => 'Stand milik admin'
        ]);
    }
}

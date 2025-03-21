<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'list-foods']);
        Permission::firstOrCreate(['name' => 'create-foods']);
        Permission::firstOrCreate(['name' => 'edit-foods']);
        Permission::firstOrCreate(['name' => 'delete-foods']);

        Permission::firstOrCreate(['name' => 'list-stands']);
        Permission::firstOrCreate(['name' => 'create-stands']);
        Permission::firstOrCreate(['name' => 'edit-stands']);
        Permission::firstOrCreate(['name' => 'delete-stands']);

        Permission::firstOrCreate(['name' => 'list-users']);
        Permission::firstOrCreate(['name' => 'create-users']);
        Permission::firstOrCreate(['name' => 'edit-users']);
        Permission::firstOrCreate(['name' => 'delete-users']);

        $studentRole = Role::where('name', 'Student')->first();
        $studentRole->givePermissionTo([
            'list-foods',
            'list-stands'
        ]);

        $standRole = Role::where('name', 'Stand')->first();
        $standRole->givePermissionTo([
            'list-foods',
            'create-foods',
            'edit-foods',
            'delete-foods',
            'list-stands',
            'create-stands',
            'edit-stands',
            'delete-stands',
            'list-users',
            'create-users',
            'edit-users',
            'delete-users',
        ]);
    }
}

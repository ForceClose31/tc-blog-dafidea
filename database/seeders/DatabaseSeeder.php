<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        $permissions = [
            'dashboard',
            'create',
            'edit',
            'delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin3@gmail.com',
            'role' => 'Admin',
            'password' => bcrypt('123123'),
        ]);

        $admin->assignRole($adminRole);

        $admin->givePermissionTo([
            'dashboard',
            'create',
            'edit',
            'delete'
        ]);

        $adminRole->givePermissionTo([
            'dashboard',
            'create',
            'edit',
            'delete'
        ]);
    }
}

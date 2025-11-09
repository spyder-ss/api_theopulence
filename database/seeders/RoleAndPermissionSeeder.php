<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for Amenities module
        Permission::create(['name' => 'amenity-list']);
        Permission::create(['name' => 'amenity-create']);
        Permission::create(['name' => 'amenity-edit']);
        Permission::create(['name' => 'amenity-delete']);

        // Create roles
        $adminRole = Role::create(['name' => 'Admin']);
        $managerRole = Role::create(['name' => 'Manager']);
        $userRole = Role::create(['name' => 'User']); // role_id 3

        // Assign all permissions to Admin role
        $adminRole->givePermissionTo(Permission::all());

        // Assign specific permissions to Manager role (example)
        $managerRole->givePermissionTo(['amenity-list', 'amenity-create', 'amenity-edit']);

        // Create a default admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'status' => 1,
            'is_delete' => 0,
        ]);
        $adminUser->assignRole($adminRole);

        // Create a default regular user (role_id 3)
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
            'status' => 1,
            'is_delete' => 0,
        ]);
        $regularUser->assignRole($userRole);
    }
}

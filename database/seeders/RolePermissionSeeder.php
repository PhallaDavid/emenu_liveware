<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'group' => 'System'],
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'group' => 'User Management'],
            ['name' => 'View Users', 'slug' => 'view-users', 'group' => 'User Management'],
            ['name' => 'Manage Categories', 'slug' => 'manage-categories', 'group' => 'Menu Management'],
            ['name' => 'Manage Products', 'slug' => 'manage-products', 'group' => 'Menu Management'],
            ['name' => 'Manage Tables', 'slug' => 'manage-tables', 'group' => 'Store Management'],
            ['name' => 'View Orders', 'slug' => 'view-orders', 'group' => 'Order Management'],
            ['name' => 'Manage Orders', 'slug' => 'manage-orders', 'group' => 'Order Management'],
        ];

        foreach ($permissions as $p) {
            \App\Models\Permission::create($p);
        }

        $adminRole = \App\Models\Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $managerRole = \App\Models\Role::create(['name' => 'Manager', 'slug' => 'manager']);
        $waiterRole = \App\Models\Role::create(['name' => 'Waiter', 'slug' => 'waiter']);

        // Assign some permissions to admin
        $adminRole->permissions()->attach(\App\Models\Permission::all());
    }
}

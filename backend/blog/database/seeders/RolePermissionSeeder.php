<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Ensure permissions exist (idempotent seeder)
        $permissions = [
            // Articles
            'view articles',
            'create articles',
            'edit articles',
            'delete articles',
            'publish articles',

            // Inventory / Products
            'view products',
            'create products',
            'edit products',
            'delete products',

            // Inventory / Categories
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // Inventory / Suppliers
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',

            // Inventory / Warehouses
            'view warehouses',
            'create warehouses',
            'edit warehouses',
            'delete warehouses',

            // Inventory / Stock Movements
            'view stock',
            'manage stock',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        // Admin: full access
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // Editor: can view/create/edit for articles and inventory masters
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editorRole->syncPermissions([

            // Articles
            'view articles',
            'create articles',
            'edit articles',
            'publish articles',

            // Products
            'view products',
            'create products',
            'edit products',

            // Categories
            'view categories',
            'create categories',
            'edit categories',

            // Suppliers
            'view suppliers',
            'create suppliers',
            'edit suppliers',

            // Warehouses
            'view warehouses',
            'create warehouses',
            'edit warehouses',

            // Stock
            'view stock',
            'manage stock',

        ]);


        // Viewer: can only view articles and inventory masters
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);
        $viewerRole->syncPermissions(['view articles', 'view products', 'view categories', 'view suppliers', 'view warehouses']);




        // Create test users (idempotent)
        $adminUser = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);
        $adminUser->assignRole('admin');

        $editorUser = User::firstOrCreate([
            'email' => 'editor@example.com',
        ], [
            'name' => 'Editor User',
            'password' => bcrypt('password'),
        ]);
        $editorUser->assignRole('editor');

        $viewerUser = User::firstOrCreate([
            'email' => 'viewer@example.com',
        ], [
            'name' => 'Viewer User',
            'password' => bcrypt('password'),
        ]);
        $viewerUser->assignRole('viewer');
    }
}

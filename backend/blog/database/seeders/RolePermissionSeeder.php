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

        // Create permissions
        $permissions = [
            'view articles',
            'create articles',
            'edit articles',
            'delete articles',
            'publish articles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Admin: full access
        Role::create(['name' => 'admin'])
            ->givePermissionTo(Permission::all());

        // Editor: can view, create, edit, and publish
        Role::create(['name' => 'editor'])
            ->givePermissionTo([
                'view articles',
                'create articles',
                'edit articles',
                'publish articles',
            ]);

        // Viewer: can only view
        Role::create(['name' => 'viewer'])
            ->givePermissionTo(['view articles']);

        // Create test users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('editor');

        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('viewer');
    }
}

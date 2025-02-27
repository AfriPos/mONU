<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Define permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view mac prefixes',
            'create mac prefixes',
            'deactivate mac prefixes',
            'activate mac prefixes',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'view router models',
            'view routers',
            'view router configurations',
            'view router issues',
            'fix router issues',
            'view finance',
        ];

        // Create and assign permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles and assign existing permissions
        $roles = [
            'Developer' => $permissions,
            'Super Administrator' => $permissions,
            'Administrator' => [
                'view users',
                'view router models',
                'view mac prefixes',
                'view router configurations',
                'view router issues',
                'fix router issues',
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}

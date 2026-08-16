<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'buy',
            'cart',
            'own_orders',
            'return',
            'manage_products',
            'manage_categories',
            'manage_brands',
            'manage_orders',
            'manage_returns',
            'manage_admins',
            'manage_roles',
            'full_admin_access'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // create roles and assign created permissions

        // Customer Role
        $roleCustomer = Role::findOrCreate('Customer');
        $roleCustomer->givePermissionTo(['buy', 'cart', 'own_orders', 'return']);

        // Staff Role
        $roleStaff = Role::findOrCreate('Staff');
        $roleStaff->givePermissionTo([
            'manage_products',
            'manage_categories',
            'manage_brands',
            'manage_orders',
            'manage_returns'
        ]);

        // Admin Role
        $roleAdmin = Role::findOrCreate('Admin');
        $roleAdmin->givePermissionTo([
            'manage_products',
            'manage_categories',
            'manage_brands',
            'manage_orders',
            'manage_returns',
            'full_admin_access'
        ]);
        
        // Super Admin Role
        $roleSuperAdmin = Role::findOrCreate('Super Admin');
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // Migrate existing users based on their 'role' column if they exist
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role === 'admin') {
                $user->assignRole('Super Admin');
            } elseif ($user->role === 'staff') {
                $user->assignRole('Staff');
            } elseif ($user->role === 'customer') {
                $user->assignRole('Customer');
            } else {
                $user->assignRole('Customer'); // default
            }
        }
    }
}

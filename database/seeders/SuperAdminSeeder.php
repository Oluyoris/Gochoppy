<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update the super admin user
        $super = AdminUser::updateOrCreate(
            ['email' => 'super@gochoppy.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('theonlysuperadminofgochoppy1'), // Change this in production!
                'is_super_admin' => true,
            ]
        );

        // Create the super-admin role if not exists
        $role = Role::firstOrCreate(
            ['name' => 'super-admin'],
            ['guard_name' => 'web']
        );

        // Create default permissions
        $permissions = [
            'manage-vendors',
            'manage-dispatchers',
            'manage-users',
            'view-transactions',
            'manage-settings',
            'approve-menu-requests',
            'manage-subscriptions',
            'manage-bonuses',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm],
                ['guard_name' => 'web']
            );
        }

        // Give super-admin role all permissions
        $role->syncPermissions(Permission::all());

        // Assign the role to super admin
        $super->assignRole('super-admin');

        $this->command->info('Super admin seeded successfully!');
        $this->command->info('Email: super@gochoppy.com');
        $this->command->info('Password: theonlysuperadminofgochoppy1');
        $this->command->info('Role: super-admin with all permissions');
    }
}
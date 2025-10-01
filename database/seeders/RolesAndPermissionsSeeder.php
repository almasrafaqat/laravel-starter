<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions for your modules
        $permissions = [
            'user_create',
            'user_edit',
            'user_delete',
            'user_view',
            'invoice_create',
            'invoice_edit',
            'invoice_delete',
            'invoice_view',
            'invoice_send',
            'invoice_export',
            'client_create',
            'client_edit',
            'client_delete',
            'client_view',
            'product_create',
            'product_edit',
            'product_delete',
            'product_view',
            'payment_create',
            'payment_edit',
            'payment_delete',
            'payment_view',
            'payment_refund',
            'report_view',
            'report_export',
            'team_create',
            'team_edit',
            'team_delete',
            'team_view',
            'subscription_manage',
            'notification_view',
            'notification_manage',
            'settings_view',
            'settings_edit',
        ];

        // Create permissions for both guards
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        // Define roles and their permissions
        $roles = [
            'admin' => $permissions,
            'exporter' => [
                'invoice_create',
                'invoice_edit',
                'invoice_delete',
                'invoice_view',
                'invoice_send',
                'invoice_export',
                'client_create',
                'client_edit',
                'client_delete',
                'client_view',
                'product_create',
                'product_edit',
                'product_delete',
                'product_view',
                'payment_create',
                'payment_edit',
                'payment_delete',
                'payment_view',
                'payment_refund',
                'report_view',
                'report_export',
                'notification_view',
                'notification_manage',
                'settings_view',
                'settings_edit',
            ],
            'manager' => [
                'invoice_create',
                'invoice_edit',
                'invoice_view',
                'invoice_send',
                'client_create',
                'client_edit',
                'client_view',
                'product_create',
                'product_edit',
                'product_view',
                'payment_create',
                'payment_edit',
                'payment_view',
                'report_view',
                'team_view',
            ],
            'viewer' => [
                'invoice_view',
                'client_view',
                'product_view',
                'payment_view',
                'report_view',
            ],
        ];

        $guards = ['web', 'api'];

        // Create roles for both guards and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            foreach ($guards as $guard) {
                $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => $guard]);
                $role->syncPermissions(
                    Permission::whereIn('name', $rolePermissions)
                        ->where('guard_name', $guard)
                        ->pluck('name')
                        ->toArray()
                );
            }
        }

        // Create admin user for web
        $adminEmail = config('app.admin_email', 'admin@exporter.com');
        $password = config('app.admin_password', 'admin123');
        if ($adminEmail) {
            $adminUser = User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Admin',
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]
            );
            $adminUser->assignRole('admin');
        }
    }
}

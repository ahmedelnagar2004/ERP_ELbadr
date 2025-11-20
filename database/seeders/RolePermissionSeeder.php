<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for ERP system
        $permissions = [
            // User Management
            'view-users', 'create-users', 'edit-users', 'delete-users', 'manage-users',

            // Client Management
            'view-clients', 'create-clients', 'edit-clients', 'delete-clients',

            // Category Management
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',

            // Pay Remaining Management
            'view-payremaining', 'create-payremaining', 'edit-payremaining', 'delete-payremaining',

            // Item Management
            'view-items', 'create-items', 'edit-items', 'delete-items',

            // Order Management
            'view-orders', 'create-orders', 'edit-orders', 'delete-orders', 'approve-orders',

            // Sales Management
            'view-sales', 'create-sales', 'edit-sales', 'delete-sales', 'approve-sales',

            // Unit Management
            'view-units', 'create-units', 'edit-units', 'delete-units',

            // Safe Management
            'view-safes', 'create-safes', 'edit-safes', 'delete-safes', 'manage-safes',

            // File Management
            'view-files', 'upload-files', 'edit-files', 'delete-files',

            // Reports
            'view-reports', 'export-reports',
            //
            'alert-quantity',
            // warehouse
            'view-warehouses', 'create-warehouses', 'edit-warehouses', 'delete-warehouses',

            // System Settings
            'manage-settings',
            'manage-roles',
            'manage-permissions',

            // Dashboard
            'view-dashboard',
            'view-safes', 'create-safes', 'edit-safes', 'delete-safes', 'manage-safes',

            // Dashboard
            'view-dashboard',
            // safe
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - has all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin - has most permissions except system management
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $adminPermissions = [
            'view-users', 'create-users', 'edit-users',
            'view-clients', 'create-clients', 'edit-clients', 'delete-clients',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-items', 'create-items', 'edit-items', 'delete-items',
            'view-orders', 'create-orders', 'edit-orders', 'delete-orders', 'approve-orders',
            'view-sales', 'create-sales', 'edit-sales', 'delete-sales', 'approve-sales',
            'view-files', 'upload-files', 'edit-files', 'delete-files',
            'view-reports', 'export-reports',
            'alert-quantity',
            'view-safes', 'create-safes', 'edit-safes', 'delete-safes', 'manage-safes',
            'view-dashboard',
        ];
        $admin->syncPermissions($adminPermissions);

        // Sales Manager - focused on sales and orders
        $salesManager = Role::firstOrCreate(['name' => 'sales-manager']);
        $salesManagerPermissions = [
            'view-clients', 'create-clients', 'edit-clients',
            'view-items',
            'view-orders', 'create-orders', 'edit-orders', 'approve-orders',
            'view-sales', 'create-sales', 'edit-sales', 'approve-sales',
            'view-reports',
            'view-dashboard',
        ];
        $salesManager->syncPermissions($salesManagerPermissions);

        // Sales Representative - basic sales operations
        $salesRep = Role::firstOrCreate(['name' => 'sales-representative']);
        $salesRepPermissions = [
            'view-clients', 'create-clients', 'edit-clients',
            'view-items',
            'view-orders', 'create-orders', 'edit-orders',
            'view-sales', 'create-sales', 'edit-sales',
            'view-dashboard',
        ];
        $salesRep->syncPermissions($salesRepPermissions);

        // Inventory Manager - focused on items and categories
        $inventoryManager = Role::firstOrCreate(['name' => 'inventory-manager']);
        $inventoryManagerPermissions = [
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-items', 'create-items', 'edit-items', 'delete-items',
            'view-orders',
            'view-files', 'upload-files', 'edit-files',
            'view-reports',
            'view-dashboard',
        ];
        $inventoryManager->syncPermissions($inventoryManagerPermissions);

        // Accountant - focused on financial data and reports
        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        $accountantPermissions = [
            'view-clients',
            'view-orders',
            'view-sales',
            'view-reports', 'export-reports',
            'alert-quantity',
            'view-safes', 'create-safes', 'edit-safes', 'delete-safes',
            'view-dashboard',
        ];
        $accountant->syncPermissions($accountantPermissions);

        // Employee - basic view permissions
        $employee = Role::firstOrCreate(['name' => 'employee']);
        $employeePermissions = [
            'view-clients',
            'view-items',
            'view-orders',
            'view-dashboard',
        ];
        $employee->syncPermissions($employeePermissions);

        // Create a default super admin user if it doesn't exist
        $superAdminUser = User::firstOrCreate(
            ['email' => 'admin@erp.com'],
            [
                'username' => 'admin',
                'full_name' => 'System Administrator',
                'password' => bcrypt('password'),
                'status' => 1, // 1 for active, 0 for inactive
            ]
        );

        $superAdminUser->assignRole('super-admin');

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info('Super Admin User Created:');
        $this->command->info('Email: admin@erp.com');
        $this->command->info('Password: password');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UnitsPermissionsSeeder extends Seeder
{
    public function run()
    {
        // إنشاء صلاحيات الوحدات
        $permissions = [
            'view-units',
            'create-units', 
            'edit-units',
            'delete-units',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // إعطاء جميع الصلاحيات للسوبر أدمن
        $superAdminRole = Role::where('name', 'super-admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }

        // إعطاء صلاحيات الوحدات للأدمن
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-roles');
    }

    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        // إنشاء الدور الجديد
        $role = Role::create(['name' => $request->name]);

        // لو في صلاحيات مضافة
        if ($request->has('permissions') && !empty($request->permissions)) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        // تحديث الاسم
        $role->update(['name' => $request->name]);

        // جلب الصلاحيات بالـ IDs وتحديثها
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');

    }


    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete super-admin role.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}

<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    /**
     * إنشاء الدور الجديد
     */
    public function persist(): Role
    {
        $role = Role::create(['name' => $this->name]);

        if ($this->has('permissions') && ! empty($this->permissions)) {
            $permissions = Permission::whereIn('id', $this->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return $role;
    }
}

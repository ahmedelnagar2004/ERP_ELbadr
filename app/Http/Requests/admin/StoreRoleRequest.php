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
    public function messages(): array
    {
        return [
            'name.required' => 'اسم الدور مطلوب',
            'name.string' => 'اسم الدور يجب أن يكون نص',
            'name.max' => 'اسم الدور يجب ألا يزيد عن 255 حرف',
            'name.unique' => 'اسم الدور موجود بالفعل',
            'permissions.array' => 'الصلاحيات يجب أن تكون مصفوفة',
            'permissions.*.integer' => 'معرف الصلاحية يجب أن يكون رقماً',
            'permissions.*.exists' => 'إحدى الصلاحيات المختارة غير موجودة',
        ];
    }

}

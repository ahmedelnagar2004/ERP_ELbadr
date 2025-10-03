<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class StoreUserRequest extends FormRequest
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
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'full_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'status' => ['required', 'in:active,inactive'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    /**
     * إنشاء المستخدم الجديد
     */
    public function persist(): User
    {
        // تحويل status من string إلى enum ثم إلى قيمة integer
        $statusEnum = ($this->status === 'active') ? UserStatus::Active : UserStatus::Inactive;

        $user = User::create([
            'username' => $this->username,
            'email' => $this->email,
            'full_name' => $this->full_name,
            'password' => Hash::make($this->password),
            'status' => $this->status,
        ]);

        if ($this->has('roles') && ! empty($this->roles)) {
            $user->assignRole($this->roles);
        }

        return $user;
    }
}

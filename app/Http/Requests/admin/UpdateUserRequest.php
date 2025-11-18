<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')->id;

        return [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($userId),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'full_name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'status' => ['required', 'in:1,0'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }

    /**
     * تحديث المستخدم
     */
    public function persist(User $user): User
    {
        // Use numeric status values directly (1 for active, 0 for inactive)
        $userData = [
            'username' => $this->username,
            'email' => $this->email,
            'full_name' => $this->full_name,
            'status' => $this->status,
        ];

        if ($this->filled('password')) {
            $userData['password'] = Hash::make($this->password);
        }

        $user->update($userData);

        if ($this->has('roles')) {
            $user->syncRoles($this->roles);
        } else {
            $user->syncRoles([]);
        }

        return $user;
    }
}

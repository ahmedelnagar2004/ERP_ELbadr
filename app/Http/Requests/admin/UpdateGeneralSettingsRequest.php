<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneralSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'allow_decimal_quantities' => 'nullable|boolean',
            'default_discount_type' => 'required|in:fixed,percentage',
            'enabled_payment_methods' => 'nullable|array',
            'enabled_payment_methods.*' => 'in:cash,card,bank,credit',
        ];
    }
}

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'allow_decimal_quantities' => 'nullable|boolean',
            'default_discount_type' => 'required|in:fixed,percentage',
            'enabled_payment_methods' => 'nullable|array',
            'enabled_payment_methods.*' => 'in:cash,card,bank,credit',
        ];
    }
}

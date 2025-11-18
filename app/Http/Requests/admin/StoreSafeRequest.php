<?php

namespace App\Http\Requests\Admin;

use App\Models\Safe;
use App\SafeStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreSafeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'integer', 'in:1,2,3,4,5,6'], // 1: محفظة إلكترونية, 2: حساب بنكي, 3: إنستا باي, 4: شبكة, 5: أجل, 6: خزنة داخل الكاشير
            'status' => ['required', 'boolean'], // Checkbox value
            'balance' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'in:EGP,USD,SAR'],
            'account_number' => ['nullable', 'string', 'max:255'], // Made optional to match form
        ];
    }

    /**
     * إنشاء الخزنة الجديدة
     */
    
}

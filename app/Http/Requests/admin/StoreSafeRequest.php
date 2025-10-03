<?php

namespace App\Http\Requests\Admin;

use App\Models\Safe;
use Illuminate\Foundation\Http\FormRequest;

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
            'type' => ['required', 'integer', 'in:1,2,3,4'], // 1 for محفظة إلكترونية, 2 for حساب بنكي, 3 for إنستا باي, 4 for خزنة داخل الكاشير
            'status' => ['required', 'boolean'],
            'balance' => ['required', 'numeric'],
            'currency' => ['required', 'string', 'max:3'],
            'account_number' => ['required', 'string', 'max:255'],
            //  'branch_id' => ['nullable', 'exists:branches,id'],
        ];
    }

    /**
     * إنشاء الخزنة الجديدة
     */
    public function persist(): Safe
    {
        return Safe::create($this->all());
    }
}

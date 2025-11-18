<?php

namespace App\Http\Requests;

use App\Enums\WarehouseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreWareHouseRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', new Enum(WarehouseStatus::class)],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'اسم المستودع مطلوب',
            'name.max' => 'يجب ألا يتجاوز اسم المستودع 255 حرفًا',
            'status.Illuminate\\Validation\\Rules\\Enum' => 'قيمة الحالة غير صالحة',
            'image.image' => 'يجب أن يكون الملف المرفق صورة',
            'image.mimes' => 'يجب أن يكون نوع الملف jpeg أو png أو jpg أو gif',
            'image.max' => 'يجب ألا يتجاوز حجم الصورة 2 ميجابايت',
        ];
    }
}

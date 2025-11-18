<?php

namespace App\Http\Requests\Admin;

use App\CategoryStatus;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Category::class)->ignore($this->route('category')->id),
            ],
            'status' => ['required', 'integer', 'in:0,1'],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم الفئة مطلوب',
            'name.string' => 'اسم الفئة يجب أن يكون نص',
            'name.max' => 'اسم الفئة يجب ألا يزيد عن 255 حرف',
            'name.unique' => 'اسم الفئة موجود بالفعل',
            'status.required' => 'حالة الفئة مطلوبة',
            'status.in' => 'حالة الفئة يجب أن تكون نشط أو غير نشط',
            'photo.image' => 'الملف يجب أن يكون صورة',
            'photo.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, gif, webp',
            'photo.max' => 'حجم الصورة يجب ألا يزيد عن 2 ميجابايت',
        ];
    }

    /**
     * هنا بنعمل العملية كاملة (تحديث الفئة + رفع الصورة)
     */
    
}



<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الفئة مطلوب',
            'name.string' => 'اسم الفئة يجب أن يكون نص<|im_start|>',
            'name.max' => 'اسم الفئة يجب ألا يزيد عن 255 حرف',
            'name.unique' => 'اسم الفئة موجود بالفعل',
            'status.required' => 'حالة الفئة مطلوبة',
            'status.integer' => 'حالة الفئة يجب أن تكون رقماً',
            'status.in' => 'حالة الفئة يجب أن تكون نشط أو غير نشط',
            'photo.image' => 'الملف يجب أن يكون صورة',
            'photo.mimes' => 'الصورة يجب أن تكون من نوع: jpeg, png, jpg, gif, webp',
            'photo.max' => 'حجم الصورة يجب ألا يزيد عن 2 ميجابايت',
        ];
    }
}




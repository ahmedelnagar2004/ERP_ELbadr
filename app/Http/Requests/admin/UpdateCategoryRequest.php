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
            'status' => ['required', 'in:active,inactive'],
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
            'name.string' => 'اسم الفئة يجب أن يكون نصاً',
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
    public function persist(Category $category): Category
    {
        // تحويل status من string إلى enum ثم إلى قيمة integer
        $statusEnum = ($this->status === 'active') ? CategoryStatus::Active : CategoryStatus::Inactive;

        $category->update([
            'name' => $this->name,
            'status' => $statusEnum->value(), // استخدام قيمة الـ enum (1 أو 0)
        ]);

        if ($this->hasFile('photo')) {
            // Delete old photo if exists
            if ($category->photo) {
                Storage::disk('public')->delete($category->photo->path);
                $category->photo->delete();
            }

            $file = $this->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('categories', $filename, 'public');

            $category->photo()->create([
                'filename' => $filename,
                'path' => $path,
                'ext' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'usage' => 'category_photo',
            ]);
        }

        return $category;
    }
}

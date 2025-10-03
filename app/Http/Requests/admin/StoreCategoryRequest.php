<?php

namespace App\Http\Requests\admin;

use App\CategoryStatus;
use App\Models\Category;
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

    /**
     * هنا بنعمل العملية كاملة (إضافة الفئة + رفع الصورة)
     */
    public function persist(): Category
    {
        // تحويل status من string إلى enum ثم إلى قيمة integer
        $statusEnum = ($this->status === 'active') ? CategoryStatus::Active : CategoryStatus::Inactive;

        $category = Category::create([
            'name' => $this->name,
            'status' => $statusEnum->value(), // استخدام قيمة الـ enum (1 أو 0)
        ]);

        if ($this->hasFile('photo')) {
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

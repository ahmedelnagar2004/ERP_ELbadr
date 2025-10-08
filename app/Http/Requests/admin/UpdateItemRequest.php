<?php

namespace App\Http\Requests\Admin;

use App\ItemStatus;
use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'item_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('items', 'item_code')->ignore($this->route('item')->id),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'is_shown_in_store' => ['required', 'in:shown,hidden'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'allow_decimal' => ['boolean'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'delete_photos' => ['nullable', 'array'],
            'delete_photos.*' => ['integer', 'exists:files,id'],
        ];
    }

    /**
     * تحديث المنتج مع الصور
     */
    public function persist(Item $item): Item
    {
        // تحويل is_shown_in_store من string إلى enum ثم إلى قيمة integer
        $statusEnum = ($this->is_shown_in_store === 'shown') ? ItemStatus::Shown : ItemStatus::Hidden;

        $item->update([
            'name' => $this->name,
            'item_code' => $this->item_code,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'is_shown_in_store' => $statusEnum->value(),
            'minimum_stock' => $this->minimum_stock,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'allow_decimal' => $this->allow_decimal ?? false,
        ]);

        // حذف الصور المحددة للحذف
        if ($this->delete_photos) {
            \Log::info('حذف الصور المحددة:', $this->delete_photos);
            foreach ($this->delete_photos as $photoId) {
                $photo = $item->gallery()->find($photoId);
                if ($photo) {
                    \Log::info('حذف الصورة:', ['id' => $photo->id, 'path' => $photo->path]);
                    Storage::disk('public')->delete($photo->path);
                    $photo->delete();
                } else {
                    \Log::warning('الصورة غير موجودة:', $photoId);
                }
            }
        } else {
            \Log::info('لا توجد صور محددة للحذف');
        }

       
        if ($this->hasFile('photos')) {
            foreach ($this->file('photos') as $file) {
                $filename = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('items', $filename, 'public');

                $item->gallery()->create([
                    'filename' => $filename,
                    'path' => $path,
                    'ext' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'usage' => 'item_gallery',
                ]);
            }
        }

        // رفع الصورة الرئيسية
        if ($this->hasFile('photo')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($item->mainPhoto) {
                Storage::disk('public')->delete($item->mainPhoto->path);
                $item->mainPhoto->delete();
            }

            $file = $this->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('items', $filename, 'public');

            $item->mainPhoto()->create([
                'filename' => $filename,
                'path' => $path,
                'ext' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'usage' => 'item_photo',
            ]);
        }

        return $item;
    }
}

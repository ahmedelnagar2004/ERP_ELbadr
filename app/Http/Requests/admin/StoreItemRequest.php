<?php

namespace App\Http\Requests\Admin;

use App\ItemStatus;
use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'item_code' => ['nullable', 'string', 'max:255', 'unique:items,item_code'],
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
        ];
    }

    /**
     * إنشاء المنتج الجديد مع الصور
     */
    public function persist(): Item
    {
        // تحويل is_shown_in_store من string إلى enum ثم إلى قيمة integer
        $statusEnum = ($this->is_shown_in_store === 'shown') ? ItemStatus::Shown : ItemStatus::Hidden;

        $item = Item::create([
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

        // رفع الصور وحفظها في جدول الصور المرتبط بالمنتج
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

        return $item;
    }
}

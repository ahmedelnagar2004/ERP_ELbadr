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



}

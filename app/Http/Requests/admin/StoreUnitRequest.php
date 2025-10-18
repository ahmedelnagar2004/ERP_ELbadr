<?php

namespace App\Http\Requests\Admin;

use App\Models\Unit;
use App\UnitStatus;
use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:units,name'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

}

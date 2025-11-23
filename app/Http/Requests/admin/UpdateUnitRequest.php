<?php

namespace App\Http\Requests\Admin;

use App\Models\Unit;
use App\UnitStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Unit::class)->ignore($this->route('unit')->id),
            ],
            'status' => ['required', 'boolean'],
        ];
    }

}

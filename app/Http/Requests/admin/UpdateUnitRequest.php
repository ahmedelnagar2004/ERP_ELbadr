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
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    /**
     * تحديث الوحدة
     */
    public function persist(Unit $unit): Unit
    {
        // تحويل status من string إلى enum ثم إلى قيمة integer
        $statusEnum = ($this->status === 'active') ? UnitStatus::Active : UnitStatus::Inactive;

        $unit->update([
            'name' => $this->name,
            'status' => $statusEnum->value(),
        ]);

        return $unit;
    }
}

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

    /**
     * إنشاء الوحدة الجديدة
     */
    public function persist(): Unit
    {
        // تحويل status من string إلى enum ثم إلى قيمة integer
        $statusEnum = ($this->status === 'active') ? UnitStatus::Active : UnitStatus::Inactive;

        return Unit::create([
            'name' => $this->name,
            'status' => $statusEnum->value(),
        ]);
    }
}

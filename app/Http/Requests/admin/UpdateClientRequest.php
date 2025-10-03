<?php

namespace App\Http\Requests\Admin;

use App\ClientStatus;
use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($this->route('client')->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clients', 'phone')->ignore($this->route('client')->id),
            ],
            'address' => ['required', 'string'],
            'balance' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    /**
     * تحديث العميل
     */
    public function persist(Client $client): Client
    {
        // تحويل status من string إلى enum ثم إلى قيمة integer
        $statusEnum = ($this->status === 'active') ? ClientStatus::Active : ClientStatus::Inactive;

        $client->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'balance' => $this->balance ?? 0,
            'status' => $statusEnum->value(),
        ]);

        return $client;
    }
}

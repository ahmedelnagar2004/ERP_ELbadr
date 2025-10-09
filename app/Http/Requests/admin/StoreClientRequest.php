<?php

namespace App\Http\Requests\Admin;
use App\Enums\ClientStatus;
use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:clients,email'],
            'phone' => ['required', 'string', 'max:255', 'unique:clients,phone'],
            'address' => ['required', 'string'],
            'balance' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:website,local'],
        ];
    }

    /**
     * إنشاء العميل الجديد
     */
    public function persist(): Client
    {
        // تحويل status من string إلى enum ثم إلى قيمة integer
        $statusEnum = ($this->status === 'website') ? ClientStatus::WEBSITE : ClientStatus::LOCAL;
        return Client::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'balance' => $this->balance ?? 0,
            'status' => $statusEnum->value,
        ]);
    }
}

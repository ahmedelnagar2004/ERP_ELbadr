<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->user()->id;

        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:clients,email,' . $clientId],
            'phone' => ['nullable', 'string', 'max:20', 'unique:clients,phone,' . $clientId],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }
}

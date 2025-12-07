<?php

namespace App\Http\Requests\Api\V1\app;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PaymentMethod;
use Illuminate\Validation\Rules\Enum;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'payment_method' => ['nullable', new Enum(PaymentMethod::class)], 
        ];
    }
}

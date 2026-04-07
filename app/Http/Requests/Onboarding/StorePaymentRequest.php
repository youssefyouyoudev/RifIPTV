<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $bankKeys = [
            'attijariwafa',
            'cih',
            'bank_of_africa',
            'chaabi',
            'cashplus',
            'saham',
        ];

        return [
            'payment_method' => ['required', 'in:card,bank_transfer'],
            'bank_name' => [
                Rule::requiredIf(fn () => $this->input('payment_method') === 'bank_transfer'),
                'nullable',
                'string',
                Rule::in($bankKeys),
            ],
        ];
    }
}

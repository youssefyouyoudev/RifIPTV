<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'phone_country_code' => ['required', 'string', 'regex:/^\+\d{1,4}$/'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9\s\-]{6,20}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}

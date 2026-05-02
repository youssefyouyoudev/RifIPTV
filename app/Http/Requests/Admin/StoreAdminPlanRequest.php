<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'family_slug' => ['required', Rule::in(['smart_tv', 'sup', 'max', 'trex'])],
            'name' => ['nullable', 'string', 'max:80'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:36'],
            'price_mad' => ['required', 'numeric', 'min:0', 'max:99999'],
            'badge_text' => ['nullable', 'string', 'max:40'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'features_text' => ['nullable', 'string', 'max:2000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_enabled' => ['nullable', 'boolean'],
        ];
    }
}

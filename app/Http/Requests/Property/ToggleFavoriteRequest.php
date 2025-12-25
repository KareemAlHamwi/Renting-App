<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class ToggleFavoriteRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'property_id' => ['required', 'integer', 'exists:properties,id'],
        ];
    }
}

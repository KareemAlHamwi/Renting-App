<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    /**
     * Validation rules for creating/updating a property.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'governorate_id' => [
                'required',
                'integer',
                'exists:governorates,id',
            ],

            'address' => [
                'required',
                'string',
                'max:255',
            ],

            'rent' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }
}

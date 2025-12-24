<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class PropertyPhotoRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'photo'      => 'nullable|string',
            'path'       => 'nullable|string',
            'Path'       => 'nullable|string',
            'photos'     => 'nullable|array|max:5',
            'photos.*'   => 'string',
            'order'      => 'nullable|integer|min:1'
        ];
    }
}

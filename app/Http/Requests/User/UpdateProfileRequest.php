<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        $userId = $this->user()?->id;

        return [
            'first_name' => ['sometimes', 'string', 'max:50', 'regex:/^[\p{Arabic}a-zA-Z\s]+$/u'],
            'last_name'  => ['sometimes', 'string', 'max:50', 'regex:/^[\p{Arabic}a-zA-Z\s]+$/u'],
            'birthdate'  => [
                'sometimes',
                'date',
                'before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            ],

            'personal_photo' => ['sometimes', 'string', 'max:8000000'],
            'id_photo'       => ['sometimes', 'string', 'max:8000000'],

            'username' => [
                'sometimes',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_\.]+$/',
                Rule::unique('users', 'username')->ignore($userId),
            ],
        ];
    }
}

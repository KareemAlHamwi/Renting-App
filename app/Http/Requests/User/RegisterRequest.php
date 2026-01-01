<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'first_name' => 'required|string|max:50|regex:/^[\p{Arabic}a-zA-Z\s]+$/u',
            'last_name'  => 'required|string|max:50|regex:/^[\p{Arabic}a-zA-Z\s]+$/u',
            'birthdate'  => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
            ],

            'personal_photo' => ['sometimes', 'string', 'max:8000000'],
            'id_photo'       => ['sometimes', 'string', 'max:8000000'],

            'phone_number' => [
                'required',
                'string',
                'size:10',
                'regex:/^(09[3-9]\d{7}|944\d{7}|095\d{7})$/',
                'unique:users,phone_number',
            ],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_\.]+$/',
                'unique:users,username',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:50',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/',
            ],
            'device_id' => [
                'required',
                'string',
                'max:255'
            ],
        ];
    }

    public function messages(): array {
        return [
            'password.regex'      => 'Password must include lowercase, uppercase, number, and special character.',
            'phone_number.regex'  => 'Invalid Syrian phone number format.',
            'username.regex'      => 'Username may only contain letters, numbers, underscores, and dots.',
        ];
    }
}

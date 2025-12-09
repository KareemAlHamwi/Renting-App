<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest {
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
            'first_name' => 'required|string|max:50|regex:/^[\p{Arabic}a-zA-Z\s]+$/u',
            'last_name' => 'required|string|max:50|regex:/^[\p{Arabic}a-zA-Z\s]+$/u',
            'birthdate' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(16)->format('Y-m-d'), // Minimum 16 years old
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'), // Maximum 100 years old
            ],
            // 'personal_photo' => [
            //     'required',
            //     'image',
            //     'mimes:jpeg,png,jpg',
            //     'max:2048', // 2MB max
            //     'dimensions:min_width=300,min_height=300,max_width=2000,max_height=2000,ratio=1/1', // Square ratio
            // ],
            // 'id_photo' => [
            //     'required',
            //     'image',
            //     'mimes:jpeg,png,jpg,pdf',
            //     'max:5120', // 5MB max
            // ],

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
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
            'device_id' => 'required'
        ];
    }

    public function messages(): array {
        return [
            'password.regex'   => 'Password must include lowercase, uppercase, number, and special character.',
            'phone_number.regex' => 'Invalid Syrian phone number format.',
            'username.regex'   => 'Username may only contain letters, numbers, underscores, and dots.',
        ];
    }
}

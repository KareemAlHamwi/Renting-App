<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'phone_number' => [
                'nullable',
                'regex:/^(09[3-9]\d{7}|095\d{7}|944\d{7})$/',
                'required_without:username',
                'prohibited_if:username,!,'
            ],
            'username' => [
                'nullable',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_.]+$/',
                'required_without:phone_number',
                'prohibited_if:phone_number,!,'
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
                'string'
            ],
        ];
    }

    public function messages(): array {
        return [
            'required_without' => 'Provide either phone number or username.',
            'prohibited_if'    => 'Provide only one field: phone OR username.',
            'password.regex'   => 'Password must contain a lowercase letter, uppercase letter, number, and symbol.',
            'username.regex'   => 'Username may only contain letters, numbers, underscores, and periods.',
            'phone_number.regex' => 'Invalid Syrian phone number format.',
        ];
    }
}

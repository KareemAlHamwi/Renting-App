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
                'string',
                'regex:/^(09[3-9]\d{7}|095\d{7}|944\d{7})$/',
                'required_without:username',
                'prohibits:username',
            ],
            'username' => [
                'nullable',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_.]+$/',
                'required_without:phone_number',
                'prohibits:phone_number',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:50',
            ],
            'device_id' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array {
        return [
            'phone_number.required_without' => 'Provide either phone number or username.',
            'username.required_without'     => 'Provide either phone number or username.',
            'phone_number.prohibits'        => 'Provide only one field: phone OR username.',
            'username.prohibits'            => 'Provide only one field: phone OR username.',
            'username.regex'                => 'Username may only contain letters, numbers, underscores, and periods.',
            'phone_number.regex'            => 'Invalid Syrian phone number format.',
        ];
    }
}

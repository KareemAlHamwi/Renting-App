<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangePhoneRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {

        return [
            'phone_number' => [
                'string',
                'size:10',
                'regex:/^(09[3-9]\d{7}|944\d{7}|095\d{7})$/',
                'unique:users,phone_number',
            ],
        ];
    }
}

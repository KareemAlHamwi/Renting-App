<?php

namespace App\Http\Requests\Push;

use Illuminate\Foundation\Http\FormRequest;

class UpsertDeviceTokenRequest extends FormRequest {
    public function authorize(): bool {
        return (bool) $this->user();
    }

    public function rules(): array {
        return [
            'device_id' => ['required', 'string', 'max:255'],
            'fcm_token' => ['required', 'string', 'max:512'],
            'platform'  => ['nullable', 'string', 'in:android,ios,web'],
        ];
    }
}

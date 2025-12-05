<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {
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
            'phone_number' => [
                'required',
                'string',
                'size:10',
                'regex:/^(09[3-9]\d{7}|944\d{7}|095\d{7})$/',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:50',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
            'device_id' => 'required',
        ];
    }

    public function messages(): array {
        return [
            // 'phone_number.regex' => 'رقم الهاتف غير صالح. الرجاء إدخال رقم سوري صحيح (مثال: 0931234567)',
            // 'phone_number.size' => 'رقم الهاتف يجب أن يكون 10 أرقام',
            // 'phone_number.unique' => 'رقم الهاتف مسجل مسبقاً',
            // 'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير، حرف صغير، رقم، ورمز خاص (@$!%*?&)',
            // 'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ];
    }
}

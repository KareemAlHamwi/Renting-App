<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest {
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
            'first_name' => 'string|max:50|regex:/^[\p{Arabic}a-zA-Z\s]+$/u',
            'last_name' => 'string|max:50|regex:/^[\p{Arabic}a-zA-Z\s]+$/u',
            'birthdate' => [
                'date',
                'before_or_equal:' . now()->subYears(16)->format('Y-m-d'), // Minimum 16 years old
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'), // Maximum 100 years old
            ],
            // 'personal_photo' => [
            //     'image',
            //     'mimes:jpeg,png,jpg',
            //     'max:2048', // 2MB max
            //     'dimensions:min_width=300,min_height=300,max_width=2000,max_height=2000,ratio=1/1', // Square ratio
            // ],
            // 'id_photo' => [
            //     'image',
            //     'mimes:jpeg,png,jpg,pdf',
            //     'max:5120', // 5MB max
            // ],

            'phone_number' => [
                'string',
                'size:10',
                'regex:/^(09[3-9]\d{7}|944\d{7}|095\d{7})$/',
                'unique:users,phone_number',
            ],
            'username' => [
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_\.]+$/',
                'unique:users,username',
            ],
            'password' => [
                'string',
                'min:8',
                'max:50',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ]
        ];
    }

    public function messages(): array {
        return [
            // 'first_name.regex' => 'الاسم يجب أن يحتوي على أحرف عربية أو إنجليزية فقط',
            // 'last_name.regex' => 'الاسم يجب أن يحتوي على أحرف عربية أو إنجليزية فقط',
            // 'birthdate.before_or_equal' => 'يجب أن يكون عمرك 16 سنة على الأقل',
            // 'birthdate.after_or_equal' => 'الرجاء إدخال تاريخ ميلاد صحيح',
            // 'personal_photo.dimensions' => 'الصورة الشخصية يجب أن تكون مربعة وبجودة مناسبة',
            // 'personal_photo.max' => 'حجم الصورة الشخصية يجب ألا يتجاوز 2 ميجابايت',
            // 'id_photo.max' => 'حجم صورة الهوية يجب ألا يتجاوز 5 ميجابايت',

            // 'phone_number.regex' => 'رقم الهاتف غير صالح. الرجاء إدخال رقم سوري صحيح (مثال: 0931234567)',
            // 'phone_number.size' => 'رقم الهاتف يجب أن يكون 10 أرقام',
            // 'phone_number.unique' => 'رقم الهاتف مسجل مسبقاً',
            // 'username.regex' => 'اسم المستخدم يجب أن يحتوي على أحرف إنجليزية وأرقام ونقاط وشرطات سفلية فقط',
            // 'username.unique' => 'اسم المستخدم مسجل مسبقاً',
            // 'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير، حرف صغير، رقم، ورمز خاص (@$!%*?&)',
            // 'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ];
    }
}

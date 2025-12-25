<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'stars' => [
                'numeric',
                'min:0',
                'max:5',
                'regex:/^\d(\.0|\.5)?$/',

                // enforce 0.5 increments: 0.0, 0.5, 1.0 ... 5.0
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (!is_numeric($value)) {
                        $fail("The {$attribute} must be numeric.");
                        return;
                    }

                    $v = (float) $value;

                    // Allow half steps: v * 2 must be an integer
                    $scaled = $v * 2;

                    // float-safe integer check with small tolerance
                    if (abs($scaled - round($scaled)) > 1e-9) {
                        $fail("The {$attribute} must be in 0.5 increments (e.g., 0.5, 1.0, 1.5 ... 5.0).");
                    }
                },
            ],

            'review' => ['nullable', 'string'],
        ];
    }
}

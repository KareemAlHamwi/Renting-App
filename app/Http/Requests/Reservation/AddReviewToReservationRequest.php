<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class AddReviewToReservationRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'rating' => [
                'required',
                'numeric',
                'min:0',
                'max:5',
                'regex:/^\d(\.0|\.5)?$/',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (!is_numeric($value)) {
                        $fail("The {$attribute} must be numeric.");
                        return;
                    }

                    $v = (float) $value;
                    $scaled = $v * 2;

                    if (abs($scaled - round($scaled)) > 1e-9) {
                        $fail("The {$attribute} must be in 0.5 increments (e.g., 0.5, 1.0, 1.5 ... 5.0).");
                    }
                },
            ],

            'comment' => ['nullable', 'string'],
        ];
    }
}

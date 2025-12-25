<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest {
    public function authorize(): bool {
        return true; // auth is via middleware
    }

    public function rules(): array {
        return [
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after:start_date'],
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            // NOTE: user_id is NOT accepted from client; we use auth()->id()
            // 'user_id'    => 'required|exists:users,id',
        ];
    }
}

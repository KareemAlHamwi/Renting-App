<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservedPeriodResource extends JsonResource {
    public function toArray($request): array {
        return [
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
        ];
    }
}

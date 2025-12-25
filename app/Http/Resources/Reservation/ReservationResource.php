<?php

namespace App\Http\Resources\Reservation;

use App\Enums\ReservationStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource {
    public function toArray($request): array {
        $status = $this->status; // if casted to enum, it's already ReservationStatus

        return [
            'id'          => $this->id,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'status'      => $status instanceof ReservationStatus ? $status->value : (int) $status,
            'status_name' => $status instanceof ReservationStatus ? $status->name : null,
            'user_id'     => $this->user_id,
            'property_id' => $this->property_id,
            'review_id'   => $this->review_id,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}

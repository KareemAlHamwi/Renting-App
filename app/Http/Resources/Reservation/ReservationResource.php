<?php

namespace App\Http\Resources\Reservation;

use App\Enums\ReservationStatus;
use App\Support\Utilities;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource {
    public function toArray($request): array {
        $status = $this->status;
        $primary = $this->property->primaryPhoto;

        return [
            'id'          => $this->id,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'status'      => $status instanceof ReservationStatus ? $status->value : (int) $status,
            'status_name' => $status instanceof ReservationStatus ? $status->name : null,
            'user_id'     => $this->user_id,
            'property_id' => $this->property_id,
            'cancelled_by' => $this->cancelledBy ? $this->cancelledBy->username : null,
            'username' => $this->user ? $this->user->username : null,
            'review_id' => $this->review ? $this->review->id : null,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,

            'primary_photo' => $primary ? [
                'path' => $primary->path,
                'url' => Utilities::photoUrl($primary->path),
            ] : null,
        ];
    }
}

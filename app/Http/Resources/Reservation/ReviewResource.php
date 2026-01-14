<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id'         => $this->id,
            'reservation_id' => $this->reservation_id,
            'username' => $this->reservation->user->username,
            'rating'      => (float) $this->rating,
            'comment'     => $this->comment,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}

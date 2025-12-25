<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id'         => $this->id,
            'stars'      => (float) $this->stars,
            'review'     => $this->review,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}

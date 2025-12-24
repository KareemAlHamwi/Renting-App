<?php

namespace App\Repositories\Eloquent\Reservation;

use App\Models\Reservation\Reservation;
use App\Models\Reservation\Review;
use App\Repositories\Contracts\Reservation\ReservationRepositoryInterface;

class ReservationRepository implements ReservationRepositoryInterface {
    public function findById($id): Reservation {
        return Reservation::findOrFail($id);
    }

    public function create(array $data): Reservation {
        return Reservation::create($data);
    }

    public function attachReview(Reservation $reservation, Review $review): void {
        $reservation->update(['review_id' => $review->id]);
    }

    public function checkConflict(array $data): bool {
        return Reservation::where('property_id', $data['property_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']]);
            })
            ->where('status', '!=', 4)
            ->exists();
    }
}

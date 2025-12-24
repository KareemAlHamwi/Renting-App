<?php

namespace App\Services\Reservation;

use App\Models\Reservation\Reservation;
use App\Models\Reservation\Review;

class ReservationService {
    public function createReservation(array $data): Reservation {
        $conflict = Reservation::where('property_id', $data['property_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']]);
            })
            ->where('status', '!=', 4)
            ->exists();

        if ($conflict) {
            throw new \Exception('The property is booked during this period.');
        }

        return Reservation::create($data);
    }



    public function addReviewToReservation(int $reservationId, array $reviewData): Review {
        $reservation = Reservation::findOrFail($reservationId);

        if ($reservation->status !== 3) {
            throw new \Exception('Bookings cannot be evaluated before they are completed.');
        }

        $review = Review::create($reviewData);

        $reservation->update([
            'review_id' => $review->id
        ]);

        return $review;
    }
}

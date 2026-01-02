<?php

namespace App\Repositories\Eloquent\Reservation;

use App\Models\Property\Property;
use App\Models\Reservation\Review;
use App\Repositories\Contracts\Reservation\ReviewRepositoryInterface;

class ReviewRepository implements ReviewRepositoryInterface {
    public function getAllPropertyReviews(Property $property) {
        return Review::query()
            ->whereHas('reservation', function ($q) use ($property) {
                $q->where('property_id', $property->id);
            })
            ->whereNotNull('comment')
            ->whereRaw("TRIM(comment) <> ''")
            ->with(['reservation'])
            ->latest()
            ->get();
    }

    public function findById(Review $review) {
        return Review::findOrFail($review->id);
    }

    public function create(array $data) {
        $reservationId = $data['reservation_id'] ?? null;
        if (!$reservationId) {
            throw new \InvalidArgumentException('reservation_id is required.');
        }

        if (Review::query()->where('reservation_id', $reservationId)->exists()) {
            throw new \RuntimeException('This reservation already has a review.');
        }

        return Review::create($data);
    }

    public function update(Review $review, array $data): Review {
        $review->update($data);
        return $review;
    }

    public function delete(Review $review): void {
        $review->delete();
    }
}

<?php

namespace App\Repositories\Eloquent\Reservation;

use App\Models\Reservation\Review;
use App\Repositories\Contracts\Reservation\ReviewRepositoryInterface;

class ReviewRepository implements ReviewRepositoryInterface {
    public function getAllPropertyReviews($propertyId) {
        return Review::query()
            ->whereHas('reservation', function ($q) use ($propertyId) {
                $q->where('property_id', $propertyId);
            })
            ->latest()
            ->get();
    }

    public function findById($id): Review {
        return Review::findOrFail($id);
    }

    public function create(array $data): Review {
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

<?php

namespace App\Repositories\Contracts\Reservation;

use App\Models\Reservation\Review;

interface ReviewRepositoryInterface {
    public function getAllPropertyReviews($propertyId);
    public function findById($id): Review;
    public function create(array $data): Review;
    public function update(Review $review, array $data): Review;
    public function delete(Review $review): void;
}

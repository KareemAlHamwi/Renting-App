<?php

namespace App\Repositories\Contracts\Reservation;

use App\Models\Property\Property;
use App\Models\Reservation\Review;

interface ReviewRepositoryInterface {
    public function getAllPropertyReviews(Property $property);
    public function findById(Review $review);
    public function create(array $data);
    public function update(Review $review, array $data);
    public function delete(Review $review);
}

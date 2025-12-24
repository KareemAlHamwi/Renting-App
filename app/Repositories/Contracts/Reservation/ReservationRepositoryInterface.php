<?php

namespace App\Repositories\Contracts\Reservation;

use App\Models\Reservation\Reservation;
use App\Models\Reservation\Review;

interface ReservationRepositoryInterface {
    public function findById($id): Reservation;
    public function create(array $data): Reservation;
    public function attachReview(Reservation $reservation, Review $review): void;
    public function checkConflict(array $data): bool;
}

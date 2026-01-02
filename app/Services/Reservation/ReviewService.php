<?php

namespace App\Services\Reservation;

use App\Models\Property\Property;
use App\Models\Reservation\Review;
use App\Repositories\Contracts\Reservation\ReviewRepositoryInterface;

class ReviewService {
    private $reviewRepository;

    public function __construct(ReviewRepositoryInterface $reviewRepository) {
        $this->reviewRepository = $reviewRepository;
    }

    public function getAllPropertyReviews(Property $property) {
        return $this->reviewRepository->getAllPropertyReviews($property);
    }

    public function getReview(Review $review) {
        return $this->reviewRepository->findById($review);
    }

    public function createReview(array $data) {
        if (empty($data['reservation_id'])) {
            throw new \InvalidArgumentException('reservation_id is required.');
        }

        return $this->reviewRepository->create($data);
    }

    public function updateReview(Review $review, array $data) {
        unset($data['reservation_id']);

        return $this->reviewRepository->update($review, $data);
    }

    public function deleteReview(Review $review) {
        $this->reviewRepository->delete($review);
    }
}

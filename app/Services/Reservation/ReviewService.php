<?php

namespace App\Services\Reservation;

use App\Models\Reservation\Review;
use App\Repositories\Contracts\Reservation\ReviewRepositoryInterface;

class ReviewService {
    private $reviewRepository;

    public function __construct(
        ReviewRepositoryInterface $reviewRepository
    ) {
        $this->reviewRepository = $reviewRepository;
    }

    public function getAllPropertyReviews($propertyId) {
        return $this->reviewRepository->getAllPropertyReviews($propertyId);
    }

    public function getReview($id): Review {
        return $this->reviewRepository->findById($id);
    }

    public function createReview(array $data): Review {
        if (empty($data['reservation_id'])) {
            throw new \InvalidArgumentException('reservation_id is required.');
        }

        return $this->reviewRepository->create($data);
    }

    public function updateReview($id, array $data): Review {
        $review = $this->reviewRepository->findById($id);

        unset($data['reservation_id']);

        return $this->reviewRepository->update($review, $data);
    }

    public function deleteReview($id): void {
        $review = $this->reviewRepository->findById($id);
        $this->reviewRepository->delete($review);
    }
}

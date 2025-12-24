<?php

namespace App\Services\Reservation;

use App\Models\Reservation\Review;
use App\Repositories\Contracts\Reservation\ReviewRepositoryInterface;

class ReviewService {
    protected $reviewRepo;

    public function __construct(ReviewRepositoryInterface $reviewRepo) {
        $this->reviewRepo = $reviewRepo;
    }

    public function createReview(array $data): Review {
        return $this->reviewRepo->create($data);
    }

    public function updateReview($id, array $data): Review {
        $review = $this->reviewRepo->findById($id);
        return $this->reviewRepo->update($review, $data);
    }

    public function deleteReview($id): void {
        $review = $this->reviewRepo->findById($id);
        $this->reviewRepo->delete($review);
    }

    public function getReview($id): Review {
        return $this->reviewRepo->findById($id);
    }
}

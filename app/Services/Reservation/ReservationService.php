<?php

namespace App\Services\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Reservation\Reservation;
use App\Models\Reservation\Review;
use App\Repositories\Contracts\Reservation\ReservationRepositoryInterface;
use App\Repositories\Contracts\Reservation\ReviewRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;

class ReservationService {
    private ReservationRepositoryInterface $reservationRepository;
    private ReviewRepositoryInterface $reviewRepository;
    public function __construct(
        ReservationRepositoryInterface $reservationRepository,
        ReviewRepositoryInterface $reviewRepository
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function createReservation(int $userId, int $propertyId, string $startDate, string $endDate): Reservation {
        if ($this->reservationRepository->checkConflict($propertyId, $startDate, $endDate)) {
            throw new \RuntimeException('The property is booked during this period.');
        }

        return $this->reservationRepository->create([
            'user_id'     => $userId,
            'property_id' => $propertyId,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'status'      => ReservationStatus::Pending,
        ]);
    }

    public function getReservedPeriods(int $propertyId) {
        return $this->reservationRepository->getReservedPeriods($propertyId);
    }

    public function addReviewToReservation(int $userId, int $reservationId, array $reviewData): Review {
        $reservation = $this->reservationRepository->findById($reservationId);

        if ($reservation->user_id !== $userId) {
            throw new AuthorizationException('You are not allowed to review this reservation.');
        }

        if ($reservation->status !== ReservationStatus::Completed) {
            throw new \RuntimeException('Bookings cannot be evaluated before they are completed.');
        }

        if (!is_null($reservation->review_id)) {
            throw new \RuntimeException('This reservation already has a review.');
        }

        $review = $this->reviewRepository->create($reviewData);

        $this->reservationRepository->attachReview($reservation, $review->id);

        return $review;
    }
}

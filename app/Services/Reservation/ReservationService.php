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

    public function getAllReservations() {
        return $this->reservationRepository->getAllReservations();
    }

    public function getLandlordPropertyReservations(int $landlordUserId, int $propertyId) {
        return $this->reservationRepository->getLandlordPropertyReservations($landlordUserId, $propertyId);
    }

    public function getTenantReservations(int $tenantUserId) {
        return $this->reservationRepository->getTenantReservations($tenantUserId);
    }

    public function createReservation(int $userId, int $propertyId, string $startDate, string $endDate): Reservation {
        $endDate = $endDate ?: $startDate;

        if ($this->reservationRepository->checkConflict($propertyId, $startDate, $endDate)) {
            throw new \RuntimeException('The property is booked during this period.');
        }

        return $this->reservationRepository->createReservation([
            'user_id'     => $userId,
            'property_id' => $propertyId,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'status'      => ReservationStatus::Pending,
        ]);
    }

    public function updateReservation(int $reservationId, array $data): Reservation {
        $reservation = $this->reservationRepository->findById($reservationId);

        if (in_array($reservation->status, [ReservationStatus::Cancelled, ReservationStatus::Completed], true)) {
            throw new \RuntimeException('You cannot update a cancelled/completed reservation.');
        }

        $propertyId = (int) ($data['property_id'] ?? $reservation->property_id);

        $startDate = (string) ($data['start_date'] ?? $reservation->start_date->format('Y-m-d'));
        $endDate = (string) (($data['end_date'] ?? null) ?: ($reservation->end_date?->format('Y-m-d') ?? $startDate));
        $endDate = $endDate ?: $startDate;

        if ($this->reservationRepository->checkConflictExceptReservation($propertyId, $startDate, $endDate, $reservation->id)) {
            throw new \RuntimeException('The property is booked during this period.');
        }

        // Reset to Pending + apply data
        $updateData = array_merge($data, [
            'status'      => ReservationStatus::Pending,
            'property_id' => $propertyId,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
        ]);

        return $this->reservationRepository->updateReservation($reservation, $updateData);
    }

    public function approveReservation(int $reservationId): Reservation {
        $reservation = $this->reservationRepository->findById($reservationId);

        if ($reservation->status !== ReservationStatus::Pending) {
            throw new \RuntimeException('Only pending reservations can be approved.');
        }

        return $this->reservationRepository->approveReservation($reservation);
    }

    public function cancelReservation(int $reservationId): Reservation {
        $reservation = $this->reservationRepository->findById($reservationId);

        if ($reservation->status === ReservationStatus::Completed) {
            throw new \RuntimeException('You cannot cancel a completed reservation.');
        }

        if ($reservation->status === ReservationStatus::Cancelled) {
            return $reservation; // already cancelled
        }

        return $this->reservationRepository->cancelReservation($reservation);
    }

    public function markExpiredReservationsCompleted(): int {
        return $this->reservationRepository->markExpiredReservationsCompleted();
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

        // NEW: check by reservation_id (or use $reservation->review()->exists())
        if ($reservation->review()->exists()) {
            throw new \RuntimeException('This reservation already has a review.');
        }

        // NEW: create review and link it via reservation_id
        $review = $this->reviewRepository->create([
            ...$reviewData,
            'reservation_id' => $reservationId,
        ]);

        return $review;
    }
}

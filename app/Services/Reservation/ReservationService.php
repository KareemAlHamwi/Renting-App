<?php

namespace App\Services\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Property\Property;
use App\Models\Reservation\Reservation;
use App\Models\Reservation\Review;
use App\Models\User\User;
use App\Repositories\Contracts\Reservation\ReservationRepositoryInterface;
use App\Repositories\Contracts\Reservation\ReviewRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

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

    public function getLandlordPropertyReservations(User $landlord, Property $property) {
        return $this->reservationRepository->getLandlordPropertyReservations($landlord, $property);
    }

    public function getTenantReservations(User $tenant) {
        return $this->reservationRepository->getTenantReservations($tenant);
    }

    public function createReservation(User $user, Property $property, string $startDate, string $endDate) {
        $endDate = $endDate ?: $startDate;

        if ($this->reservationRepository->checkConflict($property, $startDate, $endDate)) {
            throw new \RuntimeException('The property is booked during this period.');
        }

        return $this->reservationRepository->createReservation([
            'user_id'     => $user->id,
            'property_id' => $property->id,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'status'      => ReservationStatus::Pending,
        ]);
    }

    public function updateReservation(Reservation $reservation, array $data): Reservation {
        if (in_array($reservation->status, [ReservationStatus::Cancelled, ReservationStatus::Completed], true)) {
            throw new ConflictHttpException('You cannot update a cancelled/completed reservation.');
        }

        $property = $reservation->relationLoaded('property')
            ? $reservation->property
            : $reservation->load('property')->property;

        $startDate = (string) ($data['start_date'] ?? $reservation->start_date->format('Y-m-d'));
        $endDate   = (string) (($data['end_date'] ?? null) ?: ($reservation->end_date?->format('Y-m-d') ?? $startDate));
        $endDate   = $endDate ?: $startDate;

        if ($this->reservationRepository->checkConflictExceptReservation($property, $startDate, $endDate, $reservation)) {
            throw new ConflictHttpException('The property is booked during this period.');
        }

        $updateData = array_merge($data, [
            'status'     => ReservationStatus::Pending,
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ]);

        return $this->reservationRepository->updateReservation($reservation, $updateData);
    }

    public function approveReservation(Reservation $reservation) {
        if ($reservation->status !== ReservationStatus::Pending) {
            throw new \RuntimeException('Only pending reservations can be approved.');
        }

        return $this->reservationRepository->approveReservation($reservation);
    }

    public function cancelReservation(Reservation $reservation, User $cancelledBy): Reservation {
        if ($reservation->status === ReservationStatus::Cancelled) {
            return $reservation;
        }

        return $this->reservationRepository->cancelReservation($reservation, $cancelledBy);
    }

    public function markExpiredReservationsCompleted() {
        return $this->reservationRepository->markExpiredReservationsCompleted();
    }

    public function getReservedPeriods(Property $property) {
        return $this->reservationRepository->getReservedPeriods($property);
    }

    public function addReviewToReservation(User $user, Reservation $reservation, array $reviewData): Review {
        if ($reservation->user_id !== $user->id) {
            throw new AuthorizationException('You are not allowed to review this reservation.');
        }

        if ($reservation->status !== ReservationStatus::Completed) {
            throw new \RuntimeException('Bookings cannot be evaluated before they are completed.');
        }

        if ($reservation->review()->exists()) {
            throw new \RuntimeException('This reservation already has a review.');
        }

        $review = $this->reviewRepository->create([
            ...$reviewData,
            'reservation_id' => $reservation->id,
        ]);

        return $review;
    }
}

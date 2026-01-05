<?php

namespace App\Repositories\Contracts\Reservation;

use App\Models\Property\Property;
use App\Models\Reservation\Reservation;
use App\Models\User\User;

interface ReservationRepositoryInterface {
    public function getAllReservations(array $filters);
    public function getLandlordPropertyReservations(User $landlord, Property $property);
    public function getTenantReservations(User $tenant);
    public function findById(Reservation $reservation);
    public function createReservation(array $data);
    public function updateReservation(Reservation $reservation, array $data);
    public function approveReservation(Reservation $reservation);
    public function cancelReservation(Reservation $reservation,User $cancelledBy);
    public function markExpiredReservationsCompleted();
    public function checkConflict(Property $property, string $startDate, string $endDate);
    public function checkConflictExceptReservation(Property $property, string $startDate, string $endDate, Reservation $ignoreReservation);
    public function getReservedPeriods(Property $property);
}

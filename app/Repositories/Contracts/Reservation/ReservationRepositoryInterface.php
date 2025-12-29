<?php

namespace App\Repositories\Contracts\Reservation;

use App\Models\Reservation\Reservation;
use Illuminate\Support\Collection;

interface ReservationRepositoryInterface {
    public function getAllReservations(): Collection;
    public function getLandlordPropertyReservations(int $landlordUserId, int $propertyId);
    public function getTenantReservations(int $tenantUserId);
    public function findById(int $id): Reservation;
    public function createReservation(array $data): Reservation;
    public function updateReservation(Reservation $reservation, array $data): Reservation;
    public function approveReservation(Reservation $reservation): Reservation;
    public function cancelReservation(Reservation $reservation): Reservation;
    public function markExpiredReservationsCompleted(): int;
    public function checkConflict(int $propertyId, string $startDate, string $endDate): bool;
    public function checkConflictExceptReservation(int $propertyId, string $startDate, string $endDate, int $ignoreReservationId): bool;
    public function getReservedPeriods(int $propertyId): Collection;
}

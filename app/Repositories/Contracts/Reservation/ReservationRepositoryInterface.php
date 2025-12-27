<?php

namespace App\Repositories\Contracts\Reservation;

use App\Models\Reservation\Reservation;
use Illuminate\Support\Collection;

interface ReservationRepositoryInterface {
    public function getAllReservations(): Collection;
    public function getLandlordPropertyReservations(int $landlordUserId, int $propertyId);
    public function getTenantReservations(int $tenantUserId);
    public function findById(int $id): Reservation;
    public function create(array $data): Reservation;
    public function checkConflict(int $propertyId, string $startDate, string $endDate): bool;
    public function getReservedPeriods(int $propertyId): Collection;
    public function attachReview(Reservation $reservation, int $reviewId): Reservation;
}

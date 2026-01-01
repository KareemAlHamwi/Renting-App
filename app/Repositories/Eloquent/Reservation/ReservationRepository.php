<?php

namespace App\Repositories\Eloquent\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Reservation\Reservation;
use App\Repositories\Contracts\Reservation\ReservationRepositoryInterface;
use Illuminate\Support\Collection;

class ReservationRepository implements ReservationRepositoryInterface {
    public function getAllReservations(): Collection {
        return Reservation::query()
            ->with([
                'user',
                'property.owner',
                'property.governorate',
                'property.primaryPhoto',
                'review',
            ])
            ->orderByDesc('start_date')
            ->get();
    }

    public function getLandlordPropertyReservations(int $landlordUserId, int $propertyId): Collection {
        return Reservation::query()
            ->with(['user', 'review']) // tenant + review (add 'property' if you need it)
            ->where('property_id', $propertyId)
            ->whereHas('property', function ($q) use ($landlordUserId) {
                $q->where('user_id', $landlordUserId);
            })
            ->orderByDesc('start_date')
            ->get();
    }

    public function getTenantReservations(int $tenantUserId): Collection {
        return Reservation::query()
            ->with([
                'property.governorate',
                'property.primaryPhoto', // smallest payload for listing
                'review',
            ])
            ->where('user_id', $tenantUserId)
            ->orderByDesc('start_date')
            ->get();
    }

    public function findById(int $id): Reservation {
        return Reservation::query()->findOrFail($id);
    }

    public function createReservation(array $data): Reservation {
        return Reservation::query()->create($data);
    }

    public function updateReservation(Reservation $reservation, array $data): Reservation {
        $reservation->update($data);
        return $reservation->refresh();
    }

    public function approveReservation(Reservation $reservation): Reservation {
        return $this->updateReservation($reservation, [
            'status' => ReservationStatus::Reserved,
        ]);
    }

    public function cancelReservation(Reservation $reservation, int $cancelledBy): Reservation {
        return $this->updateReservation($reservation, [
            'status'       => ReservationStatus::Cancelled,
            'cancelled_by' => $cancelledBy,
        ]);
    }

    public function markExpiredReservationsCompleted(): int {
        return Reservation::query()
            ->where('status', ReservationStatus::Reserved)
            ->whereDate('end_date', '<', now()->toDateString())
            ->update(['status' => ReservationStatus::Completed]);
    }

    public function checkConflict(int $propertyId, string $startDate, string $endDate): bool {
        return Reservation::query()
            ->where('property_id', $propertyId)
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Reserved,
            ])
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->exists();
    }

    public function checkConflictExceptReservation(
        int $propertyId,
        string $startDate,
        string $endDate,
        int $ignoreReservationId
    ): bool {
        return Reservation::query()
            ->where('property_id', $propertyId)
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Reserved,
            ])
            ->whereKeyNot($ignoreReservationId)
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->exists();
    }

    public function getReservedPeriods(int $propertyId): Collection {
        return Reservation::query()
            ->where('property_id', $propertyId)
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Reserved,
            ])
            ->orderBy('start_date')
            ->get(['start_date', 'end_date']);
    }
}

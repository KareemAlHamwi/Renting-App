<?php

namespace App\Repositories\Eloquent\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Reservation\Reservation;
use App\Repositories\Contracts\Reservation\ReservationRepositoryInterface;
use Illuminate\Support\Collection;

class ReservationRepository implements ReservationRepositoryInterface {
    public function findById(int $id): Reservation {
        return Reservation::query()->findOrFail($id);
    }

    public function create(array $data): Reservation {
        return Reservation::query()->create($data);
    }

    public function checkConflict(int $propertyId, string $startDate, string $endDate): bool {
        return Reservation::query()
            ->where('property_id', $propertyId)
            ->whereIn('status', [
                ReservationStatus::Pending,
                ReservationStatus::Reserved,
            ])
            ->where('start_date', '<', $endDate)
            ->where('end_date', '>', $startDate)
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

    public function attachReview(Reservation $reservation, int $reviewId): Reservation {
        $reservation->update(['review_id' => $reviewId]);
        return $reservation->refresh();
    }
}

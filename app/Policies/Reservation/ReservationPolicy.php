<?php

namespace App\Policies\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Property\Property;
use App\Models\User\User;
use App\Models\Reservation\Reservation;

class ReservationPolicy {
    public function landlordPropertyReservations(User $user): bool {
        return $this->isVerified($user);
    }

    public function tenantReservations(User $user): bool {
        return $this->isVerified($user);
    }

    public function create(User $user, int|Property $property): bool {
        if (! $this->isVerified($user)) return false;

        $propertyId = $property instanceof Property ? $property->id : $property;

        // Can't reserve your own property
        return ! Property::query()
            ->whereKey($propertyId)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function update(User $user, int|Reservation $reservation): bool {
        if (! $this->isVerified($user)) return false;

        $reservationId = $reservation instanceof Reservation ? $reservation->id : $reservation;

        // Only the tenant (owner of the reservation) can update it
        return Reservation::query()
            ->whereKey($reservationId)
            ->where('user_id', $user->id)
            ->whereNotIn('status', [
                ReservationStatus::Cancelled,
                ReservationStatus::Completed,
            ])
            ->exists();
    }

    public function approve(User $user, int|Reservation $reservation): bool {
        if (! $this->isVerified($user)) return false;

        $reservationId = $reservation instanceof Reservation ? $reservation->id : $reservation;

        // Only the landlord (owner of the property) can approve, and only if Pending
        return Reservation::query()
            ->whereKey($reservationId)
            ->where('status', ReservationStatus::Pending)
            ->whereHas('property', fn($q) => $q->where('user_id', $user->id))
            ->exists();
    }

    public function cancel(User $user, int|Reservation $reservation): bool {
        if (! $this->isVerified($user)) return false;

        $reservationId = $reservation instanceof Reservation ? $reservation->id : $reservation;

        // Tenant OR landlord can cancel if not Completed and not already Cancelled
        return Reservation::query()
            ->whereKey($reservationId)
            ->whereNotIn('status', [
                ReservationStatus::Cancelled,
                ReservationStatus::Completed,
            ])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id) // tenant
                    ->orWhereHas('property', fn($qq) => $qq->where('user_id', $user->id)); // landlord
            })
            ->exists();
    }

    public function addReview(User $user, int|Reservation $reservation): bool {
        if (! $this->isVerified($user)) return false;

        $reservationId = $reservation instanceof Reservation ? $reservation->id : $reservation;

        return Reservation::query()
            ->whereKey($reservationId)
            ->where('user_id', $user->id)
            ->exists();
    }

    private function isVerified(User $user): bool {
        return !is_null($user->verified_at);
    }
}

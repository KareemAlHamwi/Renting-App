<?php

namespace App\Policies\Reservation;

use App\Enums\ReservationStatus;
use App\Models\Property\Property;
use App\Models\Reservation\Reservation;
use App\Models\User\User;
use function Illuminate\Support\now;

class ReservationPolicy {
    public function landlordPropertyReservations(User $user): bool {
        return $this->isVerified($user);
    }

    public function tenantReservations(User $user): bool {
        return $this->isVerified($user);
    }

    public function create(User $user, Property $property): bool {
        if (! $this->isVerified($user)) {
            return false;
        }

        if ($property->user_id === $user->id) {
            return false;
        }

        if ($user->activeReservationsForProperty($property)->exists()) {
            return false;
        }

        return true;
    }

    public function update(User $user, Reservation $reservation): bool {
        if ($reservation->user_id !== $user->id) {
            return false;
        }

        if (! $this->isVerified($user)) {
            return false;
        }

        if (in_array($reservation->status, [
            ReservationStatus::Cancelled,
            ReservationStatus::Completed,
        ], true)) {
            return false;
        }

        if ($reservation->start_date->lte(now())) {
            return false;
        }

        return true;
    }

    public function approve(User $user, Reservation $reservation): bool {
        if (!$this->isVerified($user)) {
            return false;
        }

        if ($reservation->status !== ReservationStatus::Pending) {
            return false;
        }

        return (int) $reservation->property?->user_id === (int) $user->id;
    }

    public function cancel(User $user, Reservation $reservation): bool {
        if (!$this->isVerified($user)) {
            return false;
        }

        if (in_array($reservation->status, [
            ReservationStatus::Cancelled,
            ReservationStatus::Completed,
        ], true)) {
            return false;
        }

        $isTenant = ((int) $reservation->user_id === (int) $user->id);

        $isLandlord = ((int) $reservation->property?->user_id === (int) $user->id);

        return $isTenant || $isLandlord;
    }

    public function addReview(User $user, Reservation $reservation): bool {
        if (!$this->isVerified($user)) {
            return false;
        }

        return (int) $reservation->user_id === (int) $user->id;
    }

    private function isVerified(User $user): bool {
        return $user->verified_at !== null;
    }
}

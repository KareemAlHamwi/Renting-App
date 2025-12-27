<?php

namespace App\Policies\Reservation;

use App\Models\User\User;
use App\Models\Reservation\Reservation;

class ReservationPolicy {
    public function landlordPropertyReservations(User $user): bool {
        return $this->isVerified($user);
    }

    public function tenantReservations(User $user): bool {
        return $this->isVerified($user);
    }

    public function create(User $user): bool {
        return $this->isVerified($user);
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

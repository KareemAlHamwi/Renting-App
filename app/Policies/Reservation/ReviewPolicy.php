<?php

namespace App\Policies\Reservation;

use App\Models\User\User;
use App\Models\Reservation\Reservation;
use App\Models\Reservation\Review;

class ReviewPolicy {
    public function update(User $user, Review $review): bool {
        return Reservation::query()
            ->where('review_id', $review->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function delete(User $user, Review $review): bool {
        return $this->update($user, $review);
    }

    public function view(User $user, Review $review): bool {
        // Optional: allow only the author; or return true to make it public.
        return $this->update($user, $review);
    }
}

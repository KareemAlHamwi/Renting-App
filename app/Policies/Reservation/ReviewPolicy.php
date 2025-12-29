<?php

namespace App\Policies\Reservation;

use App\Models\User\User;
use App\Models\Reservation\Reservation;
use App\Models\Reservation\Review;

class ReviewPolicy {
    public function viewAny(User $user): bool {
        return $this->isVerified($user);
    }

    public function view(User $user, Review $review): bool {
        return $this->isVerified($user) && $this->isAuthor($user, $review);
    }

    public function update(User $user, Review $review): bool {
        return $this->view($user, $review);
    }

    public function delete(User $user, Review $review): bool {
        return $this->view($user, $review);
    }

    private function isAuthor(User $user, Review $review): bool {
        return $review->reservation()
            ->where('user_id', $user->id)
            ->exists();
    }

    private function isVerified(User $user): bool {
        return ! is_null($user->verified_at);
    }
}

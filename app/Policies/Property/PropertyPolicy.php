<?php

namespace App\Policies\Property;

use App\Models\User\User;
use App\Models\Property\Property;

class PropertyPolicy {
    public function create(User $user): bool {
        return $this->isVerified($user);
    }

    public function update(User $user, Property $property): bool {
        return $this->isVerified($user) && $property->user_id === $user->id;
    }

    public function delete(User $user, Property $property): bool {
        return $this->isVerified($user) && $property->user_id === $user->id;
    }

    private function isVerified(User $user): bool {
        return !is_null($user->verified_at);
    }
}

<?php

namespace App\Repositories\Eloquent\Property;

use App\Models\User\User;
use App\Repositories\Contracts\Property\FavoritesRepositoryInterface;

class FavoriteRepository implements FavoritesRepositoryInterface {
    public function add($userId, $propertyId) {
        $user = User::findOrFail($userId);
        $user->favorites()->syncWithoutDetaching([$propertyId]);
    }

    public function remove($userId, $propertyId) {
        $user = User::findOrFail($userId);
        $user->favorites()->detach($propertyId);
    }

    public function exists($userId, $propertyId) {
        return User::findOrFail($userId)
            ->favorites()
            ->where('property_id', $propertyId)
            ->exists();
    }

    public function getUserFavorites($userId) {
        return User::findOrFail($userId)->favorites()->get();
    }
}

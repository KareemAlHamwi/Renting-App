<?php

namespace App\Repositories\Eloquent\Property;

use App\Models\User\User;
use App\Repositories\Contracts\Property\FavoritesRepositoryInterface;

class FavoritesRepository implements FavoritesRepositoryInterface {
    public function add($userId, $propertyId) {
        $user = User::findOrFail($userId);
        $user->favoriteProperties()->syncWithoutDetaching([$propertyId]);
    }

    public function remove($userId, $propertyId) {
        $user = User::findOrFail($userId);
        $user->favoriteProperties()->detach($propertyId);
    }

    public function exists($userId, $propertyId) {
        return User::findOrFail($userId)
            ->favoriteProperties()
            ->where('property_id', $propertyId)
            ->exists();
    }

    public function getUserFavorites($userId) {
        return User::findOrFail($userId)->favoriteProperties()->get();
    }
}

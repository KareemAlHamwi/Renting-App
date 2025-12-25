<?php

namespace App\Repositories\Eloquent\Property;

use App\Models\User\User;
use App\Repositories\Contracts\Property\FavoriteRepositoryInterface;

class FavoriteRepository implements FavoriteRepositoryInterface {
    public function getUserFavorites($userId) {
        return User::query()
        ->findOrFail($userId)
        ->favoriteProperties()
        ->with(['photos', 'governorate'])
        ->whereNotNull('properties.verified_at')
        ->get();
    }

    public function add($userId, $propertyId) {
        $user = User::findOrFail($userId);
        $user->favoriteProperties()->syncWithoutDetaching([$propertyId]);
    }

    public function exists($userId, $propertyId) {
        return User::findOrFail($userId)
            ->favoriteProperties()
            ->where('property_id', $propertyId)
            ->exists();
    }

    public function remove($userId, $propertyId) {
        $user = User::findOrFail($userId);
        $user->favoriteProperties()->detach($propertyId);
    }
}

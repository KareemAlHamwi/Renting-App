<?php

namespace App\Repositories\Eloquent\Property;

use App\Models\Property\Property;
use App\Models\User\User;
use App\Repositories\Contracts\Property\FavoriteRepositoryInterface;

class FavoriteRepository implements FavoriteRepositoryInterface {
    public function getUserFavorites(User $user) {
        return User::query()
            ->findOrFail($user->id)
            ->favoriteProperties()
            ->with(['photos', 'governorate'])
            ->whereNotNull('properties.verified_at')
            ->get();
    }

    public function getUserFavorite(User $user,Property $property) {
        return User::query()
            ->findOrFail($user->id)
            ->favoriteProperties()
            ->where('properties.id', $property->id)
            ->whereNotNull('properties.verified_at')
            ->with(['photos', 'governorate'])
            ->firstOrFail();
    }

    public function add(User $user,Property $property) {
        $user->favoriteProperties()->syncWithoutDetaching([$property->id]);
    }

    public function exists(User $user,Property $property) {
        return User::findOrFail($user->id)
            ->favoriteProperties()
            ->where('property_id', $property->id)
            ->exists();
    }

    public function remove(User $user, Property $property) {
        $user->favoriteProperties()->detach($property->id);
    }
}

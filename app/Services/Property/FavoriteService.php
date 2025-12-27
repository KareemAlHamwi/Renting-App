<?php

namespace App\Services\Property;

use App\Http\Resources\Property\PropertyResource;
use App\Models\User\User;
use App\Repositories\Contracts\Property\FavoriteRepositoryInterface;

class FavoriteService {
    private $favoriteRepository;

    public function __construct(FavoriteRepositoryInterface $favoriteRepository) {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function userFavorites($userId) {
        return $this->favoriteRepository->getUserFavorites($userId);
    }

    public function userFavorite(User $user, $propertyId) {
        return $this->favoriteRepository->getUserFavorite($user->id,$propertyId);
    }

    public function toggleFavorite($userId, $propertyId) {
        if ($this->favoriteRepository->exists($userId, $propertyId)) {
            $this->favoriteRepository->remove($userId, $propertyId);
            return ['message' => 'Property has been removed from your favorites'];
        }

        $this->favoriteRepository->add($userId, $propertyId);
        return ['message' => 'Property has been added to your favorites'];
    }
}

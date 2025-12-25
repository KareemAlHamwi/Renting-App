<?php

namespace App\Services\Property;

use App\Repositories\Contracts\Property\FavoriteRepositoryInterface;

class FavoriteService {
    private $favoriteRepository;

    public function __construct(FavoriteRepositoryInterface $favoriteRepository) {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function toggleFavorite($userId, $propertyId) {
        if ($this->favoriteRepository->exists($userId, $propertyId)) {
            $this->favoriteRepository->remove($userId, $propertyId);
            return ['message' => 'Property has been removed from your favorites'];
        }

        $this->favoriteRepository->add($userId, $propertyId);
        return ['message' => 'Property has been added to your favorites'];
    }

    public function getFavorites($userId) {
        return $this->favoriteRepository->getUserFavorites($userId);
    }
}

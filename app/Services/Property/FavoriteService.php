<?php

namespace App\Services\Property;

use App\Models\Property\Property;
use App\Models\User\User;
use App\Repositories\Contracts\Property\FavoriteRepositoryInterface;

class FavoriteService {
    private $favoriteRepository;

    public function __construct(FavoriteRepositoryInterface $favoriteRepository) {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function userFavorites(User $user) {
        return $this->favoriteRepository->getUserFavorites($user);
    }

    public function userFavorite(User $user,Property $property) {
        return $this->favoriteRepository->getUserFavorite($user,$property);
    }

    public function toggleFavorite(User $user,Property $property) {
        if ($this->favoriteRepository->exists($user, $property)) {
            $this->favoriteRepository->remove($user, $property);
            return ['message' => 'Property has been removed from your favorites'];
        }

        $this->favoriteRepository->add($user, $property);
        return ['message' => 'Property has been added to your favorites'];
    }
}

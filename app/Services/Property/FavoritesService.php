<?php

namespace App\Services\Property;

use App\Repositories\Contracts\Property\FavoritesRepositoryInterface;

class FavoriteService {
    protected $favoriteRepository;

    public function __construct(FavoritesRepositoryInterface $favoriteRepository) {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function toggleFavorite($userId, $propertyId) {
        if ($this->favoriteRepository->exists($userId, $propertyId)) {
            $this->favoriteRepository->remove($userId, $propertyId);
            return ['message' => 'تمت إزالة العقار من المفضلة'];
        }

        $this->favoriteRepository->add($userId, $propertyId);
        return ['message' => 'تمت إضافة العقار إلى المفضلة'];
    }

    public function getFavorites($userId) {
        return $this->favoriteRepository->getUserFavorites($userId);
    }
}

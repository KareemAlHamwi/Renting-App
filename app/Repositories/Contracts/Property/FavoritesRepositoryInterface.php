<?php

namespace App\Repositories\Contracts\Property;

interface FavoritesRepositoryInterface {
    public function add($userId, $propertyId);
    public function remove($userId, $propertyId);
    public function exists($userId, $propertyId);
    public function getUserFavorites($userId);
}

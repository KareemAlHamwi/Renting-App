<?php

namespace App\Repositories\Contracts\Property;

interface FavoriteRepositoryInterface {
    public function getUserFavorites($userId);
    public function getUserFavorite($userId,$propertyId);
    public function add($userId, $propertyId);
    public function exists($userId, $propertyId);
    public function remove($userId, $propertyId);
}

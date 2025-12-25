<?php

namespace App\Repositories\Contracts\Property;

interface FavoriteRepositoryInterface {
    public function getUserFavorites($userId);
    public function add($userId, $propertyId);
    public function exists($userId, $propertyId);
    public function remove($userId, $propertyId);
}

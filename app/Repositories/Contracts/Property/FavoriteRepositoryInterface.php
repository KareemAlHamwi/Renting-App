<?php

namespace App\Repositories\Contracts\Property;

use App\Models\Property\Property;
use App\Models\User\User;

interface FavoriteRepositoryInterface {
    public function getUserFavorites(User $user);
    public function getUserFavorite(User $user,Property $property);
    public function add(User $user,Property $property);
    public function exists(User $user,Property $property);
    public function remove(User $user,Property $property);
}

<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Http\Resources\Property\PropertyListResource;
use App\Http\Resources\Property\PropertyResource;
use App\Models\Property\Property;
use App\Services\Property\FavoriteService;
use Illuminate\Http\Request;

class FavoritesController extends Controller {
    private $favoriteService;

    public function __construct(
        FavoriteService $favoriteService
    ) {
        $this->favoriteService = $favoriteService;
    }

    public function userFavorites(Request $request) {
        $user = $request->user();

        $favorites = $this->favoriteService->userFavorites($user);

        return PropertyListResource::collection($favorites);
    }

    public function userFavorite(Request $request,Property $property) {
        $user = $request->user();

        $property = $this->favoriteService->userFavorite($user, $property);

        return new PropertyResource($property);
    }

    public function toggle(Request $request, Property $property) {
        $user = $request->user();

        $result = $this->favoriteService->toggleFavorite($user, $property);

        return $result;
    }
}

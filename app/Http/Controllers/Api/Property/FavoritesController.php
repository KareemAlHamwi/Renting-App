<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\ToggleFavoriteRequest;
use App\Http\Resources\Property\PropertyListResource;
use App\Http\Resources\Property\PropertyResource;
use App\Services\Property\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoritesController extends Controller {
    private $favoriteService;

    public function __construct(
        FavoriteService $favoriteService
    ) {
        $this->favoriteService = $favoriteService;
    }

    public function userFavorites(Request $request) {
        $userId = $request->user()->id;

        $favorites = $this->favoriteService->userFavorites($userId);

        return PropertyListResource::collection($favorites);
    }

    public function userFavorite(Request $request, $id) {
        $user = $request->user();

        $property = $this->favoriteService->userFavorite($user, $id);

        return new PropertyResource($property);
    }

    public function toggle(ToggleFavoriteRequest $request): JsonResponse {
        $userId = $request->user()->id;
        $propertyId = $request->validated('property_id');

        $result = $this->favoriteService->toggleFavorite($userId, $propertyId);

        return response()->json([
            'message' => $result,
        ], 200);
    }
}

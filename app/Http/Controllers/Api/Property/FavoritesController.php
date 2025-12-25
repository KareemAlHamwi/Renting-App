<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\ToggleFavoriteRequest;
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

    public function index(Request $request): JsonResponse {
        $userId = $request->user()->id;

        $favorites = $this->favoriteService->getFavorites($userId);

        return response()->json([
            'message' => $favorites,
        ], 200);
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

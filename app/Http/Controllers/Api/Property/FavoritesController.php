<?php

namespace App\Http\Controllers\Api\Property;

use App\Http\Controllers\Controller;
use App\Services\Property\FavoriteService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class FavoritesController extends Controller {
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService) {
        $this->favoriteService = $favoriteService;
    }

    public function toggle(Request $request) {
        $userId = Auth::id();
        $propertyId = $request->property_id;

        $result = $this->favoriteService->toggleFavorite($userId, $propertyId);

        return response()->json($result);
    }

    public function index() {
        $userId = Auth::id();
        return response()->json($this->favoriteService->getFavorites($userId));
    }
}

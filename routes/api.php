<?php

use App\Http\Controllers\Api\Property\FavoritesController;
use Illuminate\Support\Facades\Route;

// User (API)
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\UserController;

// Property (API)
use App\Http\Controllers\Api\Property\GovernorateController;
use App\Http\Controllers\Api\Property\PropertyController;
use App\Http\Controllers\Api\Property\PropertyPhotoController;
use App\Http\Controllers\Api\Reservation\ReservationController;
use App\Http\Controllers\Api\Reservation\ReviewController;

/*
|--------------------------------------------------------------------------
| Auth (Public)
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Public Read-Only Resources
|--------------------------------------------------------------------------
*/
Route::get('/users/{username}', [UserController::class, 'publicProfileByUsername']);

Route::prefix('governorates')->group(function () {
    Route::get('/', [GovernorateController::class, 'index']);
    Route::get('/{id}', [GovernorateController::class, 'findById']);
});

Route::prefix('properties')->group(function () {
    Route::get('/', [PropertyController::class, 'index']);
    Route::get('/{id}', [PropertyController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    */
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/logout-all', [AuthController::class, 'logoutAll']);

    /*
    |--------------------------------------------------------------------------
    | User (Authenticated User)
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->group(function () {
        Route::get('/my-profile', [UserController::class, 'myProfile']);
        Route::put('/update', [UserController::class, 'update']);
        Route::put('/phone', [UserController::class, 'changePhoneNumber']);
        Route::put('/password', [UserController::class, 'changePassword']);
        Route::delete('/delete', [UserController::class, 'deleteAccount']);
    });

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */
    Route::prefix('properties')->group(function () {
        Route::post('/', [PropertyController::class, 'store']);
        Route::put('/{property}', [PropertyController::class, 'update']);
        Route::delete('/{property}', [PropertyController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Property Photos
    |--------------------------------------------------------------------------
    */
    Route::prefix('properties/{propertyId}/photos')->group(function () {
        Route::post('/', [PropertyPhotoController::class, 'store']);
        Route::delete('/{id}', [PropertyPhotoController::class, 'destroy']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/favorites/toggle', [FavoritesController::class, 'toggle']);
        Route::get('/favorites', [FavoritesController::class, 'index']);
    });
});

Route::post('/reservations', [ReservationController::class, 'store']);
Route::post('/reservations/{id}/review', [ReservationController::class, 'addReview']);

Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/reviews/{id}', [ReviewController::class, 'show']);
Route::put('/reviews/{id}', [ReviewController::class, 'update']);
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

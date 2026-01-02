<?php

use Illuminate\Support\Facades\Route;

// User (API)
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\UserController;

// Property (API)
use App\Http\Controllers\Api\Property\GovernorateController;
use App\Http\Controllers\Api\Property\PropertyController;
use App\Http\Controllers\Api\Property\PropertyPhotoController;
use App\Http\Controllers\Api\Property\FavoritesController;

// Reservation (API)
use App\Http\Controllers\Api\Reservation\ReservationController;
use App\Http\Controllers\Api\Reservation\ReviewController;

/*
|--------------------------------------------------------------------------
| Auth (Public)
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Public Read-Only Resources
|--------------------------------------------------------------------------
*/
Route::prefix('users')->group(function () {
    Route::get('/{username}', [UserController::class, 'publicProfileByUsername']);
});

Route::prefix('governorates')->group(function () {
    Route::get('/', [GovernorateController::class, 'index']);
    Route::get('/{governorate}', [GovernorateController::class, 'findById']);
});

Route::prefix('properties')->group(function () {
    Route::get('/{property}', [PropertyController::class, 'show']);
    Route::get('/{property}/reserved-periods', [ReservationController::class, 'reservedPeriods']);
    Route::get('/{property}/reviews', [ReviewController::class, 'getAllPropertyReviews']);
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
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });

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
        Route::get('/properties', [PropertyController::class, 'userProperties']);
        Route::get('/properties/{property}', [PropertyController::class, 'userProperty']);
    });

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */
    Route::prefix('properties')->group(function () {
        Route::get('/', [PropertyController::class, 'index']);
        Route::post('/', [PropertyController::class, 'store']);
        Route::put('/{property}', [PropertyController::class, 'update']);
        Route::delete('/{property}', [PropertyController::class, 'destroy']);
        Route::get('/{property}/reservations', [ReservationController::class, 'landlordPropertyReservations']);
        Route::post('/{property}/reservations', [ReservationController::class, 'store']);

        Route::scopeBindings()->prefix('{property}/photos')->group(function () {
            Route::post('/', [PropertyPhotoController::class, 'store']);
            Route::delete('/{propertyPhoto}', [PropertyPhotoController::class, 'destroy']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Favorites
    |--------------------------------------------------------------------------
    */
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoritesController::class, 'userFavorites']);
        Route::get('/{property}', [FavoritesController::class, 'userFavorite']);
        Route::post('/{property}/toggle', [FavoritesController::class, 'toggle']);
    });

    /*
    |--------------------------------------------------------------------------
    | Reservations
    |--------------------------------------------------------------------------
    */
    Route::prefix('reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'tenantReservations']);
        Route::put('/{reservation}', [ReservationController::class, 'update']);
        Route::post('/{reservation}/approve', [ReservationController::class, 'approve']);
        Route::post('/{reservation}/cancel', [ReservationController::class, 'cancel']);
        Route::post('/{reservation}/review', [ReservationController::class, 'addReview']);
    });

    /*
    |--------------------------------------------------------------------------
    | Reviews
    |--------------------------------------------------------------------------
    */
    Route::prefix('reviews')->group(function () {
        Route::get('/{review}', [ReviewController::class, 'show']);
        Route::put('/{review}', [ReviewController::class, 'update']);
        Route::delete('/{review}', [ReviewController::class, 'destroy']);
    });
});

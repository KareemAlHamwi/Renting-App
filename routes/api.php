<?php

use Illuminate\Support\Facades\Route;

// User (API)
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\UserController;

// Property (API)
use App\Http\Controllers\Api\Property\GovernorateController;
use App\Http\Controllers\Api\Property\PropertyController;
use App\Http\Controllers\Api\Property\PropertyPhotoController;

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
Route::prefix('governorates')->group(function () {
    Route::get('/', [GovernorateController::class, 'index']);
    Route::get('{id}', [GovernorateController::class, 'findById']);
});

Route::prefix('properties')->group(function () {
    Route::get('/', [PropertyController::class, 'index']);
    Route::get('{id}', [PropertyController::class, 'show']);
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

    /*
    |--------------------------------------------------------------------------
    | User (Authenticated User)
    |--------------------------------------------------------------------------
    */
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'show']);
        Route::put('/', [UserController::class, 'update']);
        Route::put('phone', [UserController::class, 'changePhoneNumber']);
        Route::put('password', [UserController::class, 'changePassword']);
        Route::delete('/', [UserController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Governorates (Admin-like)
    |--------------------------------------------------------------------------
    */
    Route::prefix('governorates')->group(function () {
        Route::post('/', [GovernorateController::class, 'store']);
        Route::put('{id}', [GovernorateController::class, 'update']);
        Route::delete('{id}', [GovernorateController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */
    Route::prefix('properties')->group(function () {
        Route::post('/', [PropertyController::class, 'store']);
        Route::put('{id}', [PropertyController::class, 'update']);
        Route::delete('{id}', [PropertyController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | Property Photos
    |--------------------------------------------------------------------------
    */
    Route::prefix('properties/{propertyId}/photos')->group(function () {
        Route::post('/', [PropertyPhotoController::class, 'store']);
        Route::delete('{id}', [PropertyPhotoController::class, 'destroy']);
    });
});

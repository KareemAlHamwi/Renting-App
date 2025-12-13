<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\GovernorateController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyPhotoController;

Route::middleware(['auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user/show', [UserController::class, 'show']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/change-phone-number', [UserController::class, 'changePhoneNumber']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::delete('/user/destroy', [UserController::class, 'destroy']);
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);



Route::prefix('governorates')->group(function () {

    Route::get('/', [GovernorateController::class, 'index']);
    Route::post('/', [GovernorateController::class, 'store']);
    Route::get('/{id}', [GovernorateController::class, 'show']);
    Route::put('/{id}', [GovernorateController::class, 'update']);
    Route::delete('/{id}', [GovernorateController::class, 'destroy']);
});


Route::prefix('properties')->group(function () {

    Route::get('/', [PropertyController::class, 'index']);
    Route::post('/', [PropertyController::class, 'store']);
    Route::get('/{id}', [PropertyController::class, 'show']);
    Route::put('/{id}', [PropertyController::class, 'update']);
    Route::delete('/{id}', [PropertyController::class, 'destroy']);
});

Route::prefix('photos')->group(function () {

    Route::post('/', [PropertyPhotoController::class, 'store']);
    Route::delete('/{id}', [PropertyPhotoController::class, 'destroy']);
});

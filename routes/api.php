<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware(['auth:api'])->get('/user', function(Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/user/show', [UserController::class,'show']);
    Route::post('/user/update', [UserController::class,'update']);
    Route::post('/user/change-phone-number', [UserController::class,'changePhoneNumber']);
    Route::post('/user/change-password', [UserController::class,'changePassword']);
    Route::delete('/user/destroy', [UserController::class,'destroy']);
});

Route::post('/auth/register', [AuthController::class,'register']);
Route::post('/auth/login', [AuthController::class,'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);

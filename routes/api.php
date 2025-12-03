<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware(['auth:api'])->get('/user', function(Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function() {

});

// Route::post('/auth/register', [UserController::class,'register']);
// Route::post('/auth/login', [UserController::class,'login']);
// Route::post('/auth/logout', [UserController::class,'logout']);

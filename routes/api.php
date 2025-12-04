<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;



Route::middleware(['auth:api'])->get('/user', function(Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function() {

});

Route::post('/auth/store', [AuthController::class,'store']);

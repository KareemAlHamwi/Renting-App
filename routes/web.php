<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyPhotoController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AdminController::class, 'create'])->name('login');
Route::post('/login', [AdminController::class, 'store']);


Route::get('/', [DashboardController::class, 'create'])->middleware('auth');
Route::get('/users', [UserController::class, 'index'])->middleware('auth');
Route::get('/users/{user}', [DashboardController::class, 'show'])->middleware('auth');
Route::post('/users/{user}/verify', [UserController::class, 'verify'])->middleware('auth');
// Route::get('/properties', [UserController::class, 'create'])->middleware('auth');
// Route::get('/reservations', [UserController::class, 'create'])->middleware('auth');
// Route::get('/governorates', [UserController::class, 'create'])->middleware('auth');
// Route::get('/settings', [UserController::class, 'create'])->middleware('auth');
Route::post('/logout', [DashboardController::class, 'destroy'])->name('logout');

//Route::get('find/{id}', [GovernorateController::class, 'findById']);

Route::prefix('governorates')->group(function () {

    Route::get('/', [GovernorateController::class, 'index']);
    Route::post('store/', [GovernorateController::class, 'store']);
    Route::get('find/{id}', [GovernorateController::class, 'findById']);
    Route::put('update/{id}', [GovernorateController::class, 'update']);
    Route::delete('destroy/{id}', [GovernorateController::class, 'destroy']);
});

//Route::get('index/{id}', [PropertyController::class, 'index']);
Route::prefix('properties')->group(function () {

    Route::get('/', [PropertyController::class, 'index']);
    Route::post('store/', [PropertyController::class, 'store']);
    Route::get('show/{id}', [PropertyController::class, 'show']);
    Route::put('update/{id}', [PropertyController::class, 'update']);
    Route::delete('destroy/{id}', [PropertyController::class, 'destroy']);
});

Route::prefix('photos')->group(function () {

    Route::post('store/', [PropertyPhotoController::class, 'store']);
    Route::delete('destroy/{id}', [PropertyPhotoController::class, 'destroy']);
});

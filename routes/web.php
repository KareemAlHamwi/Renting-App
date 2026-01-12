<?php

use App\Http\Controllers\Web\PropertyController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ReservationController;
use App\Http\Controllers\Web\UsersController;

Route::prefix('login')->group(function () {
    Route::get('/', [AdminController::class, 'create'])->name('login');
    Route::post('/', [AdminController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'create']);
    Route::post('/logout', [DashboardController::class, 'destroy'])->name('logout');

    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'index']);
        Route::get('/{user}', [DashboardController::class, 'showUser']);
        Route::post('/{user}/toggle', [UsersController::class, 'toggleActivation']);
        Route::post('/{user}/verify', [UsersController::class, 'verify']);
    });

    Route::prefix('properties')->group(function () {
        Route::get('/', [PropertyController::class, 'index']);
        Route::get('/{property}', [DashboardController::class, 'showProperty']);
        Route::post('/{property}/toggle', [PropertyController::class, 'togglePublishing']);
        Route::post('/{property}/verify', [PropertyController::class, 'verify']);
    });

    Route::prefix('reservations')->group(function () {
        Route::get('/', [ReservationController::class, 'index']);
        Route::get('/{reservation}', [DashboardController::class, 'showReservation']);
        Route::post('/{reservation}/cancel', [ReservationController::class, 'cancel']);
    });
});

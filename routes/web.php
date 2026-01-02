<?php

use App\Http\Controllers\Web\PropertyController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ReservationController;
use App\Http\Controllers\Web\UsersController;

Route::get('/login', [AdminController::class, 'create'])->name('login');
Route::post('/login', [AdminController::class, 'store']);

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'create']);

    Route::get('/users', [UsersController::class, 'index']);
    Route::get('/users/{user}', [DashboardController::class, 'showUser']);
    Route::post('/users/{user}/verify', [UsersController::class, 'verify']);

    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/properties/{property}', [DashboardController::class, 'showProperty']);
    Route::post('/properties/{property}/verify', [PropertyController::class, 'verify']);

    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/{reservation}', [DashboardController::class, 'showReservation']);
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);

    Route::post('/logout', [DashboardController::class, 'destroy'])->name('logout');
});

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UsersController;

Route::get('/login', [AdminController::class, 'create'])->name('login');
Route::post('/login', [AdminController::class, 'store']);

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'create']);

    Route::get('/users', [UsersController::class, 'index']);
    Route::get('/users/{user}', [DashboardController::class, 'show']);
    Route::post('/users/{user}/verify', [UsersController::class, 'verify']);

    Route::post('/logout', [DashboardController::class, 'destroy'])->name('logout');
});

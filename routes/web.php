<?php

use Illuminate\Support\Facades\Route;

// Web controllers
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\DashboardController;

// API controller reused in web (intentional)
use App\Http\Controllers\Api\User\UserController;

Route::get('/login', [AdminController::class, 'create'])->name('login');
Route::post('/login', [AdminController::class, 'store']);

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'create']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [DashboardController::class, 'show']);
    Route::post('/users/{user}/verify', [UserController::class, 'verify']);

    Route::post('/logout', [DashboardController::class, 'destroy'])->name('logout');
});

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\DashboardController;
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

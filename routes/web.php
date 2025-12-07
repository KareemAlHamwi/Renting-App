<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AdminController::class, 'create'])->name('login');
Route::post('/login', [AdminController::class, 'store']);

Route::get('/', [DashboardController::class, 'create'])->middleware('auth');
Route::post('/logout', [DashboardController::class, 'destroy'])->name('logout');

<?php

use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::post('/login',[AdminController::class,'login']);

Route::post('/dashboard', function () {
    // handle login logic here
    return view('dashboard');
});

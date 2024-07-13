<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [UserController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

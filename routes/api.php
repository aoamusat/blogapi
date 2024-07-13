<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Payment\StripeController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::post('/stripe/webhook', [StripeController::class, 'stripeWebhook']);
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [UserController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // POST Routes
        Route::controller(PostController::class)->group(function () {
            Route::get('/posts', 'index');
            Route::post('/posts', 'store');
            Route::put('/posts/{id}', 'update');
            Route::get('/posts/{id}', 'show');
            Route::delete('/posts/{id}', 'destroy');
        });
        Route::controller(CommentController::class)->group(function () {
            Route::get('/posts/{postId}/comments', 'getComments');
            Route::post('/posts/{postId}/comments', 'createComment');
        });
    });
});

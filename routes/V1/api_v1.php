<?php

use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;

Route::prefix('v1')->group(function (): void {
  Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

  Route::middleware('jwt')->group(function (): void {
    Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');

    Route::prefix('users')->group(function (): void {
      Route::post('/profile', [UserController::class, 'uploadProfile'])->name('users.profile');
      Route::get('/top-views', [UserController::class, 'topViewedUsers'])->name('users.top_views');
    });

    Route::prefix('posts')->group(function (): void {
      Route::get('/', [PostController::class, 'index'])->name('posts.index');
      Route::get('/{id}', [PostController::class, 'show'])->name('posts.show');
    });
  });
});



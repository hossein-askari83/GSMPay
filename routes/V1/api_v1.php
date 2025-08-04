<?php

use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;

Route::prefix('v1')->group(function (): void {
  Route::post('/auth/login', [AuthController::class, 'login']);

  Route::middleware('jwt')->group(function () {
    Route::prefix('user')->group(function (): void {
      Route::post('/profile', [UserController::class, 'uploadProfile']);
    });

    Route::prefix('post')->group(function (): void {
      Route::get('/', [PostController::class, 'index']);
    });
  });
});



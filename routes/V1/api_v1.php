<?php

use App\Http\Controllers\API\V1\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;

Route::prefix('v1')->group(function (): void {
  Route::post('/auth/login', [AuthController::class, 'login']);

  Route::middleware('jwt')->group(function () {
    Route::post('/user/profile', [UserController::class, 'uploadProfile']);
  });
});



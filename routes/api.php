<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LandingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(callback: function (): void {
    Route::post(uri: '/register', action: [AuthController::class, 'register']);
    Route::post(uri: '/login', action: [AuthController::class, 'login'])->name(name: 'login');
    Route::post(uri: '/forgot-password', action: [AuthController::class, 'forgotPassword']);
    Route::post(uri: '/reset-password', action: [AuthController::class, 'resetPassword'])->name(name: 'password.reset');
    Route::get(uri: '/user', action: fn (Request $request): mixed => $request->user())->middleware(['auth:sanctum']);
    Route::middleware(['auth:sanctum'])->post(uri: '/logout', action: [AuthController::class, 'logout']);
});

Route::prefix('landing')->group(callback: function (): void {
    Route::get(uri: '/', action: [LandingController::class, 'index']);
});

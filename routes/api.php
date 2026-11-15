<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LoyaltyCardApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public mobile API
Route::post('/login', [AuthController::class, 'login']);
Route::get('/cards/{token}', [LoyaltyCardApiController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user()->load(['shop', 'branch', 'roles']);
    });

    Route::get('/cards', [LoyaltyCardApiController::class, 'index']);
    Route::post('/cards/{token}/stamp', [LoyaltyCardApiController::class, 'addStamp']);
    Route::post('/cards/{token}/redeem', [LoyaltyCardApiController::class, 'redeem']);
});

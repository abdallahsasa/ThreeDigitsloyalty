<?php

use App\Http\Controllers\Api\AppleWalletWebServiceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LoyaltyCardApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Apple Wallet PassKit Web Service (used by iOS Wallet for live updates & push notifications)
Route::prefix('apple-wallet/v1')->group(function () {
    Route::post('/devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}/{serialNumber}', [AppleWalletWebServiceController::class, 'register']);
    Route::delete('/devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}/{serialNumber}', [AppleWalletWebServiceController::class, 'unregister']);
    Route::get('/devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}', [AppleWalletWebServiceController::class, 'listUpdates']);
    Route::get('/passes/{passTypeIdentifier}/{serialNumber}', [AppleWalletWebServiceController::class, 'latestPass']);
    Route::post('/log', [AppleWalletWebServiceController::class, 'logErrors']);
});

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

<?php

use App\Http\Controllers\LoyaltyCardController;
use App\Http\Controllers\MerchantRegisterController;
use App\Http\Controllers\ShopJoinController;
use App\Services\FunnelTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $plans = \App\Models\Plan::where('status', 'active')->orderBy('price', 'asc')->get();

    return view('welcome', compact('plans'));
})->name('home');

Route::get('/register', [MerchantRegisterController::class, 'show'])->name('merchant.register');
Route::post('/register', [MerchantRegisterController::class, 'store'])->name('merchant.register.store');

Route::post('/funnel/track', function (Request $request, FunnelTracker $tracker) {
    $validated = $request->validate([
        'event' => ['required', 'string', 'in:primary_cta_clicked,demo_started,demo_completed'],
        'properties' => ['nullable', 'array'],
    ]);

    $tracker->track($validated['event'], $validated['properties'] ?? []);

    return response()->noContent();
})->name('funnel.track');

Route::get('/join/{shop}', [ShopJoinController::class, 'show'])->name('shop.join');
Route::post('/join/{shop}', [ShopJoinController::class, 'store'])->name('shop.join.store');
Route::get('/join/{shop}/poster', [ShopJoinController::class, 'poster'])->name('shop.join.poster');

Route::get('/card/{token}', [LoyaltyCardController::class, 'show'])->name('loyalty-card.show');
Route::get('/card/{token}/manifest.json', [LoyaltyCardController::class, 'manifest'])->name('loyalty-card.manifest');
Route::get('/card/{token}/wallet/apple', [LoyaltyCardController::class, 'downloadApplePass'])->name('loyalty-card.apple-pass');
Route::get('/card/{token}/wallet/google', [LoyaltyCardController::class, 'redirectToGoogleWallet'])->name('loyalty-card.google-pass');
Route::get('/card/{token}/wallet/apple-payload', [LoyaltyCardController::class, 'applePassPayload'])->name('loyalty-card.apple-payload');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }

    return redirect()->back();
})->name('lang.switch');

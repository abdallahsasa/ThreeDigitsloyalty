<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoyaltyCardController;
use App\Http\Controllers\ShopJoinController;

Route::get('/', function () {
    $plans = \App\Models\Plan::where('status', 'active')->orderBy('price', 'asc')->get();
    $clients = \App\Models\Shop::where('status', 'active')->orderBy('id', 'asc')->take(12)->get();
    return view('welcome', compact('plans', 'clients'));
});

Route::get('/join/{shop}', [ShopJoinController::class, 'show'])->name('shop.join');
Route::post('/join/{shop}', [ShopJoinController::class, 'store'])->name('shop.join.store');

Route::get('/card/{token}', [LoyaltyCardController::class, 'show'])->name('loyalty-card.show');
Route::get('/card/{token}/manifest.json', [LoyaltyCardController::class, 'manifest'])->name('loyalty-card.manifest');
Route::get('/card/{token}/wallet/apple-payload', [LoyaltyCardController::class, 'applePassPayload'])->name('loyalty-card.apple-payload');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

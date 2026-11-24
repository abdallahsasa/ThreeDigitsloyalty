<?php

use App\Http\Controllers\LoyaltyCardController;
use App\Http\Controllers\MerchantRegisterController;
use App\Http\Controllers\ShopJoinController;
use App\Services\FunnelTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$renderHome = function (?string $forcedLocale = null) {
    if ($forcedLocale && in_array($forcedLocale, ['en', 'ar'], true)) {
        app()->setLocale($forcedLocale);
        session(['locale' => $forcedLocale]);
    }

    $plans = \App\Models\Plan::where('status', 'active')->orderBy('price', 'asc')->get();

    return view('welcome', compact('plans'));
};

Route::get('/', fn () => $renderHome())->name('home');
Route::get('/ar', fn () => $renderHome('ar'))->name('home.ar');
Route::get('/en', fn () => $renderHome('en'))->name('home.en');

$renderLegal = function (string $view, ?string $forcedLocale = null) {
    if ($forcedLocale && in_array($forcedLocale, ['en', 'ar'], true)) {
        app()->setLocale($forcedLocale);
        session(['locale' => $forcedLocale]);
    }

    return view($view);
};

Route::get('/privacy', fn () => $renderLegal('legal.privacy'))->name('legal.privacy');
Route::get('/ar/privacy', fn () => $renderLegal('legal.privacy', 'ar'))->name('legal.privacy.ar');
Route::get('/en/privacy', fn () => $renderLegal('legal.privacy', 'en'))->name('legal.privacy.en');

Route::get('/terms', fn () => $renderLegal('legal.terms'))->name('legal.terms');
Route::get('/ar/terms', fn () => $renderLegal('legal.terms', 'ar'))->name('legal.terms.ar');
Route::get('/en/terms', fn () => $renderLegal('legal.terms', 'en'))->name('legal.terms.en');

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

Route::get('/lang/{locale}', function (Request $request, $locale) {
    if (in_array($locale, ['en', 'ar'], true)) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }

    $targetLocale = in_array($locale, ['en', 'ar'], true) ? $locale : 'ar';
    $previous = url()->previous();
    $parsed = parse_url($previous ?: '');
    $path = rtrim($parsed['path'] ?? '', '/');
    $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';

    // Preserve explicit plan query param if passed directly on the switcher link
    if ($request->filled('plan') && !str_contains($query, 'plan=')) {
        $sep = $query === '' ? '?' : '&';
        $query .= $sep . 'plan=' . urlencode((string) $request->query('plan'));
    }

    if (in_array($path, ['', '/ar', '/en'], true)) {
        return redirect("/{$targetLocale}{$query}");
    }

    if (in_array($path, ['/privacy', '/ar/privacy', '/en/privacy'], true)) {
        return redirect("/{$targetLocale}/privacy");
    }

    if (in_array($path, ['/terms', '/ar/terms', '/en/terms'], true)) {
        return redirect("/{$targetLocale}/terms");
    }

    if ($path === '/register' && $request->filled('plan')) {
        return redirect()->route('merchant.register', ['plan' => $request->query('plan')]);
    }

    return redirect()->back();
})->name('lang.switch');

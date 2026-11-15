<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoyaltyCard;
use App\Services\WalletPassService;

class LoyaltyCardController extends Controller
{
    public function show($token, WalletPassService $walletService)
    {
        $card = LoyaltyCard::withoutGlobalScopes()
            ->with([
                'customer',
                'shop',
                'campaign' => fn ($query) => $query->withoutGlobalScopes(),
            ])
            ->where('token', $token)
            ->firstOrFail();

        $qrUrl = route('loyalty-card.show', $token);
        $appleConfigured = $walletService->isAppleWalletConfigured();
        $googleConfigured = $walletService->isGoogleWalletConfigured();

        return view('loyalty-card.show', compact('card', 'qrUrl', 'appleConfigured', 'googleConfigured'));
    }

    public function manifest(string $token, WalletPassService $walletService)
    {
        $card = LoyaltyCard::withoutGlobalScopes()
            ->with([
                'customer',
                'shop',
                'campaign' => fn ($query) => $query->withoutGlobalScopes(),
            ])
            ->where('token', $token)
            ->firstOrFail();

        $cardUrl = route('loyalty-card.show', $token);

        return response()
            ->json($walletService->buildWebAppManifest($card, $cardUrl))
            ->header('Content-Type', 'application/manifest+json');
    }

    public function applePassPayload(string $token, WalletPassService $walletService)
    {
        $card = LoyaltyCard::withoutGlobalScopes()
            ->with([
                'customer',
                'shop',
                'campaign' => fn ($query) => $query->withoutGlobalScopes(),
            ])
            ->where('token', $token)
            ->firstOrFail();

        $qrUrl = route('loyalty-card.show', $token);

        return response()->json($walletService->buildApplePassPayload($card, $qrUrl));
    }
}

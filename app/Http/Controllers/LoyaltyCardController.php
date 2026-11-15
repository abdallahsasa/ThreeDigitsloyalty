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
        $card = $this->findCardByToken($token);
        $qrUrl = route('loyalty-card.show', $token);

        return response()->json($walletService->buildApplePassPayload($card, $qrUrl));
    }

    public function downloadApplePass(string $token, WalletPassService $walletService)
    {
        $card = $this->findCardByToken($token);
        $qrUrl = route('loyalty-card.show', $token);
        $pkpassBinary = $walletService->generateApplePkpass($card, $qrUrl);

        return response($pkpassBinary, 200, [
            'Content-Type' => 'application/vnd.apple.pkpass',
            'Content-Disposition' => 'attachment; filename="threedigits-' . $card->token . '.pkpass"',
            'Content-Length' => strlen($pkpassBinary),
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    public function redirectToGoogleWallet(string $token, WalletPassService $walletService)
    {
        $card = $this->findCardByToken($token);
        $qrUrl = route('loyalty-card.show', $token);
        $saveUrl = $walletService->generateGoogleWalletSaveUrl($card, $qrUrl);

        return redirect()->away($saveUrl);
    }

    protected function findCardByToken(string $token): LoyaltyCard
    {
        return LoyaltyCard::withoutGlobalScopes()
            ->with([
                'customer',
                'shop',
                'campaign' => fn ($query) => $query->withoutGlobalScopes(),
            ])
            ->where('token', $token)
            ->firstOrFail();
    }
}

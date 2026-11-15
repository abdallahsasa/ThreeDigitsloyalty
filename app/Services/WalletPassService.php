<?php

namespace App\Services;

use App\Models\LoyaltyCard;
use ZipArchive;

class WalletPassService
{
    /**
     * Build the Apple Wallet pass.json payload for a LoyaltyCard.
     */
    public function buildApplePassPayload(LoyaltyCard $card, string $qrUrl): array
    {
        $required = max(1, (int) ($card->campaign->required_purchases_count ?? 1));

        return [
            'formatVersion' => 1,
            'passTypeIdentifier' => config('services.wallet.apple_pass_type_id', 'pass.com.threedigits.loyalty'),
            'serialNumber' => $card->token,
            'teamIdentifier' => config('services.wallet.apple_team_id', 'THREEDIGIT'),
            'organizationName' => $card->shop->name,
            'description' => $card->campaign->name,
            'logoText' => $card->shop->name,
            'foregroundColor' => 'rgb(248, 250, 252)',
            'backgroundColor' => 'rgb(15, 23, 42)',
            'labelColor' => 'rgb(212, 175, 55)',
            'barcode' => [
                'message' => $card->token,
                'format' => 'PKBarcodeFormatQR',
                'messageEncoding' => 'iso-8859-1',
                'altText' => $card->customer->phone,
            ],
            'barcodes' => [
                [
                    'message' => $card->token,
                    'format' => 'PKBarcodeFormatQR',
                    'messageEncoding' => 'iso-8859-1',
                    'altText' => $card->customer->phone,
                ],
            ],
            'storeCard' => [
                'headerFields' => [
                    [
                        'key' => 'stamps',
                        'label' => 'STAMPS',
                        'value' => "{$card->current_stamps_count} / {$required}",
                    ],
                ],
                'primaryFields' => [
                    [
                        'key' => 'campaign',
                        'label' => 'CAMPAIGN',
                        'value' => $card->campaign->name,
                    ],
                ],
                'secondaryFields' => [
                    [
                        'key' => 'customer',
                        'label' => 'MEMBER',
                        'value' => $card->customer->name,
                    ],
                    [
                        'key' => 'reward',
                        'label' => 'REWARD',
                        'value' => $card->campaign->reward_name ?? 'Free Reward',
                    ],
                ],
                'backFields' => [
                    [
                        'key' => 'pass_url',
                        'label' => 'Digital Pass URL',
                        'value' => $qrUrl,
                    ],
                ],
            ],
        ];
    }

    /**
     * Check whether Apple Wallet signing certificates are configured.
     */
    public function isAppleWalletConfigured(): bool
    {
        $certPath = config('services.wallet.apple_cert_path');
        $wwdrPath = config('services.wallet.apple_wwdr_path');

        return !empty($certPath) && is_file($certPath) && !empty($wwdrPath) && is_file($wwdrPath);
    }

    /**
     * Check whether Google Wallet issuer credentials are configured.
     */
    public function isGoogleWalletConfigured(): bool
    {
        return !empty(config('services.wallet.google_issuer_id'))
            && !empty(config('services.wallet.google_service_account_json'));
    }

    /**
     * Build the dynamic PWA Web App Manifest so iOS/Android can install the card as a native-like pass.
     */
    public function buildWebAppManifest(LoyaltyCard $card, string $cardUrl): array
    {
        return [
            'id' => "threedigits-pass-{$card->token}",
            'name' => "{$card->shop->name} — Loyalty Pass",
            'short_name' => $card->shop->name,
            'description' => "{$card->campaign->name} ({$card->current_stamps_count}/{$card->campaign->required_purchases_count})",
            'start_url' => $cardUrl,
            'scope' => $cardUrl,
            'display' => 'standalone',
            'orientation' => 'portrait',
            'background_color' => '#0b1120',
            'theme_color' => '#4f46e5',
            'icons' => [
                [
                    'src' => asset('images/logo-icon.png'),
                    'sizes' => '342x242',
                    'type' => 'image/png',
                ],
            ],
        ];
    }
}

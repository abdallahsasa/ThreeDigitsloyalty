<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\LoyaltyCard;
use App\Models\Shop;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class WalletPassService
{
    /**
     * Build the Apple Wallet pass.json payload for a LoyaltyCard.
     */
    public function buildApplePassPayload(LoyaltyCard $card, string $qrUrl): array
    {
        $required = max(1, (int) ($card->campaign->required_purchases_count ?? 1));
        $statusText = match ($card->reward_status) {
            'ready' => 'REWARD READY',
            'redeemed' => 'REDEEMED',
            'expired' => 'EXPIRED',
            default => 'COLLECTING',
        };

        $webServiceBase = rtrim((string) config('app.url', 'https://loylty.threedigitsoftware.com'), '/');

        $backFields = [
            [
                'key' => 'pass_url',
                'label' => 'Digital Pass URL',
                'value' => $qrUrl,
            ],
            [
                'key' => 'powered_by',
                'label' => 'Powered By',
                'value' => 'ThreeDigits Loyalty',
            ],
        ];

        if (!empty($card->wallet_message)) {
            array_unshift($backFields, [
                'key' => 'latest_message',
                'label' => $card->wallet_message_title ?: 'Notification',
                'value' => $card->wallet_message,
                'changeMessage' => '%@',
            ]);
        }

        $payload = [
            'formatVersion' => 1,
            'passTypeIdentifier' => config('services.wallet.apple_pass_type_id', 'pass.com.threedigitsoftware.loyalty'),
            'serialNumber' => $card->token,
            'teamIdentifier' => config('services.wallet.apple_team_id', 'THREEDIGIT'),
            'webServiceURL' => "{$webServiceBase}/api/apple-wallet",
            'authenticationToken' => str_pad((string) $card->token, 16, '0'),
            'organizationName' => $card->shop->name,
            'description' => $card->campaign->name ?? ($card->shop->name . ' Loyalty Card'),
            'logoText' => $card->shop->name,
            'foregroundColor' => 'rgb(248, 250, 252)',
            'backgroundColor' => 'rgb(10, 15, 44)',
            'labelColor' => 'rgb(196, 181, 253)',
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
                'primaryFields' => [],
                'secondaryFields' => [
                    [
                        'key' => 'customer',
                        'label' => 'MEMBER',
                        'value' => $card->customer->name,
                    ],
                    [
                        'key' => 'campaign',
                        'label' => 'PROGRAM',
                        'value' => $card->campaign->name ?? 'VIP Stamp Card',
                    ],
                ],
                'auxiliaryFields' => [
                    [
                        'key' => 'reward',
                        'label' => 'REWARD',
                        'value' => $card->campaign->reward_name ?? 'Free Reward',
                    ],
                    [
                        'key' => 'status',
                        'label' => 'STATUS',
                        'value' => $statusText,
                    ],
                ],
                'backFields' => $backFields,
            ],
        ];

        $locations = $this->resolveShopLocations($card);
        if (!empty($locations)) {
            $payload['locations'] = $locations;
            $payload['maxDistance'] = 150;
        }

        return $payload;
    }

    /**
     * Check whether official Apple Wallet signing certificates are configured on disk or via base64 env.
     */
    public function isAppleWalletConfigured(): bool
    {
        if (!empty(config('services.wallet.apple_cert_base64'))) {
            return true;
        }

        $certPath = $this->resolveExistingFilePath(
            config('services.wallet.apple_cert_path'),
            storage_path('app/wallet/apple-pass.p12')
        );

        return $certPath !== null;
    }

    /**
     * Check whether Google Wallet issuer credentials are configured.
     */
    public function isGoogleWalletConfigured(): bool
    {
        $issuerId = config('services.wallet.google_issuer_id');
        if (empty($issuerId)) {
            return false;
        }

        $saBase64 = (string) config('services.wallet.google_service_account_base64', '');
        if ($saBase64 !== '') {
            $decoded = json_decode((string) base64_decode($saBase64, true), true);
            if (is_array($decoded) && !empty($decoded['client_email']) && !empty($decoded['private_key'])) {
                return true;
            }
        }

        $saConfig = config('services.wallet.google_service_account_json');
        if (empty($saConfig)) {
            return false;
        }

        if ($this->resolveExistingFilePath($saConfig, storage_path('app/wallet/google-service-account.json')) !== null) {
            return true;
        }

        $decoded = json_decode($saConfig, true);

        return is_array($decoded) && !empty($decoded['client_email']) && !empty($decoded['private_key']);
    }

    /**
     * Generate a complete, PKCS#7-signed Apple Wallet .pkpass archive and return its raw binary bytes.
     */
    public function generateApplePkpass(LoyaltyCard $card, string $qrUrl): string
    {
        $workDir = storage_path('app/wallet/tmp/' . Str::uuid());
        File::ensureDirectoryExists($workDir);

        try {
            // 1. Write pass.json
            $passJson = json_encode(
                $this->buildApplePassPayload($card, $qrUrl),
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
            );
            file_put_contents("{$workDir}/pass.json", $passJson);

            // 2. Write required icon, logo, and visual stamp-card strip PNG assets
            $sourceImage = $this->resolveShopLogoFilePath($card);
            $this->writeResizedPng($sourceImage, "{$workDir}/icon.png", 29, 29);
            $this->writeResizedPng($sourceImage, "{$workDir}/icon@2x.png", 58, 58);
            $this->writeResizedPng($sourceImage, "{$workDir}/icon@3x.png", 87, 87);
            $this->writeResizedPng($sourceImage, "{$workDir}/logo.png", 50, 50);
            $this->writeResizedPng($sourceImage, "{$workDir}/logo@2x.png", 100, 100);
            $this->writeResizedPng($sourceImage, "{$workDir}/logo@3x.png", 150, 150);
            $this->writeStampStripPng($card, "{$workDir}/strip.png", 375, 123);
            $this->writeStampStripPng($card, "{$workDir}/strip@2x.png", 750, 246);
            $this->writeStampStripPng($card, "{$workDir}/strip@3x.png", 1125, 369);

            // 3. Build manifest.json with SHA-1 hashes of all bundle files
            $bundleFiles = [
                'pass.json',
                'icon.png',
                'icon@2x.png',
                'icon@3x.png',
                'logo.png',
                'logo@2x.png',
                'logo@3x.png',
                'strip.png',
                'strip@2x.png',
                'strip@3x.png',
            ];

            $manifest = [];
            foreach ($bundleFiles as $fileName) {
                $manifest[$fileName] = sha1_file("{$workDir}/{$fileName}");
            }

            $manifestPath = "{$workDir}/manifest.json";
            file_put_contents($manifestPath, json_encode($manifest, JSON_UNESCAPED_SLASHES));

            // 4. Sign manifest.json into a detached DER PKCS#7 signature file
            $signaturePath = "{$workDir}/signature";
            $this->signManifest($manifestPath, $signaturePath);

            // 5. Package everything into a .pkpass zip archive
            $pkpassPath = "{$workDir}/pass.pkpass";
            $zip = new ZipArchive();
            if ($zip->open($pkpassPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new RuntimeException('Unable to create .pkpass ZIP archive.');
            }

            foreach (array_merge($bundleFiles, ['manifest.json', 'signature']) as $fileName) {
                $zip->addFile("{$workDir}/{$fileName}", $fileName);
            }
            $zip->close();

            return file_get_contents($pkpassPath);
        } finally {
            File::deleteDirectory($workDir);
        }
    }

    /**
     * Build the Google Wallet "Save to Google Wallet" URL (https://pay.google.com/gp/v/save/{jwt})
     * signed with RS256.
     */
    public function generateGoogleWalletSaveUrl(LoyaltyCard $card, string $qrUrl): string
    {
        $issuerId = config('services.wallet.google_issuer_id') ?: '3388000000022222222';
        $classSuffix = preg_replace('/[^a-zA-Z0-9_.-]/', '_', config('services.wallet.google_class_suffix', 'threedigits_loyalty_pass'));
        $classId = "{$issuerId}.{$classSuffix}_{$card->shop_id}";
        $objectId = "{$issuerId}.card_{$card->id}_" . preg_replace('/[^a-zA-Z0-9_.-]/', '', $card->token);
        $required = max(1, (int) ($card->campaign->required_purchases_count ?? 1));

        [$clientEmail, $privateKeyPem] = $this->resolveGoogleServiceAccountCredentials();

        $logoUrl = $card->shop->logo_url ?: asset('images/logo-icon.png');
        if (!str_starts_with($logoUrl, 'https://')) {
            $logoUrl = 'https://loylty.threedigitsoftware.com/images/logo-icon.png';
        }

        // Ensure the LoyaltyClass exists on Google Wallet REST API before generating the Save JWT
        if ($this->isGoogleWalletConfigured() && !app()->runningUnitTests()) {
            $this->ensureGoogleLoyaltyClassExists($clientEmail, $privateKeyPem, $classId, $card, $logoUrl);
        }

        $loyaltyObject = [
            'id' => $objectId,
            'classId' => $classId,
            'state' => 'ACTIVE',
            'accountId' => $card->customer->phone,
            'accountName' => $card->customer->name,
            'loyaltyPoints' => [
                'label' => 'Stamps',
                'balance' => [
                    'string' => "{$card->current_stamps_count} / {$required}",
                ],
            ],
            'barcode' => [
                'type' => 'QR_CODE',
                'value' => $card->token,
                'alternateText' => $card->customer->phone,
            ],
            'textModulesData' => [
                [
                    'header' => 'SHOP',
                    'body' => $card->shop->name,
                    'id' => 'shop_name',
                ],
                [
                    'header' => 'REWARD',
                    'body' => $card->campaign->reward_name ?? 'Free Reward',
                    'id' => 'reward_name',
                ],
            ],
            'linksModuleData' => [
                'uris' => [
                    [
                        'uri' => str_starts_with($qrUrl, 'https://') ? $qrUrl : 'https://loylty.threedigitsoftware.com/card/' . $card->token,
                        'description' => 'Open Live Pass',
                        'id' => 'pass_url',
                    ],
                ],
            ],
        ];

        $geoLocations = $this->resolveGoogleLocations($card);
        if (!empty($geoLocations)) {
            $loyaltyObject['locations'] = $geoLocations;
        }

        $claims = [
            'iss' => $clientEmail,
            'aud' => 'google',
            'typ' => 'savetowallet',
            'iat' => time(),
            'origins' => [],
            'payload' => [
                'loyaltyObjects' => [$loyaltyObject],
            ],
        ];

        $jwt = $this->signJwtRs256($claims, $privateKeyPem);

        return "https://pay.google.com/gp/v/save/{$jwt}";
    }

    /**
     * Send a live Wallet update + push notification to both Apple Wallet (APNs) and Google Wallet (addMessage).
     *
     * @return array{apple_sent: int, apple_failed: int, google_sent: bool}
     */
    public function sendPassNotification(LoyaltyCard $card, ?string $title = null, ?string $message = null): array
    {
        if ($message !== null && trim($message) !== '') {
            $card->forceFill([
                'wallet_message_title' => $title ?: $card->shop->name,
                'wallet_message' => trim($message),
                'wallet_message_updated_at' => now(),
                'updated_at' => now(),
            ])->save();
        } else {
            $card->touch();
        }

        $appleSent = 0;
        $appleFailed = 0;

        if ($this->isAppleWalletConfigured()) {
            $registrations = $card->walletRegistrations()->get();
            if ($registrations->isNotEmpty()) {
                [$certPem, $keyPem] = $this->resolveAppleSigningCredentials();
                $passTypeId = config('services.wallet.apple_pass_type_id', 'pass.com.threedigitsoftware.loyalty');

                foreach ($registrations as $reg) {
                    $status = $this->sendAppleApnsPush($reg->push_token, $passTypeId, $certPem, $keyPem);
                    if ($status >= 200 && $status < 300) {
                        $appleSent++;
                    } else {
                        $appleFailed++;
                        if ($status === 410) {
                            $reg->delete();
                        }
                    }
                }
            }
        }

        $googleSent = false;
        if ($this->isGoogleWalletConfigured() && !app()->runningUnitTests()) {
            $googleSent = $this->syncAndNotifyGoogleObject($card, $title, $message);
        }

        return [
            'apple_sent' => $appleSent,
            'apple_failed' => $appleFailed,
            'google_sent' => $googleSent,
        ];
    }

    /**
     * Build a localized progress notification message for a stamp update or reminder.
     *
     * @return array{0: string, 1: string}
     */
    public function buildStampProgressMessage(LoyaltyCard $card, bool $isReminder = false): array
    {
        $required = max(1, (int) ($card->campaign->required_purchases_count ?? 1));
        $current = max(0, (int) $card->current_stamps_count);
        $remaining = max(0, $required - $current);
        $rewardName = $card->campaign->reward_name ?? 'Free Reward';
        $shopName = $card->shop->name ?? 'ThreeDigits Loyalty';
        $isEnglish = ($card->shop->default_language ?? $card->customer->language ?? 'ar') === 'en';

        if ($card->reward_status === 'ready' || $remaining === 0) {
            $title = "{$shopName} 🎉";
            $message = $isEnglish
                ? "Your {$rewardName} is ready! Visit {$shopName} to redeem it now."
                : "مكافأتك ({$rewardName}) جاهزة الآن! تفضل بزيارة {$shopName} لاستبدالها.";

            return [$title, $message];
        }

        if ($isReminder) {
            $title = $isEnglish ? "{$shopName} Reminder" : "تذكير من {$shopName}";
            $message = $isEnglish
                ? "Only {$remaining} " . ($remaining === 1 ? 'stamp' : 'stamps') . " left for your next {$rewardName}! Come complete your card."
                : "متبقي لك {$remaining} " . ($remaining === 1 ? 'ختم واحد' : ($remaining === 2 ? 'ختمان' : 'أختام')) . " للحصول على {$rewardName}! تعال وأكمل بطاقتك.";

            return [$title, $message];
        }

        $title = $shopName;
        $message = $isEnglish
            ? "Stamp added ({$current}/{$required})! {$remaining} left for your next {$rewardName}."
            : "تمت إضافة ختم جديد ({$current}/{$required})! متبقي لك {$remaining} " . ($remaining === 1 ? 'ختم واحد' : ($remaining === 2 ? 'ختمان' : 'أختام')) . " للحصول على {$rewardName}.";

        return [$title, $message];
    }

    /**
     * Send personalized stamp-completion reminders to all incomplete cards in a shop.
     *
     * @return array{cards: int, apple_sent: int, google_sent: int}
     */
    public function sendStampCompletionReminders(Shop $shop): array
    {
        $cards = LoyaltyCard::withoutGlobalScopes()
            ->with(['customer', 'shop', 'campaign' => fn ($q) => $q->withoutGlobalScopes()])
            ->where('shop_id', $shop->id)
            ->where('reward_status', 'not_ready')
            ->get();

        $appleSent = 0;
        $googleSent = 0;

        foreach ($cards as $card) {
            [$title, $message] = $this->buildStampProgressMessage($card, true);
            $res = $this->sendPassNotification($card, $title, $message);
            $appleSent += $res['apple_sent'];
            if ($res['google_sent']) {
                $googleSent++;
            }
        }

        return [
            'cards' => $cards->count(),
            'apple_sent' => $appleSent,
            'google_sent' => $googleSent,
        ];
    }

    protected function resolveShopLocations(LoyaltyCard $card): array
    {
        $branches = Branch::withoutGlobalScopes()
            ->where('shop_id', $card->shop_id)
            ->where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->limit(10)
            ->get();

        $defaultText = ($card->shop->default_language ?? 'ar') === 'en'
            ? "You're near {$card->shop->name}! Come complete your stamps and claim your reward."
            : "أنت بالقرب من {$card->shop->name}! تعال وأكمل أختامك واحصل على مكافأتك.";

        return $branches->map(fn (Branch $branch) => [
            'latitude' => (float) $branch->latitude,
            'longitude' => (float) $branch->longitude,
            'relevantText' => $branch->nearby_message ?: $defaultText,
        ])->values()->all();
    }

    protected function resolveGoogleLocations(LoyaltyCard $card): array
    {
        return Branch::withoutGlobalScopes()
            ->where('shop_id', $card->shop_id)
            ->where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->limit(10)
            ->get()
            ->map(fn (Branch $branch) => [
                'latitude' => (float) $branch->latitude,
                'longitude' => (float) $branch->longitude,
            ])
            ->values()
            ->all();
    }

    protected function sendAppleApnsPush(string $pushToken, string $passTypeId, string $certPem, string $keyPem): int
    {
        $tmpDir = storage_path('app/wallet/tmp/' . Str::uuid());
        File::ensureDirectoryExists($tmpDir);

        try {
            $certFile = "{$tmpDir}/apns-cert.pem";
            $keyFile = "{$tmpDir}/apns-key.pem";
            file_put_contents($certFile, $certPem);
            file_put_contents($keyFile, $keyPem);

            $ch = curl_init("https://api.push.apple.com/3/device/{$pushToken}");
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => '{}',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
                CURLOPT_SSLCERT => $certFile,
                CURLOPT_SSLKEY => $keyFile,
                CURLOPT_HTTPHEADER => [
                    "apns-topic: {$passTypeId}",
                    'apns-push-type: background',
                    'Content-Type: application/json',
                ],
                CURLOPT_TIMEOUT => 8,
            ]);

            curl_exec($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode < 200 || $httpCode >= 300) {
                Log::warning('Apple Wallet APNs push failed', ['status' => $httpCode, 'error' => curl_error($ch)]);
            }
            curl_close($ch);

            return $httpCode;
        } finally {
            File::deleteDirectory($tmpDir);
        }
    }

    protected function syncAndNotifyGoogleObject(LoyaltyCard $card, ?string $title = null, ?string $message = null): bool
    {
        try {
            [$clientEmail, $privateKeyPem] = $this->resolveGoogleServiceAccountCredentials();
            $accessToken = $this->getGoogleOAuthAccessToken($clientEmail, $privateKeyPem);
            if (!$accessToken) {
                return false;
            }

            $issuerId = config('services.wallet.google_issuer_id');
            $objectId = "{$issuerId}.card_{$card->id}_" . preg_replace('/[^a-zA-Z0-9_.-]/', '', $card->token);
            $required = max(1, (int) ($card->campaign->required_purchases_count ?? 1));

            $objectUrl = "https://walletobjects.googleapis.com/walletobjects/v1/loyaltyObject/{$objectId}";

            $patch = Http::withToken($accessToken)->timeout(8)->patch($objectUrl, [
                'loyaltyPoints' => [
                    'label' => 'Stamps',
                    'balance' => [
                        'string' => "{$card->current_stamps_count} / {$required}",
                    ],
                ],
            ]);

            if ($patch->status() === 404) {
                // Customer has not added this pass to Google Wallet yet
                return false;
            }

            if ($message !== null && trim($message) !== '') {
                $msgRes = Http::withToken($accessToken)->timeout(8)->post("{$objectUrl}/addMessage", [
                    'message' => [
                        'header' => $title ?: $card->shop->name,
                        'body' => trim($message),
                        'messageType' => 'TEXT_AND_NOTIFY',
                    ],
                ]);

                return $msgRes->successful();
            }

            return $patch->successful();
        } catch (\Throwable $e) {
            Log::warning('Google Wallet sync/notify error', ['error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Ensure the shop's LoyaltyClass exists in Google Wallet API (creates it via REST API if missing).
     */
    protected function ensureGoogleLoyaltyClassExists(
        string $clientEmail,
        string $privateKeyPem,
        string $classId,
        LoyaltyCard $card,
        string $logoUrl
    ): void {
        $cacheKey = 'google_wallet_class_ready_' . md5($classId);
        if (Cache::has($cacheKey)) {
            return;
        }

        try {
            $accessToken = $this->getGoogleOAuthAccessToken($clientEmail, $privateKeyPem);
            if (!$accessToken) {
                return;
            }

            $baseUrl = 'https://walletobjects.googleapis.com/walletobjects/v1/loyaltyClass';
            $check = Http::withToken($accessToken)->timeout(8)->get("{$baseUrl}/{$classId}");

            if ($check->successful()) {
                Cache::put($cacheKey, true, now()->addHours(12));

                return;
            }

            if ($check->status() === 404) {
                $classPayload = [
                    'id' => $classId,
                    'issuerName' => $card->shop->name ?: 'ThreeDigits Loyalty',
                    'programName' => $card->campaign->name ?? ($card->shop->name . ' Rewards'),
                    'programLogo' => [
                        'sourceUri' => [
                            'uri' => $logoUrl,
                        ],
                        'contentDescription' => [
                            'defaultValue' => [
                                'language' => 'en-US',
                                'value' => $card->shop->name,
                            ],
                        ],
                    ],
                    'hexBackgroundColor' => '#0A0F2C',
                    'reviewStatus' => 'UNDER_REVIEW',
                ];

                $create = Http::withToken($accessToken)->timeout(8)->post($baseUrl, $classPayload);

                if ($create->successful() || $create->status() === 409) {
                    Cache::put($cacheKey, true, now()->addHours(12));
                } else {
                    Log::warning('Google Wallet LoyaltyClass creation failed', [
                        'classId' => $classId,
                        'status' => $create->status(),
                        'body' => $create->body(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Google Wallet class sync error', ['error' => $e->getMessage()]);
        }
    }

    protected function getGoogleOAuthAccessToken(string $clientEmail, string $privateKeyPem): ?string
    {
        return Cache::remember('google_wallet_oauth_token_' . md5($clientEmail), now()->addMinutes(50), function () use ($clientEmail, $privateKeyPem) {
            $now = time();
            $assertion = $this->signJwtRs256([
                'iss' => $clientEmail,
                'scope' => 'https://www.googleapis.com/auth/wallet_object.issuer',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ], $privateKeyPem);

            $response = Http::asForm()->timeout(8)->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $assertion,
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::warning('Google Wallet OAuth token request failed', ['status' => $response->status(), 'body' => $response->body()]);

            return null;
        });
    }

    /**
     * Build the dynamic PWA Web App Manifest.
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
            'background_color' => '#010A29',
            'theme_color' => '#743EE2',
            'icons' => [
                [
                    'src' => asset('images/logo-icon.png'),
                    'sizes' => '342x242',
                    'type' => 'image/png',
                ],
            ],
        ];
    }

    protected function resolveShopLogoFilePath(LoyaltyCard $card): string
    {
        if (!empty($card->shop->logo)) {
            $clean = ltrim($card->shop->logo, '/');
            $candidates = [
                public_path($clean),
                public_path('uploads/' . $clean),
            ];
            foreach ($candidates as $candidate) {
                if (is_file($candidate)) {
                    return $candidate;
                }
            }
        }

        return public_path('images/logo-icon.png');
    }

    protected function writeResizedPng(string $sourcePath, string $destPath, int $width, int $height): void
    {
        $info = @getimagesize($sourcePath);
        $srcImg = null;

        if ($info && isset($info[2])) {
            $srcImg = match ($info[2]) {
                IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
                IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : null,
                default => null,
            };
        }

        $dstImg = imagecreatetruecolor($width, $height);
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
        $transparent = imagecolorallocatealpha($dstImg, 1, 10, 41, 127);
        imagefilledrectangle($dstImg, 0, 0, $width, $height, $transparent);

        if ($srcImg) {
            $srcW = imagesx($srcImg);
            $srcH = imagesy($srcImg);
            $scale = min($width / max(1, $srcW), $height / max(1, $srcH));
            $newW = max(1, (int) round($srcW * $scale));
            $newH = max(1, (int) round($srcH * $scale));
            $dstX = (int) floor(($width - $newW) / 2);
            $dstY = (int) floor(($height - $newH) / 2);

            imagealphablending($dstImg, true);
            imagecopyresampled($dstImg, $srcImg, $dstX, $dstY, 0, 0, $newW, $newH, $srcW, $srcH);
            imagedestroy($srcImg);
        }

        imagepng($dstImg, $destPath);
        imagedestroy($dstImg);
    }

    /**
     * Render a high-contrast visual stamp-progress strip banner for Apple Wallet storeCard passes.
     */
    protected function writeStampStripPng(LoyaltyCard $card, string $destPath, int $width, int $height): void
    {
        $img = imagecreatetruecolor($width, $height);
        imagealphablending($img, true);

        // Smooth vertical-diagonal ThreeDigits Midnight Navy -> Indigo -> Electric Violet gradient
        for ($y = 0; $y < $height; $y++) {
            $t = $y / max(1, $height - 1);
            $r = (int) round(11 + (76 - 11) * $t);
            $g = (int) round(18 + (29 - 18) * $t);
            $b = (int) round(56 + (149 - 56) * $t);
            $lineColor = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $width, $y, $lineColor);
        }

        // Subtle decorative glow orbs
        $glow1 = imagecolorallocatealpha($img, 116, 62, 226, 98);
        $glow2 = imagecolorallocatealpha($img, 59, 66, 213, 102);
        imagefilledellipse($img, (int) round($width * 0.14), (int) round($height * 0.18), (int) round($width * 0.42), (int) round($width * 0.42), $glow1);
        imagefilledellipse($img, (int) round($width * 0.88), (int) round($height * 0.82), (int) round($width * 0.36), (int) round($width * 0.36), $glow2);

        $required = max(1, min(12, (int) ($card->campaign->required_purchases_count ?? 8)));
        $current = max(0, min($required, (int) $card->current_stamps_count));
        $isReady = $card->reward_status === 'ready' || $current >= $required;

        // Arrange stamp circles in 1 row (<= 6) or 2 balanced rows (> 6)
        $cols = $required <= 6 ? $required : (int) ceil($required / 2);
        $rows = $required <= 6 ? 1 : 2;

        $padX = (int) round($width * 0.09);
        $padY = (int) round($height * 0.16);
        $usableW = $width - ($padX * 2);
        $usableH = $height - ($padY * 2) - (int) round($height * 0.06);

        $cellW = $usableW / max(1, $cols);
        $cellH = $usableH / max(1, $rows);
        $diameter = (int) round(min($cellW, $cellH) * 0.72);
        $radius = max(4, (int) floor($diameter / 2));

        $filledBg = $isReady
            ? imagecolorallocate($img, 16, 185, 129)   // Emerald when reward ready
            : imagecolorallocate($img, 116, 62, 226);  // Electric Violet for earned stamps
        $filledRing = imagecolorallocatealpha($img, 255, 255, 255, 45);
        $emptyBg = imagecolorallocatealpha($img, 10, 15, 44, 55);
        $emptyRing = imagecolorallocatealpha($img, 196, 181, 253, 65);
        $white = imagecolorallocate($img, 255, 255, 255);

        $scale = $width / 375.0;
        $strokeWidth = max(2, (int) round(2.2 * $scale));

        for ($i = 0; $i < $required; $i++) {
            $row = intdiv($i, $cols);
            $col = $i % $cols;
            $itemsInThisRow = ($row === $rows - 1 && $required % $cols !== 0) ? ($required % $cols) : $cols;
            $rowOffsetX = ($usableW - ($itemsInThisRow * $cellW)) / 2;

            $cx = (int) round($padX + $rowOffsetX + ($col + 0.5) * $cellW);
            $cy = (int) round($padY + ($row + 0.5) * $cellH);
            $earned = $i < $current;

            if ($earned) {
                imagefilledellipse($img, $cx, $cy, $diameter, $diameter, $filledBg);
                imagesetthickness($img, max(1, (int) round(1.5 * $scale)));
                imageellipse($img, $cx, $cy, $diameter, $diameter, $filledRing);

                // Crisp checkmark inside earned stamp
                imagesetthickness($img, $strokeWidth);
                $x1 = (int) round($cx - $radius * 0.36);
                $y1 = (int) round($cy + $radius * 0.02);
                $x2 = (int) round($cx - $radius * 0.08);
                $y2 = (int) round($cy + $radius * 0.30);
                $x3 = (int) round($cx + $radius * 0.38);
                $y3 = (int) round($cy - $radius * 0.28);
                imageline($img, $x1, $y1, $x2, $y2, $white);
                imageline($img, $x2, $y2, $x3, $y3, $white);
            } else {
                imagefilledellipse($img, $cx, $cy, $diameter, $diameter, $emptyBg);
                imagesetthickness($img, max(1, (int) round(1.5 * $scale)));
                imageellipse($img, $cx, $cy, $diameter, $diameter, $emptyRing);
            }
        }

        // Sleek bottom progress bar
        $barH = max(3, (int) round(4 * $scale));
        $barBg = imagecolorallocatealpha($img, 255, 255, 255, 100);
        imagefilledrectangle($img, 0, $height - $barH, $width, $height, $barBg);
        $progressW = (int) round($width * ($current / max(1, $required)));
        if ($progressW > 0) {
            imagefilledrectangle($img, 0, $height - $barH, $progressW, $height, $filledBg);
        }

        imagesetthickness($img, 1);
        imagepng($img, $destPath);
        imagedestroy($img);
    }

    protected function signManifest(string $manifestPath, string $signaturePath): void
    {
        [$certPem, $keyPem, $wwdrPath] = $this->resolveAppleSigningCredentials();

        $tmpDir = dirname($manifestPath);
        $certFile = "{$tmpDir}/signer-cert.pem";
        $keyFile = "{$tmpDir}/signer-key.pem";
        $pemSigFile = "{$tmpDir}/signature.pem";

        file_put_contents($certFile, $certPem);
        file_put_contents($keyFile, $keyPem);

        $signed = $wwdrPath && is_file($wwdrPath)
            ? openssl_pkcs7_sign($manifestPath, $pemSigFile, "file://{$certFile}", ["file://{$keyFile}", ''], [], PKCS7_BINARY | PKCS7_DETACHED, $wwdrPath)
            : openssl_pkcs7_sign($manifestPath, $pemSigFile, "file://{$certFile}", ["file://{$keyFile}", ''], [], PKCS7_BINARY | PKCS7_DETACHED);

        @unlink($certFile);
        @unlink($keyFile);

        if (!$signed || !is_file($pemSigFile)) {
            throw new RuntimeException('Failed to sign Apple Wallet pass manifest.');
        }

        $pemContent = file_get_contents($pemSigFile);
        @unlink($pemSigFile);

        $derContent = $this->convertPkcs7PemToDer($pemContent);
        file_put_contents($signaturePath, $derContent);
    }

    protected function convertPkcs7PemToDer(string $pemContent): string
    {
        $begin = 'filename="smime.p7s"';
        $pos = strpos($pemContent, $begin);
        if ($pos !== false) {
            $body = substr($pemContent, $pos + strlen($begin));
        } else {
            $parts = preg_split('/\r?\n\r?\n/', $pemContent, 2);
            $body = $parts[1] ?? $pemContent;
        }

        $body = preg_replace('/-----END PKCS7-----.*/s', '', $body);
        $body = preg_replace('/\s+/', '', trim($body));

        return base64_decode($body);
    }

    /**
     * Resolve Apple Pass Type Certificate & Private Key (or generate a persistent local keypair if unconfigured).
     *
     * @return array{0: string, 1: string, 2: ?string}
     */
    protected function resolveAppleSigningCredentials(): array
    {
        $certPassword = (string) config('services.wallet.apple_cert_password', '');
        $wwdrPath = $this->resolveExistingFilePath(
            config('services.wallet.apple_wwdr_path'),
            storage_path('app/wallet/AppleWWDRCAG4.pem')
        );

        $wwdrBase64 = (string) config('services.wallet.apple_wwdr_base64', '');
        if (!$wwdrPath && $wwdrBase64 !== '') {
            $decodedWwdr = base64_decode($wwdrBase64, true);
            if ($decodedWwdr !== false) {
                $tmpWwdr = storage_path('app/wallet/tmp-wwdr.pem');
                File::ensureDirectoryExists(dirname($tmpWwdr));
                file_put_contents($tmpWwdr, $decodedWwdr);
                $wwdrPath = $tmpWwdr;
            }
        }

        $certBase64 = (string) config('services.wallet.apple_cert_base64', '');
        if ($certBase64 !== '') {
            $raw = base64_decode($certBase64, true);
            $certs = [];
            if ($raw !== false && openssl_pkcs12_read($raw, $certs, $certPassword)) {
                return [$certs['cert'], $certs['pkey'], $wwdrPath];
            }
        }

        $certPath = $this->resolveExistingFilePath(
            config('services.wallet.apple_cert_path'),
            storage_path('app/wallet/apple-pass.p12')
        );

        if ($certPath !== null) {
            $raw = file_get_contents($certPath);
            $certs = [];
            if (str_ends_with(strtolower($certPath), '.p12') || str_ends_with(strtolower($certPath), '.pfx')) {
                if (openssl_pkcs12_read($raw, $certs, $certPassword)) {
                    return [$certs['cert'], $certs['pkey'], $wwdrPath];
                }
            } else {
                // Assume PEM containing cert and key
                return [$raw, $raw, $wwdrPath];
            }
        }

        return $this->ensureFallbackKeypair();
    }

    protected function resolveExistingFilePath(?string $configuredPath, string $defaultPath): ?string
    {
        $candidates = array_filter([
            $configuredPath,
            $configuredPath && !str_starts_with($configuredPath, '/') ? base_path($configuredPath) : null,
            $defaultPath,
        ]);

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Resolve Google Cloud Service Account email & private key PEM.
     *
     * @return array{0: string, 1: string}
     */
    protected function resolveGoogleServiceAccountCredentials(): array
    {
        $saBase64 = (string) config('services.wallet.google_service_account_base64', '');
        if ($saBase64 !== '') {
            $decoded = json_decode((string) base64_decode($saBase64, true), true);
            if (is_array($decoded) && !empty($decoded['client_email']) && !empty($decoded['private_key'])) {
                return [$decoded['client_email'], $decoded['private_key']];
            }
        }

        $saConfig = config('services.wallet.google_service_account_json');
        $saFile = $this->resolveExistingFilePath($saConfig, storage_path('app/wallet/google-service-account.json'));

        if ($saFile !== null) {
            $json = file_get_contents($saFile);
        } elseif (!empty($saConfig)) {
            $json = $saConfig;
        } else {
            $json = '';
        }

        if ($json !== '') {
            $data = json_decode($json, true);
            if (is_array($data) && !empty($data['client_email']) && !empty($data['private_key'])) {
                return [$data['client_email'], $data['private_key']];
            }
        }

        [, $keyPem] = $this->ensureFallbackKeypair();

        return ['wallet-service@threedigits-loyalty.iam.gserviceaccount.com', $keyPem];
    }

    /**
     * Ensure a persistent local X.509 certificate and 2048-bit RSA key exist for signing when production certs are not yet mounted.
     *
     * @return array{0: string, 1: string, 2: null}
     */
    protected function ensureFallbackKeypair(): array
    {
        $walletDir = storage_path('app/wallet');
        File::ensureDirectoryExists($walletDir);

        $certFile = "{$walletDir}/fallback-signer.crt.pem";
        $keyFile = "{$walletDir}/fallback-signer.key.pem";

        if (is_file($certFile) && is_file($keyFile)) {
            return [file_get_contents($certFile), file_get_contents($keyFile), null];
        }

        $privateKey = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $csr = openssl_csr_new([
            'commonName' => config('services.wallet.apple_pass_type_id', 'pass.com.threedigits.loyalty'),
            'organizationName' => 'ThreeDigits Loyalty',
            'countryName' => 'SA',
        ], $privateKey);

        $x509 = openssl_csr_sign($csr, null, $privateKey, 3650);

        openssl_x509_export($x509, $certPem);
        openssl_pkey_export($privateKey, $keyPem);

        file_put_contents($certFile, $certPem);
        file_put_contents($keyFile, $keyPem);

        return [$certPem, $keyPem, null];
    }

    protected function signJwtRs256(array $claims, string $privateKeyPem): string
    {
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES)),
            $this->base64UrlEncode(json_encode($claims, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)),
        ];

        $signingInput = implode('.', $segments);
        $signature = '';

        if (!openssl_sign($signingInput, $signature, $privateKeyPem, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Failed to sign Google Wallet JWT with RS256.');
        }

        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

<?php

namespace App\Services;

use App\Models\LoyaltyCard;
use Illuminate\Support\Facades\File;
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

        return [
            'formatVersion' => 1,
            'passTypeIdentifier' => config('services.wallet.apple_pass_type_id', 'pass.com.threedigits.loyalty'),
            'serialNumber' => $card->token,
            'teamIdentifier' => config('services.wallet.apple_team_id', 'THREEDIGIT'),
            'organizationName' => $card->shop->name,
            'description' => $card->campaign->name ?? ($card->shop->name . ' Loyalty Card'),
            'logoText' => $card->shop->name,
            'foregroundColor' => 'rgb(248, 250, 252)',
            'backgroundColor' => 'rgb(1, 10, 41)',
            'labelColor' => 'rgb(167, 139, 250)',
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
                        'label' => $card->campaign->name ?? 'LOYALTY PASS',
                        'value' => "{$card->current_stamps_count} of {$required}",
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
                'auxiliaryFields' => [
                    [
                        'key' => 'status',
                        'label' => 'STATUS',
                        'value' => $statusText,
                    ],
                ],
                'backFields' => [
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
                ],
            ],
        ];
    }

    /**
     * Check whether official Apple Wallet signing certificates are configured on disk.
     */
    public function isAppleWalletConfigured(): bool
    {
        $certPath = config('services.wallet.apple_cert_path');

        return !empty($certPath) && is_file($certPath);
    }

    /**
     * Check whether Google Wallet issuer credentials are configured.
     */
    public function isGoogleWalletConfigured(): bool
    {
        $issuerId = config('services.wallet.google_issuer_id');
        $saConfig = config('services.wallet.google_service_account_json');

        if (empty($issuerId) || empty($saConfig)) {
            return false;
        }

        if (is_file($saConfig)) {
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

            // 2. Write required icon and logo PNG assets
            $sourceImage = $this->resolveShopLogoFilePath($card);
            $this->writeResizedPng($sourceImage, "{$workDir}/icon.png", 29, 29);
            $this->writeResizedPng($sourceImage, "{$workDir}/icon@2x.png", 58, 58);
            $this->writeResizedPng($sourceImage, "{$workDir}/icon@3x.png", 87, 87);
            $this->writeResizedPng($sourceImage, "{$workDir}/logo.png", 160, 50);
            $this->writeResizedPng($sourceImage, "{$workDir}/logo@2x.png", 320, 100);
            $this->writeResizedPng($sourceImage, "{$workDir}/logo@3x.png", 480, 150);

            // 3. Build manifest.json with SHA-1 hashes of all bundle files
            $bundleFiles = [
                'pass.json',
                'icon.png',
                'icon@2x.png',
                'icon@3x.png',
                'logo.png',
                'logo@2x.png',
                'logo@3x.png',
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
        $objectId = "{$issuerId}.card_{$card->id}_{$card->token}";
        $required = max(1, (int) ($card->campaign->required_purchases_count ?? 1));

        [$clientEmail, $privateKeyPem] = $this->resolveGoogleServiceAccountCredentials();

        $logoUrl = $card->shop->logo_url ?: asset('images/logo-icon.png');
        if (!str_starts_with($logoUrl, 'https://')) {
            // Google Wallet requires a publicly reachable https:// logo URI; provide a fallback if running on local http
            $logoUrl = 'https://placehold.co/256x256/010A29/743EE2.png?text=3D';
        }

        $claims = [
            'iss' => $clientEmail,
            'aud' => 'google',
            'typ' => 'savetowallet',
            'iat' => time(),
            'origins' => [config('app.url', 'http://localhost')],
            'payload' => [
                'loyaltyClasses' => [
                    [
                        'id' => $classId,
                        'issuerName' => 'ThreeDigits Loyalty',
                        'programName' => $card->campaign->name ?? ($card->shop->name . ' Loyalty'),
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
                        'hexBackgroundColor' => '#010A29',
                        'reviewStatus' => 'UNDER_REVIEW',
                    ],
                ],
                'loyaltyObjects' => [
                    [
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
                                    'uri' => $qrUrl,
                                    'description' => 'Open Live Pass',
                                    'id' => 'pass_url',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $jwt = $this->signJwtRs256($claims, $privateKeyPem);

        return "https://pay.google.com/gp/v/save/{$jwt}";
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
        $certPath = config('services.wallet.apple_cert_path');
        $certPassword = (string) config('services.wallet.apple_cert_password', '');
        $wwdrPath = config('services.wallet.apple_wwdr_path');

        if (!empty($certPath) && is_file($certPath)) {
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

    /**
     * Resolve Google Cloud Service Account email & private key PEM.
     *
     * @return array{0: string, 1: string}
     */
    protected function resolveGoogleServiceAccountCredentials(): array
    {
        $saConfig = config('services.wallet.google_service_account_json');

        if (!empty($saConfig)) {
            $json = is_file($saConfig) ? file_get_contents($saConfig) : $saConfig;
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

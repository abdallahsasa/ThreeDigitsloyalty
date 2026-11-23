<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyCard;
use App\Models\WalletRegistration;
use App\Services\WalletPassService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppleWalletWebServiceController extends Controller
{
    /**
     * POST /api/apple-wallet/v1/devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}/{serialNumber}
     */
    public function register(Request $request, string $deviceLibraryIdentifier, string $passTypeIdentifier, string $serialNumber)
    {
        $card = $this->authorizePass($request, $serialNumber);
        if (!$card) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $pushToken = (string) ($request->input('pushToken') ?: $request->json('pushToken') ?: '');
        if ($pushToken === '') {
            return response()->json(['error' => 'Missing pushToken'], 400);
        }

        $registration = WalletRegistration::updateOrCreate(
            [
                'device_library_identifier' => $deviceLibraryIdentifier,
                'serial_number' => $serialNumber,
            ],
            [
                'loyalty_card_id' => $card->id,
                'pass_type_identifier' => $passTypeIdentifier,
                'push_token' => $pushToken,
            ]
        );

        return response()->noContent($registration->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * DELETE /api/apple-wallet/v1/devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}/{serialNumber}
     */
    public function unregister(Request $request, string $deviceLibraryIdentifier, string $passTypeIdentifier, string $serialNumber)
    {
        $card = $this->authorizePass($request, $serialNumber);
        if (!$card) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        WalletRegistration::where('device_library_identifier', $deviceLibraryIdentifier)
            ->where('pass_type_identifier', $passTypeIdentifier)
            ->where('serial_number', $serialNumber)
            ->delete();

        return response()->noContent(200);
    }

    /**
     * GET /api/apple-wallet/v1/devices/{deviceLibraryIdentifier}/registrations/{passTypeIdentifier}
     */
    public function listUpdates(Request $request, string $deviceLibraryIdentifier, string $passTypeIdentifier)
    {
        $registrations = WalletRegistration::with([
            'loyaltyCard' => fn ($q) => $q->withoutGlobalScopes(),
        ])
            ->where('device_library_identifier', $deviceLibraryIdentifier)
            ->where('pass_type_identifier', $passTypeIdentifier)
            ->get();

        if ($registrations->isEmpty()) {
            return response()->noContent(204);
        }

        $since = $request->query('passesUpdatedSince');
        $sinceTime = $since ? Carbon::createFromTimestamp((int) $since) : null;

        $serialNumbers = [];
        $latestTimestamp = 0;

        foreach ($registrations as $reg) {
            $card = $reg->loyaltyCard;
            if (!$card) {
                continue;
            }

            $cardUpdated = $card->updated_at ?? now();
            $ts = $cardUpdated->getTimestamp();
            if ($ts > $latestTimestamp) {
                $latestTimestamp = $ts;
            }

            if (!$sinceTime || $cardUpdated->gt($sinceTime)) {
                $serialNumbers[] = $card->token;
            }
        }

        if (empty($serialNumbers)) {
            return response()->noContent(204);
        }

        return response()->json([
            'lastUpdated' => (string) $latestTimestamp,
            'serialNumbers' => array_values(array_unique($serialNumbers)),
        ]);
    }

    /**
     * GET /api/apple-wallet/v1/passes/{passTypeIdentifier}/{serialNumber}
     */
    public function latestPass(Request $request, string $passTypeIdentifier, string $serialNumber, WalletPassService $walletService)
    {
        $card = $this->authorizePass($request, $serialNumber);
        if (!$card) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $card->load([
            'customer',
            'shop',
            'campaign' => fn ($q) => $q->withoutGlobalScopes(),
        ]);

        $qrUrl = route('loyalty-card.show', $card->token);
        $pkpassBinary = $walletService->generateApplePkpass($card, $qrUrl);
        $lastModified = ($card->updated_at ?? now())->toRfc7231String();

        return response($pkpassBinary, 200, [
            'Content-Type' => 'application/vnd.apple.pkpass',
            'Last-Modified' => $lastModified,
            'Content-Length' => strlen($pkpassBinary),
        ]);
    }

    /**
     * POST /api/apple-wallet/v1/log
     */
    public function logErrors(Request $request)
    {
        Log::info('Apple Wallet device log', ['logs' => $request->input('logs', [])]);

        return response()->noContent(200);
    }

    protected function authorizePass(Request $request, string $serialNumber): ?LoyaltyCard
    {
        $authHeader = (string) $request->header('Authorization', '');
        if (!str_starts_with($authHeader, 'ApplePass ')) {
            return null;
        }

        $token = trim(substr($authHeader, strlen('ApplePass ')));
        if ($token === '' || !hash_equals($serialNumber, $token)) {
            return null;
        }

        return LoyaltyCard::withoutGlobalScopes()->where('token', $serialNumber)->first();
    }
}

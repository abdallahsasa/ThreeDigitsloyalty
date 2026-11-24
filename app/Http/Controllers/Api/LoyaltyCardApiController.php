<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyCard;
use App\Services\RewardLogicService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoyaltyCardApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = LoyaltyCard::with(['customer', 'shop', 'campaign']);

        if ($request->filled('phone')) {
            $phone = $request->string('phone')->toString();
            $query->whereHas('customer', fn ($q) => $q->where('phone', $phone));
        }

        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->integer('shop_id'));
        }

        $cards = $query->latest()->paginate(20);

        return response()->json($cards);
    }

    public function show(string $token): JsonResponse
    {
        $card = LoyaltyCard::withoutGlobalScopes()->with([
            'customer:id,name,phone',
            'shop:id,name,logo,default_language',
            'campaign' => fn ($q) => $q->withoutGlobalScopes()->select('id', 'name', 'type', 'required_purchases_count', 'reward_name', 'reward_description', 'status'),
            'transactions' => fn ($q) => $q->latest()->take(10),
        ])
            ->where('token', $token)
            ->firstOrFail();

        return response()->json(['data' => $card]);
    }

    public function addStamp(Request $request, string $token, RewardLogicService $rewardService): JsonResponse
    {
        $user = $request->user();
        if (!$user->hasAnyRole(['Shop Owner', 'Branch Manager', 'Cashier'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $card = LoyaltyCard::with('campaign')
            ->where('token', $token)
            ->firstOrFail();

        if ($user->shop_id !== $card->shop_id) {
            return response()->json(['message' => 'Card does not belong to your shop.'], 403);
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $updated = $rewardService->addPurchase(
                $card,
                $user,
                $user->branch,
                $validated['notes'] ?? ''
            );

            return response()->json([
                'message' => 'Stamp added successfully.',
                'data' => $updated->load(['customer', 'campaign']),
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function redeem(Request $request, string $token, RewardLogicService $rewardService): JsonResponse
    {
        $user = $request->user();
        if (!$user->hasAnyRole(['Shop Owner', 'Branch Manager', 'Cashier'])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $card = LoyaltyCard::with('campaign')
            ->where('token', $token)
            ->firstOrFail();

        if ($user->shop_id !== $card->shop_id) {
            return response()->json(['message' => 'Card does not belong to your shop.'], 403);
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $updated = $rewardService->redeemReward(
                $card,
                $user,
                $user->branch,
                $validated['notes'] ?? ''
            );

            return response()->json([
                'message' => 'Reward redeemed successfully.',
                'data' => $updated->load(['customer', 'campaign']),
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}

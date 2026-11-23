<?php

namespace App\Services;

use App\Models\LoyaltyCard;
use App\Models\LoyaltyTransaction;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Exception;

class RewardLogicService
{
    /**
     * Add a purchase/stamp to the loyalty card.
     */
    public function addPurchase(LoyaltyCard $card, User $cashier, ?Branch $branch = null, string $notes = ''): LoyaltyCard
    {
        if ($card->reward_status === 'expired') {
            throw new Exception('This loyalty card has expired.');
        }

        if ($card->reward_status === 'ready') {
            throw new Exception('Reward is already ready to be redeemed. Please redeem it first before adding new stamps.');
        }

        $campaign = $card->campaign;
        if (!$campaign || $campaign->status !== 'active') {
            throw new Exception('This loyalty campaign is not currently active.');
        }

        if ($campaign->start_date && now()->startOfDay()->lt($campaign->start_date)) {
            throw new Exception('This loyalty campaign has not started yet.');
        }

        if ($campaign->end_date && now()->startOfDay()->gt($campaign->end_date)) {
            throw new Exception('This loyalty campaign has ended.');
        }

        $requiredStamps = max(1, (int) $campaign->required_purchases_count);

        DB::transaction(function () use (&$card, $cashier, $branch, $notes, $requiredStamps) {
            $card->current_stamps_count++;
            $card->last_activity_date = now();
            
            $transactionType = 'purchase';
            $pointsChange = 1;

            // Check if required purchases reached
            if ($card->current_stamps_count >= $requiredStamps) {
                $card->reward_status = 'ready';
                $transactionType = 'reward_unlocked';
            }

            $card->save();

            LoyaltyTransaction::create([
                'customer_id' => $card->customer_id,
                'shop_id' => $card->shop_id,
                'branch_id' => $branch?->id,
                'cashier_id' => $cashier->id,
                'loyalty_card_id' => $card->id,
                'type' => $transactionType,
                'points_change' => $pointsChange,
                'notes' => $notes,
            ]);
        });

        if (LoyaltyTransaction::withoutGlobalScopes()->where('shop_id', $card->shop_id)->count() === 1) {
            app(FunnelTracker::class)->track('first_stamp_added', ['shop_id' => $card->shop_id]);
        }

        $refreshed = $card->refresh()->loadMissing(['customer', 'shop', 'campaign']);
        if (!app()->runningUnitTests()) {
            app(WalletPassService::class)->sendPassNotification($refreshed);
        }

        return $refreshed;
    }

    /**
     * Redeem the unlocked reward and reset stamps.
     */
    public function redeemReward(LoyaltyCard $card, User $cashier, ?Branch $branch = null, string $notes = ''): LoyaltyCard
    {
        if ($card->reward_status !== 'ready') {
            throw new Exception('Reward is not ready to be redeemed.');
        }

        DB::transaction(function () use (&$card, $cashier, $branch, $notes) {
            $card->current_stamps_count = 0;
            $card->reward_status = 'not_ready';
            $card->last_activity_date = now();
            $card->save();

            LoyaltyTransaction::create([
                'customer_id' => $card->customer_id,
                'shop_id' => $card->shop_id,
                'branch_id' => $branch?->id,
                'cashier_id' => $cashier->id,
                'loyalty_card_id' => $card->id,
                'type' => 'reward_redeemed',
                'points_change' => 0,
                'notes' => $notes,
            ]);
        });

        if (LoyaltyTransaction::withoutGlobalScopes()->where('shop_id', $card->shop_id)->count() === 1) {
            app(FunnelTracker::class)->track('first_stamp_added', ['shop_id' => $card->shop_id]);
        }

        $refreshed = $card->refresh()->loadMissing(['customer', 'shop', 'campaign']);
        if (!app()->runningUnitTests()) {
            app(WalletPassService::class)->sendPassNotification($refreshed);
        }

        return $refreshed;
    }
}

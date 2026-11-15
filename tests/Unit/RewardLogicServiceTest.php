<?php

namespace Tests\Unit;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\LoyaltyCampaign;
use App\Models\LoyaltyCard;
use App\Models\LoyaltyTransaction;
use App\Models\Plan;
use App\Models\Shop;
use App\Models\User;
use App\Services\RewardLogicService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RewardLogicServiceTest extends TestCase
{
    use RefreshDatabase;

    private RewardLogicService $service;
    private Shop $shop;
    private Branch $branch;
    private User $cashier;
    private Customer $customer;
    private LoyaltyCampaign $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new RewardLogicService();

        $plan = Plan::create([
            'name' => 'Starter',
            'price' => 29.00,
            'status' => 'active',
        ]);

        $this->shop = Shop::create([
            'name' => 'Roast & Co',
            'plan_id' => $plan->id,
            'status' => 'active',
        ]);

        $this->branch = Branch::create([
            'shop_id' => $this->shop->id,
            'name' => 'Main Branch',
            'status' => 'active',
        ]);

        $this->cashier = User::factory()->create([
            'shop_id' => $this->shop->id,
            'branch_id' => $this->branch->id,
        ]);

        $this->customer = Customer::create([
            'name' => 'Sara Ali',
            'phone' => '+966500000001',
            'status' => 'active',
        ]);

        $this->campaign = LoyaltyCampaign::create([
            'shop_id' => $this->shop->id,
            'name' => 'Buy 3 Get 1 Free',
            'type' => 'stamps',
            'required_purchases_count' => 3,
            'reward_name' => 'Free Latte',
            'status' => 'active',
        ]);
    }

    public function test_add_purchase_increments_stamps_and_unlocks_reward_at_threshold(): void
    {
        $card = LoyaltyCard::create([
            'customer_id' => $this->customer->id,
            'shop_id' => $this->shop->id,
            'campaign_id' => $this->campaign->id,
            'token' => Str::random(32),
            'current_stamps_count' => 0,
            'reward_status' => 'not_ready',
        ]);

        $card = $this->service->addPurchase($card, $this->cashier, $this->branch, 'First coffee');
        $this->assertSame(1, $card->current_stamps_count);
        $this->assertSame('not_ready', $card->reward_status);

        $card = $this->service->addPurchase($card, $this->cashier, $this->branch);
        $this->assertSame(2, $card->current_stamps_count);
        $this->assertSame('not_ready', $card->reward_status);

        $card = $this->service->addPurchase($card, $this->cashier, $this->branch);
        $this->assertSame(3, $card->current_stamps_count);
        $this->assertSame('ready', $card->reward_status);

        $this->assertSame(3, LoyaltyTransaction::where('loyalty_card_id', $card->id)->count());
        $this->assertDatabaseHas('loyalty_transactions', [
            'loyalty_card_id' => $card->id,
            'type' => 'reward_unlocked',
            'points_change' => 1,
        ]);
    }

    public function test_cannot_add_purchase_when_reward_is_already_ready(): void
    {
        $card = LoyaltyCard::create([
            'customer_id' => $this->customer->id,
            'shop_id' => $this->shop->id,
            'campaign_id' => $this->campaign->id,
            'token' => Str::random(32),
            'current_stamps_count' => 3,
            'reward_status' => 'ready',
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Reward is already ready to be redeemed');

        $this->service->addPurchase($card, $this->cashier, $this->branch);
    }

    public function test_redeem_reward_resets_stamps_and_logs_transaction(): void
    {
        $card = LoyaltyCard::create([
            'customer_id' => $this->customer->id,
            'shop_id' => $this->shop->id,
            'campaign_id' => $this->campaign->id,
            'token' => Str::random(32),
            'current_stamps_count' => 3,
            'reward_status' => 'ready',
        ]);

        $card = $this->service->redeemReward($card, $this->cashier, $this->branch, 'Claimed free latte');

        $this->assertSame(0, $card->current_stamps_count);
        $this->assertSame('not_ready', $card->reward_status);
        $this->assertDatabaseHas('loyalty_transactions', [
            'loyalty_card_id' => $card->id,
            'type' => 'reward_redeemed',
            'points_change' => 0,
            'notes' => 'Claimed free latte',
        ]);
    }

    public function test_cannot_redeem_when_reward_is_not_ready(): void
    {
        $card = LoyaltyCard::create([
            'customer_id' => $this->customer->id,
            'shop_id' => $this->shop->id,
            'campaign_id' => $this->campaign->id,
            'token' => Str::random(32),
            'current_stamps_count' => 1,
            'reward_status' => 'not_ready',
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Reward is not ready to be redeemed');

        $this->service->redeemReward($card, $this->cashier, $this->branch);
    }

    public function test_cannot_stamp_inactive_campaign_or_expired_card(): void
    {
        $this->campaign->update(['status' => 'inactive']);

        $card = LoyaltyCard::create([
            'customer_id' => $this->customer->id,
            'shop_id' => $this->shop->id,
            'campaign_id' => $this->campaign->id,
            'token' => Str::random(32),
            'current_stamps_count' => 0,
            'reward_status' => 'not_ready',
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('not currently active');

        $this->service->addPurchase($card, $this->cashier, $this->branch);
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\CustomerWelcomeMail;
use App\Models\Customer;
use App\Models\LoyaltyCampaign;
use App\Models\LoyaltyCard;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ShopJoinController extends Controller
{
    public function show(string $shop)
    {
        $resolved = $this->resolveActiveShop($shop);

        if (!empty($resolved->slug) && $shop !== $resolved->slug) {
            return redirect()->route('shop.join', $resolved->slug, 301);
        }

        $shop = $resolved;

        $campaign = LoyaltyCampaign::withoutGlobalScopes()
            ->where('shop_id', $shop->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        $joinUrl = route('shop.join', $shop->slug ?: $shop->id);

        return view('shop-join.show', compact('shop', 'campaign', 'joinUrl'));
    }

    public function poster(string $shop)
    {
        $resolved = $this->resolveActiveShop($shop);

        if (!empty($resolved->slug) && $shop !== $resolved->slug) {
            return redirect()->route('shop.join.poster', array_merge(request()->query(), ['shop' => $resolved->slug]), 301);
        }

        $shop = $resolved;

        $campaign = LoyaltyCampaign::withoutGlobalScopes()
            ->where('shop_id', $shop->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        $joinUrl = route('shop.join', $shop->slug ?: $shop->id);

        return view('shop-join.poster', compact('shop', 'campaign', 'joinUrl'));
    }

    public function store(Request $request, string $shop)
    {
        $shop = $this->resolveActiveShop($shop);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
        ]);

        $customer = Customer::withTrashed()->updateOrCreate(
            ['phone' => trim($validated['phone'])],
            [
                'name' => trim($validated['name']),
                'email' => trim($validated['email']),
                'language' => 'ar',
                'status' => 'active',
                'deleted_at' => null,
            ]
        );

        $campaign = LoyaltyCampaign::withoutGlobalScopes()
            ->where('shop_id', $shop->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$campaign) {
            $campaign = LoyaltyCampaign::withoutGlobalScopes()->create([
                'shop_id' => $shop->id,
                'name' => 'VIP Loyalty Card',
                'type' => 'stamps',
                'required_purchases_count' => 8,
                'reward_name' => 'Free Signature Reward',
                'status' => 'active',
            ]);
        }

        $card = LoyaltyCard::withoutGlobalScopes()->firstOrCreate(
            [
                'customer_id' => $customer->id,
                'shop_id' => $shop->id,
                'campaign_id' => $campaign->id,
            ],
            [
                'token' => Str::random(32),
                'current_stamps_count' => 0,
                'reward_status' => 'not_ready',
                'last_activity_date' => now(),
            ]
        );

        $card->setRelation('campaign', $campaign);
        $card->setRelation('shop', $shop);
        $card->setRelation('customer', $customer);

        if ($card->wasRecentlyCreated && LoyaltyCard::withoutGlobalScopes()->where('shop_id', $shop->id)->count() === 1) {
            app(\App\Services\FunnelTracker::class)->track('first_customer_card_issued', ['shop_id' => $shop->id]);
        }

        Mail::to($customer->email)->send(new CustomerWelcomeMail($customer, $shop, $card));

        return redirect()
            ->route('loyalty-card.show', ['token' => $card->token, 'joined' => 1]);
    }

    protected function resolveActiveShop(string $identifier): Shop
    {
        $shop = Shop::query()
            ->where('slug', $identifier)
            ->when(ctype_digit($identifier), fn ($q) => $q->orWhere('id', (int) $identifier))
            ->firstOrFail();

        abort_if($shop->status !== 'active', 404);

        return $shop;
    }
}

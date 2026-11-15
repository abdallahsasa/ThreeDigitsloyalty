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
    public function show(Shop $shop)
    {
        abort_if($shop->status !== 'active', 404);

        $campaign = LoyaltyCampaign::withoutGlobalScopes()
            ->where('shop_id', $shop->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        $joinUrl = route('shop.join', $shop->id);

        return view('shop-join.show', compact('shop', 'campaign', 'joinUrl'));
    }

    public function poster(Shop $shop)
    {
        abort_if($shop->status !== 'active', 404);

        $campaign = LoyaltyCampaign::withoutGlobalScopes()
            ->where('shop_id', $shop->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        $joinUrl = route('shop.join', $shop->id);

        return view('shop-join.poster', compact('shop', 'campaign', 'joinUrl'));
    }

    public function store(Request $request, Shop $shop)
    {
        abort_if($shop->status !== 'active', 404);

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

        Mail::to($customer->email)->send(new CustomerWelcomeMail($customer, $shop, $card));

        return redirect()
            ->route('loyalty-card.show', ['token' => $card->token, 'joined' => 1]);
    }
}

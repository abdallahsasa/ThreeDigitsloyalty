<?php

namespace App\Filament\Shop\Pages;

use App\Models\Branch;
use App\Models\LoyaltyCampaign;
use App\Models\LoyaltyCard;
use App\Models\LoyaltyTransaction;
use App\Services\FunnelTracker;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class GettingStarted extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?int $navigationSort = 0;

    protected string $view = 'filament.shop.pages.getting-started';

    public static function getNavigationLabel(): string
    {
        return __('Getting Started');
    }

    public function getTitle(): string
    {
        return __('Getting Started');
    }

    protected function getViewData(): array
    {
        $tenant = Filament::getTenant();
        $hasLogoOrBranch = $tenant && (!empty($tenant->logo) || Branch::where('shop_id', $tenant->id)->exists());
        $hasCampaign = $tenant && LoyaltyCampaign::where('shop_id', $tenant->id)->exists();
        $hasCard = $tenant && LoyaltyCard::where('shop_id', $tenant->id)->exists();
        $hasStamp = $tenant && LoyaltyTransaction::where('shop_id', $tenant->id)->exists();

        $steps = [
            [
                'done' => (bool) $hasLogoOrBranch,
                'title' => __('Store details and logo'),
                'desc' => __('Add your branch details and upload your store logo.'),
                'url' => $tenant ? url("/shop/{$tenant->id}/branches") : '#',
                'cta' => __('Open Branches'),
            ],
            [
                'done' => (bool) $hasCampaign,
                'title' => __('First reward campaign'),
                'desc' => __('Set the number of stamps required and the reward customers earn.'),
                'url' => $tenant ? url("/shop/{$tenant->id}/loyalty-campaigns") : '#',
                'cta' => __('Create Campaign'),
            ],
            [
                'done' => (bool) ($hasCampaign && $hasLogoOrBranch),
                'title' => __('Registration QR'),
                'desc' => __('Print or display your store QR code so customers can open their digital card.'),
                'url' => $tenant ? url("/shop/{$tenant->id}/shop-qr-code") : '#',
                'cta' => __('View Store QR'),
            ],
            [
                'done' => (bool) ($hasCard && $hasStamp),
                'title' => __('First customer card and stamp'),
                'desc' => __('Scan a customer QR card or look up their phone number to issue the first stamp.'),
                'url' => $tenant ? url("/shop/{$tenant->id}/q-r-scanner") : '#',
                'cta' => __('Open QR Scanner'),
            ],
        ];

        $completedCount = collect($steps)->where('done', true)->count();

        if ($tenant && $hasLogoOrBranch && !session()->has("funnel_setup_done_{$tenant->id}")) {
            session()->put("funnel_setup_done_{$tenant->id}", true);
            app(FunnelTracker::class)->track('store_setup_completed', ['shop_id' => $tenant->id]);
        }

        return [
            'shop' => $tenant,
            'steps' => $steps,
            'completedCount' => $completedCount,
            'totalSteps' => count($steps),
        ];
    }
}

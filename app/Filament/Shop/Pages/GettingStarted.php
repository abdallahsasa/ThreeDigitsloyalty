<?php

namespace App\Filament\Shop\Pages;

use App\Models\Branch;
use App\Models\LoyaltyCampaign;
use App\Models\LoyaltyCard;
use App\Models\LoyaltyTransaction;
use App\Services\FunnelTracker;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

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
        $isAr = app()->getLocale() === 'ar';

        $hasBranch = $tenant && Branch::where('shop_id', $tenant->id)->exists();
        $hasBrandingOrBranch = $tenant && (! empty($tenant->logo) || ! empty($tenant->card_bg_color) || $hasBranch);
        $existingCampaign = $tenant ? LoyaltyCampaign::where('shop_id', $tenant->id)->latest()->first() : null;
        $hasCampaign = (bool) $existingCampaign;
        $hasCard = $tenant && LoyaltyCard::where('shop_id', $tenant->id)->exists();
        $hasStamp = $tenant && LoyaltyTransaction::where('shop_id', $tenant->id)->exists();
        $hasReviewedQr = $tenant && (Cache::get("shop_qr_reviewed_{$tenant->id}", false) || $hasCard);

        $campaignUrl = '#';
        if ($tenant) {
            $campaignUrl = $existingCampaign
                ? url("/shop/{$tenant->id}/loyalty-campaigns/{$existingCampaign->id}/edit?onboarding=1")
                : url("/shop/{$tenant->id}/loyalty-campaigns/create?onboarding=1");
        }

        $existingBranch = $tenant ? Branch::where('shop_id', $tenant->id)->latest()->first() : null;
        $branchUrl = '#';
        if ($tenant) {
            $branchUrl = $existingBranch
                ? url("/shop/{$tenant->id}/branches/{$existingBranch->id}/edit?onboarding=1")
                : url("/shop/{$tenant->id}/branches/create?onboarding=1");
        }

        $steps = [
            [
                'done' => (bool) $hasBrandingOrBranch,
                'title' => __('Store details and logo'),
                'desc' => __('Upload your store logo and customize your loyalty card colors.'),
                'url' => $tenant ? url("/shop/{$tenant->id}/card-branding?onboarding=1") : '#',
                'cta' => $hasBrandingOrBranch
                    ? ($isAr ? 'تعديل البطاقة والشعار' : 'Edit Card & Logo')
                    : __('Customize Card & Logo'),
                'secondary_url' => $branchUrl,
                'secondary_cta' => $hasBranch
                    ? ($isAr ? 'تعديل موقع الفرع' : 'Edit Branch Pin')
                    : ($isAr ? '+ إضافة فرع وموقع الخريطة' : '+ Add Branch & Map Pin'),
            ],
            [
                'done' => $hasCampaign,
                'title' => __('First reward campaign'),
                'desc' => __('Set the number of stamps required and the reward customers earn.'),
                'url' => $campaignUrl,
                'cta' => $hasCampaign
                    ? ($isAr ? 'تعديل الحملة' : 'Edit Campaign')
                    : __('Create Campaign'),
            ],
            [
                'done' => (bool) ($hasCampaign && $hasReviewedQr),
                'title' => __('Registration QR'),
                'desc' => __('Print or display your store QR code so customers can open their digital card.'),
                'url' => $tenant ? url("/shop/{$tenant->id}/shop-qr-code?onboarding=1") : '#',
                'cta' => __('View Store QR'),
            ],
            [
                'done' => (bool) ($hasCard && $hasStamp),
                'title' => __('First customer card and stamp'),
                'desc' => __('Scan a customer QR card or look up their phone number to issue the first stamp.'),
                'url' => $tenant ? url("/shop/{$tenant->id}/q-r-scanner?onboarding=1") : '#',
                'cta' => __('Open QR Scanner'),
            ],
        ];

        $nextStepIndex = collect($steps)->search(fn (array $step) => ! $step['done']);
        $completedCount = collect($steps)->where('done', true)->count();

        if ($tenant && $hasBrandingOrBranch && ! session()->has("funnel_setup_done_{$tenant->id}")) {
            session()->put("funnel_setup_done_{$tenant->id}", true);
            app(FunnelTracker::class)->track('store_setup_completed', ['shop_id' => $tenant->id]);
        }

        return [
            'shop' => $tenant,
            'steps' => $steps,
            'nextStepIndex' => $nextStepIndex,
            'completedCount' => $completedCount,
            'totalSteps' => count($steps),
        ];
    }
}

<?php

namespace App\Filament\Shop\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Url;

class ShopQrCode extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-megaphone';

    protected string $view = 'filament.shop.pages.shop-qr-code';

    #[Url(as: 'onboarding')]
    public bool $fromOnboarding = false;

    public function mount(): void
    {
        if ($tenant = Filament::getTenant()) {
            Cache::forever("shop_qr_reviewed_{$tenant->id}", true);
        }
    }

    public static function getNavigationLabel(): string
    {
        return __('Shop Registration QR');
    }

    public function getTitle(): string
    {
        return __('Customer Registration QR & Landing Page');
    }

    protected function getViewData(): array
    {
        $tenant = Filament::getTenant();
        $joinUrl = $tenant ? route('shop.join', $tenant->slug ?: $tenant->id) : url('/');
        $campaign = $tenant
            ? \App\Models\LoyaltyCampaign::withoutGlobalScopes()
                ->where('shop_id', $tenant->id)
                ->where('status', 'active')
                ->latest()
                ->first()
            : null;

        return [
            'shop' => $tenant,
            'campaign' => $campaign,
            'joinUrl' => $joinUrl,
            'fromOnboarding' => $this->fromOnboarding,
        ];
    }
}

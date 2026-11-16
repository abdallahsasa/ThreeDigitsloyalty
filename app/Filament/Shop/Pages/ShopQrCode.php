<?php

namespace App\Filament\Shop\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;

class ShopQrCode extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-megaphone';

    protected string $view = 'filament.shop.pages.shop-qr-code';

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
        $joinUrl = $tenant ? route('shop.join', $tenant->slug) : url('/');

        return [
            'shop' => $tenant,
            'joinUrl' => $joinUrl,
        ];
    }
}

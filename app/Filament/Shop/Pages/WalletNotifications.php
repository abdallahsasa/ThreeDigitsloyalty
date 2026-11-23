<?php

namespace App\Filament\Shop\Pages;

use App\Models\LoyaltyCard;
use App\Models\WalletRegistration;
use App\Services\WalletPassService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class WalletNotifications extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bell-alert';

    protected string $view = 'filament.shop.pages.wallet-notifications';

    public string $titleText = '';

    public string $messageText = '';

    public static function getNavigationLabel(): string
    {
        return __('Wallet Notifications');
    }

    public function getTitle(): string
    {
        return __('Wallet Notifications');
    }

    public function mount(): void
    {
        $tenant = Filament::getTenant();
        $this->titleText = $tenant ? $tenant->name : 'ThreeDigits Loyalty';
    }

    public function sendBroadcast(WalletPassService $walletService): void
    {
        $this->validate([
            'titleText' => ['required', 'string', 'max:80'],
            'messageText' => ['required', 'string', 'max:240'],
        ]);

        $tenant = Filament::getTenant();
        $cards = LoyaltyCard::with(['customer', 'shop', 'campaign'])
            ->where('shop_id', $tenant->id)
            ->get();

        $appleTotal = 0;
        $googleTotal = 0;

        foreach ($cards as $card) {
            $result = $walletService->sendPassNotification($card, $this->titleText, $this->messageText);
            $appleTotal += $result['apple_sent'];
            if ($result['google_sent']) {
                $googleTotal++;
            }
        }

        $isAr = app()->getLocale() === 'ar';
        Notification::make()
            ->title($isAr
                ? "تم إرسال الإشعار (Apple: {$appleTotal}، Google: {$googleTotal})"
                : "Notification sent (Apple: {$appleTotal}, Google: {$googleTotal})")
            ->success()
            ->send();

        $this->messageText = '';
    }

    protected function getViewData(): array
    {
        $tenant = Filament::getTenant();
        $totalCards = $tenant ? LoyaltyCard::where('shop_id', $tenant->id)->count() : 0;
        $appleDevices = $tenant
            ? WalletRegistration::whereHas('loyaltyCard', fn ($q) => $q->withoutGlobalScopes()->where('shop_id', $tenant->id))->count()
            : 0;

        return [
            'totalCards' => $totalCards,
            'appleDevices' => $appleDevices,
            'appleReady' => app(WalletPassService::class)->isAppleWalletConfigured(),
            'googleReady' => app(WalletPassService::class)->isGoogleWalletConfigured(),
        ];
    }
}

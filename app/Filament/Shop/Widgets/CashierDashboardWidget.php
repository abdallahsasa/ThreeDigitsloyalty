<?php

namespace App\Filament\Shop\Widgets;

use Filament\Widgets\Widget;
use App\Models\LoyaltyTransaction;
use App\Models\LoyaltyCard;
use Carbon\Carbon;
use Filament\Facades\Filament;

class CashierDashboardWidget extends Widget
{
    protected string $view = 'filament.shop.widgets.cashier-dashboard-widget';
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('Cashier');
    }

    protected function getViewData(): array
    {
        $tenant = Filament::getTenant();
        $today = Carbon::today();

        if (!$tenant) return [];

        $cardsScannedToday = LoyaltyCard::whereHas('transactions', fn($q) => $q->whereDate('created_at', $today))
                                ->where('shop_id', $tenant->id)
                                ->count();

        $purchasesAddedToday = LoyaltyTransaction::whereHas('loyaltyCard', fn($q) => $q->where('shop_id', $tenant->id))
                                ->where('type', 'purchase')
                                ->whereDate('created_at', $today)
                                ->count();

        $rewardsRedeemedToday = LoyaltyTransaction::whereHas('loyaltyCard', fn($q) => $q->where('shop_id', $tenant->id))
                                ->where('type', 'reward_redeemed')
                                ->whereDate('created_at', $today)
                                ->count();

        return [
            'scanned' => $cardsScannedToday,
            'purchases' => $purchasesAddedToday,
            'rewards' => $rewardsRedeemedToday,
        ];
    }
}
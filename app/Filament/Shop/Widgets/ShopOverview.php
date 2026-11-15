<?php

namespace App\Filament\Shop\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Customer;
use App\Models\LoyaltyCampaign;
use App\Models\LoyaltyCard;
use App\Models\LoyaltyTransaction;
use App\Models\Branch;
use Carbon\Carbon;
use Filament\Facades\Filament;

class ShopOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return !auth()->user()->hasRole('Cashier');
    }
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();
        if (!$tenant) return [];

        $thisMonth = Carbon::now()->startOfMonth();

        // New Customers This Month
        $newCustomers = Customer::whereHas('loyaltyCards', fn($q) => $q->where('shop_id', $tenant->id))
                                ->where('created_at', '>=', $thisMonth)
                                ->count();

        // Total Purchases this month
        $purchasesThisMonth = LoyaltyTransaction::whereHas('loyaltyCard', fn($q) => $q->where('shop_id', $tenant->id))
                                ->where('type', 'purchase')
                                ->where('created_at', '>=', $thisMonth)
                                ->count();

        // Rewards Ready vs Redeemed
        $rewardsReady = LoyaltyCard::where('shop_id', $tenant->id)->where('reward_status', 'ready')->count();
        $rewardsRedeemed = LoyaltyTransaction::whereHas('loyaltyCard', fn($q) => $q->where('shop_id', $tenant->id))
                                ->where('type', 'reward_redeemed')
                                ->count();

        $isAr = app()->getLocale() === 'ar';

        return [
            Stat::make($isAr ? 'إجمالي العملاء' : 'Total Customers', Customer::whereHas('loyaltyCards', fn($q) => $q->where('shop_id', $tenant->id))->count())
                ->description($isAr ? "{$newCustomers} عميل جديد هذا الشهر" : $newCustomers . ' new this month')
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make($isAr ? 'الحملات النشطة' : 'Active Campaigns', LoyaltyCampaign::where('shop_id', $tenant->id)->where('status', 'active')->count())
                ->description($isAr ? Branch::where('shop_id', $tenant->id)->count() . ' فروع نشطة' : Branch::where('shop_id', $tenant->id)->count() . ' active branches')
                ->icon('heroicon-o-megaphone')
                ->color('success'),
            Stat::make($isAr ? 'المشتريات (هذا الشهر)' : 'Purchases (This Month)', $purchasesThisMonth)
                ->description($isAr ? 'إجمالي زيارات العملاء' : 'Total customer visits')
                ->icon('heroicon-o-shopping-bag')
                ->color('warning'),
            Stat::make($isAr ? 'مكافآت جاهزة للاستبدال' : 'Rewards Ready', $rewardsReady)
                ->description($isAr ? "تم استبدال {$rewardsRedeemed} مكافأة" : $rewardsRedeemed . ' rewards redeemed in total')
                ->icon('heroicon-o-gift')
                ->color('danger'),
        ];
    }
}
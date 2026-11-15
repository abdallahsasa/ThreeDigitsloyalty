<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Shop;
use App\Models\Customer;
use App\Models\LoyaltyCard;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $isAr = app()->getLocale() === 'ar';

        return [
            Stat::make($isAr ? 'إجمالي المتاجر' : 'Total Shops', Shop::count())
                ->description($isAr ? 'المتاجر المسجلة في المنصة' : 'Platform registered shops')
                ->icon('heroicon-o-building-storefront')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make($isAr ? 'المتاجر النشطة' : 'Active Shops', Shop::where('status', 'active')->count())
                ->description($isAr ? 'تعمل حالياً على المنصة' : 'Currently active on the platform')
                ->icon('heroicon-o-check-badge'),
            Stat::make($isAr ? 'إجمالي العملاء' : 'Total Customers', Customer::count())
                ->description($isAr ? 'عبر جميع المتاجر' : 'Across all shops')
                ->icon('heroicon-o-users')
                ->chart([15, 4, 17, 7, 2, 10, 20])
                ->color('primary'),
            Stat::make($isAr ? 'بطاقات الولاء المصدرة' : 'Total Loyalty Cards', LoyaltyCard::count())
                ->description($isAr ? 'البطاقات الرقمية الفعالة' : 'Issued digital cards')
                ->icon('heroicon-o-credit-card'),
        ];
    }
}
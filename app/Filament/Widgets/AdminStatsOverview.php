<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\LoyaltyCard;
use App\Models\LoyaltyTransaction;
use App\Models\Shop;
use App\Models\WalletRegistration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\File;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $isAr = app()->getLocale() === 'ar';

        $totalShops = Shop::count();
        $activeShops = Shop::where('status', 'active')->count();
        $totalCustomers = Customer::count();
        $totalCards = LoyaltyCard::withoutGlobalScopes()->count();
        $totalStamps = LoyaltyTransaction::withoutGlobalScopes()->whereIn('type', ['purchase', 'reward_unlocked'])->count();
        $totalRedeemed = LoyaltyTransaction::withoutGlobalScopes()->where('type', 'reward_redeemed')->count();
        $appleDevices = WalletRegistration::count();
        $todayErrors = $this->countTodayErrors();

        return [
            Stat::make($isAr ? 'المتاجر (النشطة / الإجمالي)' : 'Shops (Active / Total)', "{$activeShops} / {$totalShops}")
                ->description($isAr ? 'المتاجر المسجلة في المنصة' : 'Registered platform stores')
                ->icon('heroicon-o-building-storefront')
                ->color('success'),
            Stat::make($isAr ? 'إجمالي العملاء والبطاقات' : 'Customers & Cards', "{$totalCustomers} / {$totalCards}")
                ->description($isAr ? 'عملاء مسجلين / بطاقات مصدرة' : 'Registered customers / issued cards')
                ->icon('heroicon-o-credit-card')
                ->color('primary'),
            Stat::make($isAr ? 'الأختام والمكافآت' : 'Stamps & Rewards', "{$totalStamps} / {$totalRedeemed}")
                ->description($isAr ? 'أختام مضافة / مكافآت مستبدلة' : 'Stamps issued / rewards redeemed')
                ->icon('heroicon-o-sparkles')
                ->color('info'),
            Stat::make($isAr ? 'أجهزة Apple Wallet' : 'Apple Wallet Devices', $appleDevices)
                ->description($isAr ? 'أجهزة مسجلة للإشعارات' : 'Registered for push updates')
                ->icon('heroicon-o-device-phone-mobile')
                ->color('primary'),
            Stat::make($isAr ? 'أخطاء اليوم' : 'Today’s Errors', $todayErrors)
                ->description($isAr ? 'اضغط لعرض سجل الأخطاء اليومي' : 'Click to inspect daily error logs')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($todayErrors > 0 ? 'danger' : 'success')
                ->url(url('/admin/system-logs')),
        ];
    }

    protected function countTodayErrors(): int
    {
        $dailyFile = storage_path('logs/laravel-' . now()->format('Y-m-d') . '.log');
        $fallbackFile = storage_path('logs/laravel.log');
        $path = File::isFile($dailyFile) ? $dailyFile : (File::isFile($fallbackFile) ? $fallbackFile : null);

        if (!$path) {
            return 0;
        }

        $todayPrefix = '[' . now()->format('Y-m-d');
        $count = 0;
        $handle = @fopen($path, 'rb');
        if (!$handle) {
            return 0;
        }

        while (($line = fgets($handle)) !== false) {
            if (str_starts_with($line, $todayPrefix) && preg_match('/\.(ERROR|CRITICAL|ALERT|EMERGENCY):/', $line)) {
                $count++;
            }
        }
        fclose($handle);

        return $count;
    }
}

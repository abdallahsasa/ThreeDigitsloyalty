<?php

// 1. Update Widgets
$widgets = [
    'app/Filament/Shop/Widgets/ShopOverview.php',
    'app/Filament/Shop/Widgets/ShopPurchasesChart.php',
    'app/Filament/Shop/Widgets/ShopCustomerGrowthChart.php',
    'app/Filament/Shop/Widgets/ShopRecentTransactions.php',
];

$canViewWidget = <<<PHP

    public static function canView(): bool
    {
        return !auth()->user()->hasRole('Cashier');
    }
PHP;

foreach ($widgets as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    if (strpos($content, 'public static function canView()') === false) {
        $content = preg_replace('/(class \w+ extends .*?\n\{)/s', "$1$canViewWidget", $content);
        file_put_contents($file, $content);
    }
}

// 2. Update Resources
$resources = [
    'app/Filament/Shop/Resources/BranchResource.php',
    'app/Filament/Shop/Resources/CustomerResource.php',
    'app/Filament/Shop/Resources/LoyaltyCampaignResource.php',
    'app/Filament/Shop/Resources/LoyaltyCardResource.php',
    'app/Filament/Shop/Resources/UserResource.php',
];

$canAccessResource = <<<PHP

    public static function canAccess(): bool
    {
        return !auth()->user()->hasRole('Cashier');
    }
PHP;

foreach ($resources as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    if (strpos($content, 'public static function canAccess()') === false) {
        $content = preg_replace('/(class \w+ extends .*?\n\{)/s', "$1$canAccessResource", $content);
        file_put_contents($file, $content);
    }
}

// Create a minimalist Cashier Dashboard view for the Dashboard.
// By default Dashboard shows Widgets. If we hide widgets from Cashier, the Dashboard is empty.
// Let's create a custom CashierWidget to show the big QR Scanner button and today's stats.
$cashierWidget = <<<'PHP'
<?php

namespace App\Filament\Shop\Widgets;

use Filament\Widgets\Widget;
use App\Models\LoyaltyTransaction;
use App\Models\LoyaltyCard;
use Carbon\Carbon;
use Filament\Facades\Filament;

class CashierDashboardWidget extends Widget
{
    protected static string $view = 'filament.shop.widgets.cashier-dashboard-widget';
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
PHP;

file_put_contents('app/Filament/Shop/Widgets/CashierDashboardWidget.php', $cashierWidget);

if (!is_dir('resources/views/filament/shop/widgets')) {
    mkdir('resources/views/filament/shop/widgets', 0777, true);
}

$cashierWidgetView = <<<'HTML'
<x-filament-widgets::widget>
    <x-filament::section>
        <div class="text-center py-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome, {{ auth()->user()->name }}</h2>
            <p class="text-gray-500 mb-8">Ready to serve customers?</p>
            
            <x-filament::button size="xl" tag="a" href="{{ \App\Filament\Shop\Pages\QRScanner::getUrl() }}" color="primary" class="w-full md:w-auto px-12 py-4 text-xl">
                Open QR Scanner
            </x-filament::button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 pt-8 border-t border-gray-200">
            <div class="text-center">
                <div class="text-gray-500 text-sm font-medium">Scanned Today</div>
                <div class="text-4xl font-bold text-indigo-600 mt-2">{{ $scanned }}</div>
            </div>
            <div class="text-center">
                <div class="text-gray-500 text-sm font-medium">Purchases Added Today</div>
                <div class="text-4xl font-bold text-indigo-600 mt-2">{{ $purchases }}</div>
            </div>
            <div class="text-center">
                <div class="text-gray-500 text-sm font-medium">Rewards Redeemed Today</div>
                <div class="text-4xl font-bold text-green-600 mt-2">{{ $rewards }}</div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
HTML;

file_put_contents('resources/views/filament/shop/widgets/cashier-dashboard-widget.blade.php', $cashierWidgetView);

echo "Cashier policies and widget created.\n";

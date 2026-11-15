<?php

$overviewContent = <<<'PHP'
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

        return [
            Stat::make('Total Customers', Customer::whereHas('loyaltyCards', fn($q) => $q->where('shop_id', $tenant->id))->count())
                ->description($newCustomers . ' new this month')
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Active Campaigns', LoyaltyCampaign::where('shop_id', $tenant->id)->where('status', 'active')->count())
                ->description(Branch::where('shop_id', $tenant->id)->count() . ' active branches')
                ->icon('heroicon-o-megaphone')
                ->color('success'),
            Stat::make('Purchases (This Month)', $purchasesThisMonth)
                ->description('Total customer visits')
                ->icon('heroicon-o-shopping-bag')
                ->color('warning'),
            Stat::make('Rewards Ready', $rewardsReady)
                ->description($rewardsRedeemed . ' rewards redeemed in total')
                ->icon('heroicon-o-gift')
                ->color('danger'),
        ];
    }
}
PHP;

$purchasesChartContent = <<<'PHP'
<?php

namespace App\Filament\Shop\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\LoyaltyTransaction;
use Carbon\Carbon;
use Filament\Facades\Filament;

class ShopPurchasesChart extends ChartWidget
{
    protected ?string $heading = 'Daily Purchases (Last 7 Days)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $tenant = Filament::getTenant();
        if (!$tenant) return ['datasets' => [], 'labels' => []];

        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('D');
            
            $data[] = LoyaltyTransaction::whereHas('loyaltyCard', fn($q) => $q->where('shop_id', $tenant->id))
                        ->where('type', 'purchase')
                        ->whereDate('created_at', $date->toDateString())
                        ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Purchases (Stamps)',
                    'data' => $data,
                    'backgroundColor' => '#4F46E5',
                    'borderColor' => '#4F46E5',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
PHP;

$customerGrowthChartContent = <<<'PHP'
<?php

namespace App\Filament\Shop\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\LoyaltyCard;
use Carbon\Carbon;
use Filament\Facades\Filament;

class ShopCustomerGrowthChart extends ChartWidget
{
    protected ?string $heading = 'Customer Growth (Last 6 Months)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $tenant = Filament::getTenant();
        if (!$tenant) return ['datasets' => [], 'labels' => []];

        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M');
            
            $data[] = LoyaltyCard::where('shop_id', $tenant->id)
                        ->whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year)
                        ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Loyalty Cards',
                    'data' => $data,
                    'backgroundColor' => '#10B981',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
PHP;

$recentTransactionsContent = <<<'PHP'
<?php

namespace App\Filament\Shop\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\LoyaltyTransaction;
use Filament\Facades\Filament;

class ShopRecentTransactions extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $tenant = Filament::getTenant();

        return $table
            ->query(
                LoyaltyTransaction::with(['loyaltyCard.customer', 'branch'])
                    ->whereHas('loyaltyCard', fn($q) => $q->where('shop_id', $tenant?->id ?? 0))
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('loyaltyCard.customer.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch'),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'purchase',
                        'success' => 'reward_redeemed',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state))),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
PHP;

file_put_contents('app/Filament/Shop/Widgets/ShopOverview.php', $overviewContent);
file_put_contents('app/Filament/Shop/Widgets/ShopPurchasesChart.php', $purchasesChartContent);
file_put_contents('app/Filament/Shop/Widgets/ShopCustomerGrowthChart.php', $customerGrowthChartContent);
file_put_contents('app/Filament/Shop/Widgets/ShopRecentTransactions.php', $recentTransactionsContent);

echo "Shop widgets updated.\n";

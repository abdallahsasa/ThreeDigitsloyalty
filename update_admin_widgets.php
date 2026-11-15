<?php

$overviewContent = <<<'PHP'
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
        return [
            Stat::make('Total Shops', Shop::count())
                ->description('Platform registered shops')
                ->icon('heroicon-o-building-storefront')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Active Shops', Shop::where('status', 'active')->count())
                ->description('Currently active on the platform')
                ->icon('heroicon-o-check-badge'),
            Stat::make('Total Customers', Customer::count())
                ->description('Across all shops')
                ->icon('heroicon-o-users')
                ->chart([15, 4, 17, 7, 2, 10, 20])
                ->color('primary'),
            Stat::make('Total Loyalty Cards', LoyaltyCard::count())
                ->description('Issued digital cards')
                ->icon('heroicon-o-credit-card'),
        ];
    }
}
PHP;

$shopsChartContent = <<<'PHP'
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Shop;
use Carbon\Carbon;

class AdminShopsChart extends ChartWidget
{
    protected static ?string $heading = 'Shop Growth (Last 6 Months)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M');
            $data[] = Shop::whereMonth('created_at', $month->month)->whereYear('created_at', $month->year)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Shops',
                    'data' => $data,
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

$subscriptionsChartContent = <<<'PHP'
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class AdminSubscriptionsChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue ($)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Mock data since we don't have real revenue yet
        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => [1200, 1500, 2100, 1800, 2400, 3100],
                    'backgroundColor' => '#4F46E5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
PHP;

$latestShopsContent = <<<'PHP'
<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Shop;

class AdminLatestShops extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Shop::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
PHP;

file_put_contents('app/Filament/Widgets/AdminStatsOverview.php', $overviewContent);
file_put_contents('app/Filament/Widgets/AdminShopsChart.php', $shopsChartContent);
file_put_contents('app/Filament/Widgets/AdminSubscriptionsChart.php', $subscriptionsChartContent);
file_put_contents('app/Filament/Widgets/AdminLatestShops.php', $latestShopsContent);

echo "Admin widgets updated.\n";

<?php

namespace App\Filament\Shop\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\LoyaltyCard;
use Carbon\Carbon;
use Filament\Facades\Filament;

class ShopCustomerGrowthChart extends ChartWidget
{
    public static function canView(): bool
    {
        return !auth()->user()->hasRole('Cashier');
    }
    public function getHeading(): ?string
    {
        return app()->getLocale() === 'ar' ? 'نمو العملاء (آخر 6 أشهر)' : 'Customer Growth (Last 6 Months)';
    }
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
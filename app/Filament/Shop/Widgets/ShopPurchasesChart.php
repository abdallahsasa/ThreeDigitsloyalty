<?php

namespace App\Filament\Shop\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\LoyaltyTransaction;
use Carbon\Carbon;
use Filament\Facades\Filament;

class ShopPurchasesChart extends ChartWidget
{
    public static function canView(): bool
    {
        return !auth()->user()->hasRole('Cashier');
    }
    public function getHeading(): ?string
    {
        return app()->getLocale() === 'ar' ? 'المشتريات اليومية (آخر 7 أيام)' : 'Daily Purchases (Last 7 Days)';
    }
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
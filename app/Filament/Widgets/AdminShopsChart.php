<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Shop;
use Carbon\Carbon;

class AdminShopsChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return app()->getLocale() === 'ar' ? 'نمو المتاجر (آخر 6 أشهر)' : 'Shop Growth (Last 6 Months)';
    }
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
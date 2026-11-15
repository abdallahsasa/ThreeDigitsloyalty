<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class AdminSubscriptionsChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return app()->getLocale() === 'ar' ? 'الإيرادات الشهرية ($)' : 'Monthly Revenue ($)';
    }
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
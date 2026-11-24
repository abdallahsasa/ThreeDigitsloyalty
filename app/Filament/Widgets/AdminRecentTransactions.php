<?php

namespace App\Filament\Widgets;

use App\Models\LoyaltyTransaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AdminRecentTransactions extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $isAr = app()->getLocale() === 'ar';

        return $table
            ->heading($isAr ? 'أحدث حركات الأختام والمكافآت عبر المنصة' : 'Recent Platform Activity')
            ->query(
                LoyaltyTransaction::withoutGlobalScopes()
                    ->with(['shop', 'customer', 'branch'])
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('shop.name')->label($isAr ? 'المتجر' : 'Shop'),
                Tables\Columns\TextColumn::make('customer.name')->label($isAr ? 'العميل' : 'Customer'),
                Tables\Columns\TextColumn::make('branch.name')->label($isAr ? 'الفرع' : 'Branch'),
                Tables\Columns\TextColumn::make('type')
                    ->label($isAr ? 'النوع' : 'Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'reward_redeemed', 'reward_unlocked' => 'success',
                        'purchase' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label($isAr ? 'التاريخ' : 'Date')->dateTime(),
            ]);
    }
}

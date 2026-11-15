<?php

namespace App\Filament\Shop\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\LoyaltyTransaction;
use Filament\Facades\Filament;

class ShopRecentTransactions extends BaseWidget
{
    public static function canView(): bool
    {
        return !auth()->user()->hasRole('Cashier');
    }
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
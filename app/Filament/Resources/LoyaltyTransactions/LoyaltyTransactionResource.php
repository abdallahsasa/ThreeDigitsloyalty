<?php

namespace App\Filament\Resources\LoyaltyTransactions;

use App\Models\LoyaltyTransaction;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LoyaltyTransactionResource extends Resource
{
    protected static ?string $model = LoyaltyTransaction::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?int $navigationSort = 14;

    public static function getNavigationGroup(): ?string
    {
        return __('Loyalty Operations');
    }

    public static function getNavigationLabel(): string
    {
        return __('Loyalty Transactions');
    }

    public static function getModelLabel(): string
    {
        return __('Loyalty Transaction');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Loyalty Transactions');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('shop.name')->label('Shop')->searchable()->sortable(),
                TextColumn::make('branch.name')->label('Branch')->searchable(),
                TextColumn::make('customer.name')->label('Customer')->searchable(),
                TextColumn::make('cashier.name')->label('Cashier')->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'reward_redeemed', 'reward_unlocked' => 'success',
                        'purchase' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('points_change')->numeric()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('shop_id')->relationship('shop', 'name')->label('Shop'),
                SelectFilter::make('type')->options([
                    'purchase' => __('Purchase'),
                    'reward_unlocked' => __('Reward Unlocked'),
                    'reward_redeemed' => __('Reward Redeemed'),
                    'manual_adjustment' => __('Manual Adjustment'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\LoyaltyTransactions\Pages\ListLoyaltyTransactions::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Shop\Resources\LoyaltyTransactions\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class LoyaltyTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Hidden::make('shop_id')
                    ->default(fn () => Filament::getTenant()?->id)
                    ->required(),
                Select::make('branch_id')
                    ->relationship(
                        'branch',
                        'name',
                        fn (Builder $query) => $query->where('shop_id', Filament::getTenant()?->id)
                    )
                    ->searchable()
                    ->preload(),
                Select::make('cashier_id')
                    ->relationship(
                        'cashier',
                        'name',
                        fn (Builder $query) => $query->where('shop_id', Filament::getTenant()?->id)
                    )
                    ->searchable()
                    ->preload(),
                Select::make('loyalty_card_id')
                    ->relationship(
                        'loyaltyCard',
                        'token',
                        fn (Builder $query) => $query->where('shop_id', Filament::getTenant()?->id)
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->options([
                        'purchase' => __('Purchase'),
                        'reward_unlocked' => __('Reward Unlocked'),
                        'reward_redeemed' => __('Reward Redeemed'),
                        'manual_adjustment' => __('Manual Adjustment'),
                    ])
                    ->default('purchase')
                    ->required(),
                TextInput::make('points_change')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}

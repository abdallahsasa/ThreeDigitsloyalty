<?php

namespace App\Filament\Shop\Resources\LoyaltyTransactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LoyaltyTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('shop_id')
                    ->relationship('shop', 'name')
                    ->required(),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                Select::make('cashier_id')
                    ->relationship('cashier', 'name'),
                Select::make('loyalty_card_id')
                    ->relationship('loyaltyCard', 'id')
                    ->required(),
                TextInput::make('type')
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

<?php

namespace App\Filament\Shop\Resources\LoyaltyTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoyaltyTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['customer', 'shop', 'branch', 'cashier', 'loyaltyCard']))
            ->columns([
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('shop.name')
                    ->searchable(),
                TextColumn::make('branch.name')
                    ->searchable(),
                TextColumn::make('cashier.name')
                    ->searchable(),
                TextColumn::make('loyaltyCard.id')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('points_change')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

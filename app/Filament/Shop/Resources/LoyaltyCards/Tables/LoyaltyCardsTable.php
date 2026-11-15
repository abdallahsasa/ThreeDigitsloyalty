<?php

namespace App\Filament\Shop\Resources\LoyaltyCards\Tables;

use App\Models\LoyaltyCard;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoyaltyCardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['customer', 'shop', 'campaign']))
            ->columns([
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('shop.name')
                    ->searchable(),
                TextColumn::make('campaign.name')
                    ->searchable(),
                TextColumn::make('token')
                    ->searchable(),
                TextColumn::make('current_stamps_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reward_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ready' => 'success',
                        'not_ready' => 'warning',
                        'redeemed' => 'info',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title())
                    ->searchable(),
                TextColumn::make('last_activity_date')
                    ->dateTime()
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
                Action::make('viewCard')
                    ->label('View QR')
                    ->icon('heroicon-o-qr-code')
                    ->url(fn (LoyaltyCard $record): string => route('loyalty-card.show', $record->token))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

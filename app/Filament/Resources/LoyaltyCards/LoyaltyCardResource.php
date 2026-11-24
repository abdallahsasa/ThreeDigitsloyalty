<?php

namespace App\Filament\Resources\LoyaltyCards;

use App\Models\LoyaltyCard;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LoyaltyCardResource extends Resource
{
    protected static ?string $model = LoyaltyCard::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('Loyalty Operations');
    }

    public static function getNavigationLabel(): string
    {
        return __('Loyalty Cards');
    }

    public static function getModelLabel(): string
    {
        return __('Loyalty Card');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Loyalty Cards');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('shop_id')->relationship('shop', 'name')->searchable()->preload()->required(),
            Select::make('customer_id')->relationship('customer', 'name')->searchable()->preload()->required(),
            Select::make('campaign_id')->relationship('campaign', 'name')->searchable()->preload()->required(),
            TextInput::make('token')->required(),
            TextInput::make('current_stamps_count')->numeric()->default(0)->required(),
            Select::make('reward_status')->options([
                'not_ready' => __('Not Ready'),
                'ready' => __('Ready to Redeem'),
                'redeemed' => __('Redeemed'),
                'expired' => __('Expired'),
            ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('shop.name')->label('Shop')->searchable()->sortable(),
                TextColumn::make('customer.name')->label('Customer')->searchable(),
                TextColumn::make('customer.phone')->label('Phone')->searchable(),
                TextColumn::make('campaign.name')->label('Campaign')->searchable(),
                TextColumn::make('current_stamps_count')
                    ->label('Stamps')
                    ->formatStateUsing(fn ($state, $record) => "{$state} / " . ($record->campaign?->required_purchases_count ?? '-'))
                    ->sortable(),
                TextColumn::make('reward_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ready' => 'success',
                        'not_ready' => 'warning',
                        'redeemed' => 'info',
                        default => 'danger',
                    }),
                TextColumn::make('wallet_registrations_count')
                    ->counts('walletRegistrations')
                    ->label('Apple Devices'),
                TextColumn::make('last_activity_date')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('shop_id')->relationship('shop', 'name')->label('Shop'),
                SelectFilter::make('reward_status')->options([
                    'not_ready' => __('Not Ready'),
                    'ready' => __('Ready to Redeem'),
                    'redeemed' => __('Redeemed'),
                    'expired' => __('Expired'),
                ]),
            ])
            ->recordActions([
                Action::make('view_pass')
                    ->label(__('View QR'))
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

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\LoyaltyCards\Pages\ListLoyaltyCards::route('/'),
            'create' => \App\Filament\Resources\LoyaltyCards\Pages\CreateLoyaltyCard::route('/create'),
            'edit' => \App\Filament\Resources\LoyaltyCards\Pages\EditLoyaltyCard::route('/{record}/edit'),
        ];
    }
}

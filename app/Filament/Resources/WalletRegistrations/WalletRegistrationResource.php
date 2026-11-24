<?php

namespace App\Filament\Resources\WalletRegistrations;

use App\Models\WalletRegistration;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WalletRegistrationResource extends Resource
{
    protected static ?string $model = WalletRegistration::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?int $navigationSort = 15;

    public static function getNavigationGroup(): ?string
    {
        return __('Loyalty Operations');
    }

    public static function getNavigationLabel(): string
    {
        return __('Wallet Devices');
    }

    public static function getModelLabel(): string
    {
        return __('Wallet Device');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Wallet Devices');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('loyaltyCard.shop.name')->label('Shop')->searchable(),
                TextColumn::make('loyaltyCard.customer.name')->label('Customer')->searchable(),
                TextColumn::make('loyaltyCard.customer.phone')->label('Phone')->searchable(),
                TextColumn::make('serial_number')->label('Card Token')->limit(14)->searchable(),
                TextColumn::make('device_library_identifier')->label('Apple Device')->limit(16),
                TextColumn::make('updated_at')->label('Last Sync')->dateTime()->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\WalletRegistrations\Pages\ListWalletRegistrations::route('/'),
        ];
    }
}

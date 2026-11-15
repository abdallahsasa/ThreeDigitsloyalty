<?php

namespace App\Filament\Shop\Resources\LoyaltyCards;

use App\Filament\Shop\Resources\LoyaltyCards\Pages\CreateLoyaltyCard;
use App\Filament\Shop\Resources\LoyaltyCards\Pages\EditLoyaltyCard;
use App\Filament\Shop\Resources\LoyaltyCards\Pages\ListLoyaltyCards;
use App\Filament\Shop\Resources\LoyaltyCards\Schemas\LoyaltyCardForm;
use App\Filament\Shop\Resources\LoyaltyCards\Tables\LoyaltyCardsTable;
use App\Models\LoyaltyCard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoyaltyCardResource extends Resource
{
    protected static ?string $model = LoyaltyCard::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';

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
        return LoyaltyCardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoyaltyCardsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLoyaltyCards::route('/'),
            'create' => CreateLoyaltyCard::route('/create'),
            'edit' => EditLoyaltyCard::route('/{record}/edit'),
        ];
    }
}

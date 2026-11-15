<?php

namespace App\Filament\Shop\Resources\LoyaltyTransactions;

use App\Filament\Shop\Resources\LoyaltyTransactions\Pages\CreateLoyaltyTransaction;
use App\Filament\Shop\Resources\LoyaltyTransactions\Pages\EditLoyaltyTransaction;
use App\Filament\Shop\Resources\LoyaltyTransactions\Pages\ListLoyaltyTransactions;
use App\Filament\Shop\Resources\LoyaltyTransactions\Schemas\LoyaltyTransactionForm;
use App\Filament\Shop\Resources\LoyaltyTransactions\Tables\LoyaltyTransactionsTable;
use App\Models\LoyaltyTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoyaltyTransactionResource extends Resource
{
    protected static ?string $model = LoyaltyTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

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


    public static function form(Schema $schema): Schema
    {
        return LoyaltyTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoyaltyTransactionsTable::configure($table);
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
            'index' => ListLoyaltyTransactions::route('/'),
            'create' => CreateLoyaltyTransaction::route('/create'),
            'edit' => EditLoyaltyTransaction::route('/{record}/edit'),
        ];
    }
}

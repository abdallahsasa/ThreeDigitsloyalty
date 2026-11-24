<?php

namespace App\Filament\Resources\LoyaltyTransactions;

use App\Models\LoyaltyTransaction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('shop_id')->relationship('shop', 'name')->searchable()->preload()->required(),
            Select::make('branch_id')->relationship('branch', 'name')->searchable()->preload(),
            Select::make('customer_id')->relationship('customer', 'name')->searchable()->preload()->required(),
            Select::make('cashier_id')->relationship('cashier', 'name')->searchable()->preload(),
            Select::make('loyalty_card_id')->relationship('loyaltyCard', 'token')->searchable()->preload()->required(),
            Select::make('type')->options([
                'purchase' => __('Purchase'),
                'reward_unlocked' => __('Reward Unlocked'),
                'reward_redeemed' => __('Reward Redeemed'),
                'manual_adjustment' => __('Manual Adjustment'),
            ])->required(),
            TextInput::make('points_change')->numeric()->default(0)->required(),
            Textarea::make('notes')->columnSpanFull(),
        ]);
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

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\LoyaltyTransactions\Pages\ListLoyaltyTransactions::route('/'),
            'create' => \App\Filament\Resources\LoyaltyTransactions\Pages\CreateLoyaltyTransaction::route('/create'),
            'edit' => \App\Filament\Resources\LoyaltyTransactions\Pages\EditLoyaltyTransaction::route('/{record}/edit'),
        ];
    }
}

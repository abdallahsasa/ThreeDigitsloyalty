<?php

namespace App\Filament\Resources\LoyaltyCampaigns;

use App\Models\LoyaltyCampaign;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LoyaltyCampaignResource extends Resource
{
    protected static ?string $model = LoyaltyCampaign::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?int $navigationSort = 11;

    public static function getNavigationGroup(): ?string
    {
        return __('Loyalty Operations');
    }

    public static function getNavigationLabel(): string
    {
        return __('Loyalty Campaigns');
    }

    public static function getModelLabel(): string
    {
        return __('Loyalty Campaign');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Loyalty Campaigns');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('shop.name')->label('Shop')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('required_purchases_count')->label('Stamps Required')->sortable(),
                TextColumn::make('reward_name')->searchable(),
                TextColumn::make('loyalty_cards_count')->counts('loyaltyCards')->label('Cards'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'gray'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('shop_id')->relationship('shop', 'name')->label('Shop'),
                SelectFilter::make('status')->options([
                    'active' => __('Active'),
                    'inactive' => __('Inactive'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\LoyaltyCampaigns\Pages\ListLoyaltyCampaigns::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Shop\Resources\LoyaltyCampaigns;

use App\Filament\Shop\Resources\LoyaltyCampaigns\Pages\CreateLoyaltyCampaign;
use App\Filament\Shop\Resources\LoyaltyCampaigns\Pages\EditLoyaltyCampaign;
use App\Filament\Shop\Resources\LoyaltyCampaigns\Pages\ListLoyaltyCampaigns;
use App\Filament\Shop\Resources\LoyaltyCampaigns\Schemas\LoyaltyCampaignForm;
use App\Filament\Shop\Resources\LoyaltyCampaigns\Tables\LoyaltyCampaignsTable;
use App\Models\LoyaltyCampaign;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoyaltyCampaignResource extends Resource
{
    protected static ?string $model = LoyaltyCampaign::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

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


    public static function form(Schema $schema): Schema
    {
        return LoyaltyCampaignForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoyaltyCampaignsTable::configure($table);
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
            'index' => ListLoyaltyCampaigns::route('/'),
            'create' => CreateLoyaltyCampaign::route('/create'),
            'edit' => EditLoyaltyCampaign::route('/{record}/edit'),
        ];
    }
}

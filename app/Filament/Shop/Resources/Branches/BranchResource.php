<?php

namespace App\Filament\Shop\Resources\Branches;

use App\Filament\Shop\Resources\Branches\Pages\CreateBranch;
use App\Filament\Shop\Resources\Branches\Pages\EditBranch;
use App\Filament\Shop\Resources\Branches\Pages\ListBranches;
use App\Filament\Shop\Resources\Branches\Schemas\BranchForm;
use App\Filament\Shop\Resources\Branches\Tables\BranchesTable;
use App\Models\Branch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationLabel(): string
    {
        return __('Branches');
    }

    public static function getModelLabel(): string
    {
        return __('Branch');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Branches');
    }


    public static function form(Schema $schema): Schema
    {
        return BranchForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchesTable::configure($table);
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
            'index' => ListBranches::route('/'),
            'create' => CreateBranch::route('/create'),
            'edit' => EditBranch::route('/{record}/edit'),
        ];
    }
}

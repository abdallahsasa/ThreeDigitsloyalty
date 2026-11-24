<?php

namespace App\Filament\Resources\Branches;

use App\Models\Branch;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?int $navigationSort = 13;

    public static function getNavigationGroup(): ?string
    {
        return __('Loyalty Operations');
    }

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
        return $schema->components([
            Select::make('shop_id')->relationship('shop', 'name')->searchable()->preload()->required(),
            TextInput::make('name')->required(),
            FileUpload::make('image')
                ->disk('uploads')
                ->directory('branches')
                ->image()
                ->maxSize(2048)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->getUploadedFileNameForStorageUsing(
                    fn (\Illuminate\Http\UploadedFile $file): string => 'branch-' . time() . '-' . \Illuminate\Support\Str::random(10) . '.' . $file->extension()
                ),
            TextInput::make('address'),
            Hidden::make('latitude'),
            Hidden::make('longitude'),
            View::make('filament.shop.components.branch-map-picker')->columnSpanFull(),
            TextInput::make('nearby_message')->maxLength(160),
            TextInput::make('phone')->tel(),
            Select::make('manager_id')->label('Branch Manager')->relationship('manager', 'name')->searchable()->preload(),
            Select::make('status')->options([
                'active' => __('Active'),
                'inactive' => __('Inactive'),
            ])->default('active')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('shop.name')->label('Shop')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('address')->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('latitude')
                    ->label('GPS Map')
                    ->formatStateUsing(fn ($state, Branch $record) => ($record->latitude && $record->longitude) ? 'Pinned' : 'Not set')
                    ->badge()
                    ->color(fn ($state, Branch $record) => ($record->latitude && $record->longitude) ? 'success' : 'gray'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'active' ? 'success' : 'danger'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('shop_id')->relationship('shop', 'name')->label('Shop'),
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
            'index' => \App\Filament\Resources\Branches\Pages\ListBranches::route('/'),
            'create' => \App\Filament\Resources\Branches\Pages\CreateBranch::route('/create'),
            'edit' => \App\Filament\Resources\Branches\Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Shop\Resources\Branches\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('shop_id')
                    ->default(fn () => Filament::getTenant()?->id)
                    ->required(),
                TextInput::make('name')
                    ->required(),
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
                TextInput::make('phone')
                    ->tel(),
                Select::make('manager_id')
                    ->label('Branch Manager')
                    ->relationship(
                        'manager',
                        'name',
                        fn (Builder $query) => $query->where('shop_id', Filament::getTenant()?->id)
                    )
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->options([
                        'active' => __('Active'),
                        'inactive' => __('Inactive'),
                    ])
                    ->required()
                    ->default('active'),
            ]);
    }
}

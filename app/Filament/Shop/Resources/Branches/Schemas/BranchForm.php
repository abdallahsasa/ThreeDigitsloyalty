<?php

namespace App\Filament\Shop\Resources\Branches\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('shop_id')
                    ->relationship('shop', 'name')
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
                TextInput::make('manager_id')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
            ]);
    }
}

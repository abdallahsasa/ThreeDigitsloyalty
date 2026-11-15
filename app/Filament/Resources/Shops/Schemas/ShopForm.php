<?php

namespace App\Filament\Resources\Shops\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShopForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                FileUpload::make('logo')
                    ->disk('uploads')
                    ->directory('shops')
                    ->image()
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->getUploadedFileNameForStorageUsing(
                        fn (\Illuminate\Http\UploadedFile $file): string => 'shop-' . time() . '-' . \Illuminate\Support\Str::random(10) . '.' . $file->extension()
                    ),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('address'),
                TextInput::make('country'),
                TextInput::make('city'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                Select::make('plan_id')
                    ->relationship('plan', 'name'),
                TextInput::make('default_language')
                    ->required()
                    ->default('en'),
            ]);
    }
}

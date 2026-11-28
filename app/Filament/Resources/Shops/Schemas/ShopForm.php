<?php

namespace App\Filament\Resources\Shops\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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
                TextInput::make('slug')
                    ->helperText('Leave blank to auto-generate from shop name (used in /join/{slug}).')
                    ->unique(ignoreRecord: true),
                Select::make('category')
                    ->label('Shop Category')
                    ->options(\App\Models\Shop::categoryOptions('en'))
                    ->default('cafe'),
                FileUpload::make('logo')
                    ->disk('uploads')
                    ->directory('shops')
                    ->image()
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->getUploadedFileNameForStorageUsing(
                        fn(\Illuminate\Http\UploadedFile $file): string => 'shop-' . time() . '-' . \Illuminate\Support\Str::random(10) . '.' . $file->extension()
                    ),
                ColorPicker::make('card_bg_color')
                    ->label('Card Primary Color')
                    ->default('#743EE2'),
                ColorPicker::make('card_accent_color')
                    ->label('Card Accent Color')
                    ->default('#3B42D5'),
                ColorPicker::make('card_text_color')
                    ->label('Card Text Color')
                    ->default('#FFFFFF'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('address'),
                TextInput::make('country'),
                TextInput::make('city'),
                Select::make('status')
                    ->options([
                        'active' => __('Active'),
                        'inactive' => __('Inactive'),
                    ])
                    ->required()
                    ->default('active'),
                Select::make('plan_id')
                    ->relationship('plan', 'name'),
                Select::make('default_language')
                    ->options([
                        'ar' => 'العربية (ar)',
                        'en' => 'English (en)',
                    ])
                    ->required()
                    ->default('ar'),
            ]);
    }
}

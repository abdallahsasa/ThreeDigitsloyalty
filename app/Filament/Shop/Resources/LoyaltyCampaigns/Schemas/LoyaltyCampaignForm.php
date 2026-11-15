<?php

namespace App\Filament\Shop\Resources\LoyaltyCampaigns\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LoyaltyCampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('shop_id')
                    ->default(fn () => Filament::getTenant()?->id)
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('image')
                    ->disk('uploads')
                    ->directory('campaigns')
                    ->image()
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->getUploadedFileNameForStorageUsing(
                        fn (\Illuminate\Http\UploadedFile $file): string => 'campaign-' . time() . '-' . \Illuminate\Support\Str::random(10) . '.' . $file->extension()
                    ),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('type')
                    ->options([
                        'stamps' => 'Stamps',
                    ])
                    ->required()
                    ->default('stamps'),
                TextInput::make('required_purchases_count')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(10),
                TextInput::make('reward_name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('reward_description')
                    ->columnSpanFull(),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'draft' => 'Draft',
                    ])
                    ->required()
                    ->default('active'),
            ]);
    }
}

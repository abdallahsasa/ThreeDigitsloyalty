<?php

namespace App\Filament\Shop\Resources\LoyaltyCards\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class LoyaltyCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('shop_id')
                    ->default(fn () => Filament::getTenant()?->id)
                    ->required(),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable(['name', 'phone'])
                    ->preload()
                    ->required(),
                Select::make('campaign_id')
                    ->relationship(
                        name: 'campaign',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->when(Filament::getTenant(), fn (Builder $q, $tenant) => $q->where('shop_id', $tenant->id))
                    )
                    ->preload()
                    ->required(),
                TextInput::make('token')
                    ->default(fn () => Str::random(32))
                    ->required()
                    ->maxLength(64)
                    ->unique(ignoreRecord: true)
                    ->readOnly(fn (string $operation): bool => $operation === 'edit'),
                TextInput::make('current_stamps_count')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Select::make('reward_status')
                    ->options([
                        'not_ready' => 'Not Ready',
                        'ready' => 'Ready to Redeem',
                        'redeemed' => 'Redeemed',
                        'expired' => 'Expired',
                    ])
                    ->required()
                    ->default('not_ready'),
                DateTimePicker::make('last_activity_date'),
            ]);
    }
}

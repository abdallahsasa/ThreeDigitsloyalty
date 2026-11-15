<?php

namespace App\Filament\Shop\Resources\Users\Schemas;

use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('shop_id')
                    ->default(fn () => Filament::getTenant()?->id)
                    ->required(),
                Select::make('branch_id')
                    ->relationship(
                        name: 'branch',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->when(Filament::getTenant(), fn (Builder $q, $tenant) => $q->where('shop_id', $tenant->id))
                    )
                    ->preload(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('roles')
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->whereIn('name', ['Shop Owner', 'Branch Manager', 'Cashier'])
                    )
                    ->multiple()
                    ->preload()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
            ]);
    }
}

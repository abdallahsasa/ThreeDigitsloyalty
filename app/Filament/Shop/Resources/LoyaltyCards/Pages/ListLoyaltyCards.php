<?php

namespace App\Filament\Shop\Resources\LoyaltyCards\Pages;

use App\Filament\Shop\Resources\LoyaltyCards\LoyaltyCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoyaltyCards extends ListRecords
{
    protected static string $resource = LoyaltyCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

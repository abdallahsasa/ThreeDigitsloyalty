<?php

namespace App\Filament\Shop\Resources\LoyaltyTransactions\Pages;

use App\Filament\Shop\Resources\LoyaltyTransactions\LoyaltyTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoyaltyTransactions extends ListRecords
{
    protected static string $resource = LoyaltyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

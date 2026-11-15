<?php

namespace App\Filament\Shop\Resources\LoyaltyTransactions\Pages;

use App\Filament\Shop\Resources\LoyaltyTransactions\LoyaltyTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoyaltyTransaction extends EditRecord
{
    protected static string $resource = LoyaltyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

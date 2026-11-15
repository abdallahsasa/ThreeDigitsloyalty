<?php

namespace App\Filament\Shop\Resources\LoyaltyTransactions\Pages;

use App\Filament\Shop\Resources\LoyaltyTransactions\LoyaltyTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLoyaltyTransaction extends CreateRecord
{
    protected static string $resource = LoyaltyTransactionResource::class;
}

<?php

namespace App\Filament\Shop\Resources\LoyaltyCards\Pages;

use App\Filament\Shop\Resources\LoyaltyCards\LoyaltyCardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoyaltyCard extends EditRecord
{
    protected static string $resource = LoyaltyCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

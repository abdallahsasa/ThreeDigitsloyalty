<?php

namespace App\Filament\Resources\LoyaltyCampaigns\Pages;

use App\Filament\Resources\LoyaltyCampaigns\LoyaltyCampaignResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoyaltyCampaign extends EditRecord
{
    protected static string $resource = LoyaltyCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

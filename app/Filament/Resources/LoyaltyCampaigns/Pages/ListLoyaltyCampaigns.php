<?php

namespace App\Filament\Resources\LoyaltyCampaigns\Pages;

use App\Filament\Resources\LoyaltyCampaigns\LoyaltyCampaignResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoyaltyCampaigns extends ListRecords
{
    protected static string $resource = LoyaltyCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

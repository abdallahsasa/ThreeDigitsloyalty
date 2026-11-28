<?php

namespace App\Filament\Shop\Resources\LoyaltyCampaigns\Pages;

use App\Filament\Shop\Resources\LoyaltyCampaigns\LoyaltyCampaignResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\Url;

class CreateLoyaltyCampaign extends CreateRecord
{
    protected static string $resource = LoyaltyCampaignResource::class;

    #[Url(as: 'onboarding')]
    public bool $fromOnboarding = false;

    protected function getRedirectUrl(): string
    {
        if ($this->fromOnboarding && ($tenant = Filament::getTenant())) {
            return url("/shop/{$tenant->id}/getting-started");
        }

        return parent::getRedirectUrl();
    }
}

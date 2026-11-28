<?php

namespace App\Filament\Shop\Resources\LoyaltyCampaigns\Pages;

use App\Filament\Shop\Resources\LoyaltyCampaigns\LoyaltyCampaignResource;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\Url;

class EditLoyaltyCampaign extends EditRecord
{
    protected static string $resource = LoyaltyCampaignResource::class;

    #[Url(as: 'onboarding')]
    public bool $fromOnboarding = false;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        if ($this->fromOnboarding && ($tenant = Filament::getTenant())) {
            return url("/shop/{$tenant->id}/getting-started");
        }

        return parent::getRedirectUrl();
    }
}

<?php

namespace App\Filament\Shop\Resources\Branches\Pages;

use App\Filament\Shop\Resources\Branches\BranchResource;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\Url;

class EditBranch extends EditRecord
{
    protected static string $resource = BranchResource::class;

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

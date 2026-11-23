<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class FilamentLoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = auth()->user();
        $panelId = Filament::getCurrentPanel()?->getId();

        if ($panelId === 'admin' && $user?->hasRole('Super Admin')) {
            return redirect()->intended('/admin');
        }

        if ($user?->shop_id) {
            return redirect()->to("/shop/{$user->shop_id}");
        }

        if ($user?->hasRole('Super Admin')) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended(Filament::getUrl() ?? '/');
    }
}

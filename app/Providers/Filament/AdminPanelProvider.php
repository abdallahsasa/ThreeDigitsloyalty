<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('ThreeDigits Loyalty')
            ->brandLogo(asset('images/logo.png'))
            ->darkModeBrandLogo(asset('images/logo-icon.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/logo-icon.png'))
            ->font('Montserrat')
            ->sidebarCollapsibleOnDesktop()
            ->spa()
            ->colors([
                'primary' => Color::hex('#743EE2'),
                'success' => Color::Emerald,
            ])
            ->renderHook(
                'panels::head.end',
                fn (): string => '<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet"><style>*, body, .fi-body, button, input, select, textarea { font-family: \'Montserrat\', \'Cairo\', sans-serif !important; }</style>'
            )
            ->renderHook(
                'panels::user-menu.before',
                fn (): string => '<a href="' . route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') . '" style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:999px;background:rgba(116,62,226,0.12);border:1px solid rgba(116,62,226,0.3);color:#743EE2;font-weight:700;font-size:13px;text-decoration:none;margin-inline-end:8px;">🌐 ' . (app()->getLocale() === 'ar' ? 'English' : 'عربي') . '</a>'
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // Removed default widgets
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                \App\Http\Middleware\SetLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

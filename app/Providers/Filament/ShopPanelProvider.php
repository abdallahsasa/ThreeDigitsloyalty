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

class ShopPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('shop')
            ->path('shop')
            ->login()
            ->passwordReset()
            ->tenant(\App\Models\Shop::class, ownershipRelationship: 'shop', slugAttribute: 'id')
            ->brandName('ThreeDigits Loyalty')
            ->brandLogo(asset('images/logo.png'))
            ->darkModeBrandLogo(asset('images/logo-icon.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/logo-icon.png'))
            ->font('Montserrat')
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::hex('#743EE2'),
                'success' => Color::Emerald,
            ])
            ->renderHook(
                'panels::head.end',
                function (): string {
                    $base = '<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet"><style>*, body, .fi-body, button, input, select, textarea { font-family: \'Montserrat\', \'Cairo\', sans-serif !important; }</style>';

                    if (! request()->routeIs('filament.shop.auth.*')) {
                        return $base;
                    }

                    return $base . <<<'HTML'
<style>
    body.fi-body, .fi-simple-layout { background: #010A29 !important; color: #f8fafc !important; }
    .fi-simple-main { background: #071033 !important; border: 1px solid rgba(148, 163, 184, 0.22) !important; border-radius: 20px !important; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35) !important; }
    .fi-simple-header-heading, .fi-simple-header-subheading, .fi-fo-field-wrp-label label, .fi-checkbox-label { color: #f8fafc !important; }
    .fi-input-wrp { background: #0C1742 !important; border-color: rgba(148, 163, 184, 0.25) !important; border-radius: 12px !important; }
    .fi-input { color: #f8fafc !important; }
    .fi-input::placeholder { color: #94A3B8 !important; opacity: 1 !important; }
    .fi-input-wrp:focus-within, .fi-btn:focus-visible, a:focus-visible { outline: 2px solid #A78BFA !important; outline-offset: 2px !important; }
    .fi-btn-color-primary { background: #743EE2 !important; border-radius: 999px !important; }
    .fi-btn-color-primary:hover { background: #5B21B6 !important; }
    .fi-link, .fi-simple-main a { color: #c4b5fd !important; }
</style>
HTML;
                }
            )
            ->renderHook(
                'panels::user-menu.before',
                fn(): string => '<a href="' . route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') . '" style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:999px;background:rgba(116,62,226,0.12);border:1px solid rgba(116,62,226,0.3);color:#743EE2;font-weight:700;font-size:13px;text-decoration:none;margin-inline-end:8px;">🌐 ' . (app()->getLocale() === 'ar' ? 'English' : 'عربي') . '</a>'
            )
            ->renderHook(
                'panels::auth.login.form.before',
                fn(): string => self::authLangBar()
            )
            ->renderHook(
                'panels::auth.password-reset.request.form.before',
                fn(): string => self::authLangBar()
            )
            ->renderHook(
                'panels::auth.password-reset.reset.form.before',
                fn(): string => self::authLangBar()
            )
            ->renderHook(
                'panels::auth.login.form.after',
                fn(): string => '<div style="margin-top:1rem;text-align:center;font-size:0.875rem;color:#cbd5e1;">' . (app()->getLocale() === 'ar' ? 'متجر جديد؟ ' : 'New store? ') . '<a href="' . route('merchant.register') . '" style="color:#c4b5fd;font-weight:700;text-decoration:underline;">' . (app()->getLocale() === 'ar' ? 'ابدأ مجاناً' : 'Start free') . '</a></div>'
            )
            ->discoverResources(in: app_path('Filament/Shop/Resources'), for: 'App\Filament\Shop\Resources')
            ->discoverPages(in: app_path('Filament/Shop/Pages'), for: 'App\Filament\Shop\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Shop/Widgets'), for: 'App\Filament\Shop\Widgets')
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

    private static function authLangBar(): string
    {
        $isAr = app()->getLocale() === 'ar';
        $targetLocale = $isAr ? 'en' : 'ar';
        $switchUrl = request()->fullUrlWithQuery(['lang' => $targetLocale]);
        $homeUrl = route($isAr ? 'home.ar' : 'home.en');
        $homeLabel = $isAr ? 'العودة للرئيسية' : 'Back to home';
        $langLabel = $isAr ? 'English' : 'عربي';

        return '<div style="display:flex;align-items:center;justify-content:space-between;gap:0.75rem;margin-bottom:1.25rem;">'
            . '<a href="' . e($homeUrl) . '" style="font-size:0.85rem;font-weight:700;color:#cbd5e1;text-decoration:none;display:inline-flex;align-items:center;min-height:44px;">' . e($homeLabel) . '</a>'
            . '<a href="' . e($switchUrl) . '" style="display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 0.9rem;border-radius:999px;background:rgba(116,62,226,0.16);border:1px solid rgba(167,139,250,0.4);color:#f8fafc;font-weight:700;font-size:0.85rem;text-decoration:none;">' . e($langLabel) . '</a>'
            . '</div>';
    }
}

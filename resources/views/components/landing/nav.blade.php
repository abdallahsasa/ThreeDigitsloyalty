@php
    $isAr = app()->getLocale() === 'ar';
    $homeUrl = route($isAr ? 'home.ar' : 'home.en');
    $switchHomeUrl = route($isAr ? 'home.en' : 'home.ar');
@endphp

<header x-data="{ mobileOpen: false }"
    style="position: sticky; top: 0; z-index: 50; background: rgba(1, 10, 41, 0.92); backdrop-filter: blur(14px); border-bottom: 1px solid rgba(148, 163, 184, 0.16);">
    <div class="dl-container" style="display: flex; align-items: center; justify-content: space-between; min-height: 70px; gap: 0.75rem;">
        <a href="{{ $homeUrl }}" style="display: flex; align-items: center; gap: 0.65rem; text-decoration: none; color: #f8fafc; flex-shrink: 0;">
            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 32px; width: auto; display: block;">
            <div style="line-height: 1.1;">
                <div style="font-weight: 800; font-size: 1.05rem; color: #f8fafc;">ThreeDigits</div>
                <div style="font-size: 0.62rem; font-weight: 700; letter-spacing: 0.18em; color: #c4b5fd;">LOYALTY</div>
            </div>
        </a>

        {{-- Desktop Navigation --}}
        <nav aria-label="{{ $isAr ? 'التنقل الرئيسي' : 'Primary navigation' }}" class="hidden md:flex" style="align-items: center; gap: 1.35rem;">
            <a href="#how-it-works" style="color: #cbd5e1; text-decoration: none; font-size: 0.92rem; font-weight: 600;">{{ __('landing.nav_how_it_works') }}</a>
            <a href="#merchant-tools" style="color: #cbd5e1; text-decoration: none; font-size: 0.92rem; font-weight: 600;">{{ $isAr ? 'أدوات المتجر' : 'Merchant tools' }}</a>
            <a href="#industries" style="color: #cbd5e1; text-decoration: none; font-size: 0.92rem; font-weight: 600;">{{ $isAr ? 'القطاعات' : 'Industries' }}</a>
            <a href="#pricing" style="color: #cbd5e1; text-decoration: none; font-size: 0.92rem; font-weight: 600;">{{ __('landing.nav_pricing') }}</a>
            <a href="{{ url('/shop/login') }}" style="color: #f8fafc; text-decoration: none; font-size: 0.92rem; font-weight: 700;">{{ __('landing.nav_login') }}</a>
        </nav>

        {{-- Desktop Actions --}}
        <div class="hidden md:flex" style="align-items: center; gap: 0.65rem;">
            <a href="{{ $switchHomeUrl }}"
               onclick="if (window.location.hash) { this.href = this.href.split('#')[0] + window.location.hash; }"
               style="padding: 0.45rem 0.85rem; border-radius: 999px; border: 1px solid rgba(148, 163, 184, 0.28); color: #f8fafc; text-decoration: none; font-size: 0.82rem; font-weight: 700;">
                {{ $isAr ? 'EN' : 'عربي' }}
            </a>
            <a href="{{ route('merchant.register') }}"
               onclick="window.trackFunnelEvent && window.trackFunnelEvent('primary_cta_clicked', { location: 'nav' })"
               class="dl-btn-primary" style="padding: 0.55rem 1.2rem; font-size: 0.88rem;">
                {{ __('landing.nav_cta') }}
            </a>
        </div>

        {{-- Mobile Actions --}}
        <div class="flex md:hidden" style="align-items: center; gap: 0.5rem;">
            <a href="{{ $switchHomeUrl }}"
               onclick="if (window.location.hash) { this.href = this.href.split('#')[0] + window.location.hash; }"
               style="padding: 0.4rem 0.75rem; border-radius: 999px; border: 1px solid rgba(148, 163, 184, 0.28); color: #f8fafc; text-decoration: none; font-size: 0.8rem; font-weight: 700;">
                {{ $isAr ? 'EN' : 'عربي' }}
            </a>
            <button type="button"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    aria-label="{{ $isAr ? 'فتح القائمة' : 'Toggle menu' }}"
                    style="width: 42px; height: 42px; border-radius: 10px; border: 1px solid rgba(148, 163, 184, 0.28); background: rgba(255,255,255,0.04); color: #f8fafc; display: inline-flex; align-items: center; justify-content: center; cursor: pointer;">
                <svg x-show="!mobileOpen" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
                <svg x-show="mobileOpen" x-cloak width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Dropdown --}}
    <div x-show="mobileOpen" x-transition x-cloak class="md:hidden" style="border-top: 1px solid rgba(148, 163, 184, 0.16); background: #071033;">
        <div class="dl-container" style="padding-top: 1rem; padding-bottom: 1.25rem; display: grid; gap: 0.75rem;">
            <a href="#how-it-works" @click="mobileOpen = false; $nextTick(() => { const h = document.querySelector('#how-it-works h2, #how-it-works'); if (h) { h.setAttribute('tabindex', '-1'); h.focus({ preventScroll: true }); } })" style="color: #f8fafc; text-decoration: none; font-size: 0.95rem; font-weight: 600; padding: 0.55rem 0; min-height: 44px; display: flex; align-items: center;">{{ __('landing.nav_how_it_works') }}</a>
            <a href="#merchant-tools" @click="mobileOpen = false; $nextTick(() => { const h = document.querySelector('#merchant-tools h2, #merchant-tools'); if (h) { h.setAttribute('tabindex', '-1'); h.focus({ preventScroll: true }); } })" style="color: #f8fafc; text-decoration: none; font-size: 0.95rem; font-weight: 600; padding: 0.55rem 0; min-height: 44px; display: flex; align-items: center;">{{ $isAr ? 'أدوات المتجر' : 'Merchant tools' }}</a>
            <a href="#industries" @click="mobileOpen = false; $nextTick(() => { const h = document.querySelector('#industries h2, #industries'); if (h) { h.setAttribute('tabindex', '-1'); h.focus({ preventScroll: true }); } })" style="color: #f8fafc; text-decoration: none; font-size: 0.95rem; font-weight: 600; padding: 0.55rem 0; min-height: 44px; display: flex; align-items: center;">{{ $isAr ? 'القطاعات' : 'Industries' }}</a>
            <a href="#pricing" @click="mobileOpen = false; $nextTick(() => { const h = document.querySelector('#pricing h2, #pricing'); if (h) { h.setAttribute('tabindex', '-1'); h.focus({ preventScroll: true }); } })" style="color: #f8fafc; text-decoration: none; font-size: 0.95rem; font-weight: 600; padding: 0.55rem 0; min-height: 44px; display: flex; align-items: center;">{{ __('landing.nav_pricing') }}</a>
            <a href="{{ url('/shop/login') }}" style="color: #cbd5e1; text-decoration: none; font-size: 0.95rem; font-weight: 700; padding: 0.4rem 0;">{{ __('landing.nav_login') }}</a>
            <a href="{{ route('merchant.register') }}"
               onclick="window.trackFunnelEvent && window.trackFunnelEvent('primary_cta_clicked', { location: 'mobile_nav' })"
               class="dl-btn-primary" style="width: 100%; margin-top: 0.25rem;">
                {{ __('landing.nav_cta') }}
            </a>
        </div>
    </div>
</header>

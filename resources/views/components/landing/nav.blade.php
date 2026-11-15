<header
    style="position: sticky; top: 0; z-index: 50; background: rgba(7, 8, 13, 0.78); backdrop-filter: blur(18px); border-bottom: 1px solid rgba(255,255,255,0.07);">
    <div class="dl-container"
        style="display: flex; align-items: center; justify-content: space-between; height: 74px; gap: 1rem;">
        <a href="/" style="display: flex; align-items: center; gap: 0.7rem; text-decoration: none; color: #f8fafc;">
            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty"
                style="height: 36px; width: auto; display: block;">
            <div style="line-height: 1.05;">
                <div style="font-weight: 800; font-size: 1.15rem; letter-spacing: -0.02em; color: #f8fafc;">ThreeDigits
                </div>
                <div style="font-size: 0.62rem; font-weight: 700; letter-spacing: 0.22em; color: #818cf8;">LOYALTY</div>
            </div>
        </a>

        <nav style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            <a href="#features"
                style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 600;">{{ __('landing.nav_features') }}</a>
            <a href="#clients"
                style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 600;">{{ app()->getLocale() === 'ar' ? 'عملاؤنا' : 'Clients' }}</a>
            <a href="#pricing"
                style="color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 600;">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</a>
            <a href="{{ url('/shop/login') }}"
                style="color: #e2e8f0; text-decoration: none; font-size: 0.9rem; font-weight: 600;">{{ __('landing.nav_login') }}</a>
        </nav>

        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <a href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
                style="padding: 0.45rem 0.85rem; border-radius: 999px; border: 1px solid rgba(255,255,255,0.14); color: #e2e8f0; text-decoration: none; font-size: 0.8rem; font-weight: 700;">
                {{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}
            </a>
            <a href="{{ url('/admin') }}" class="dl-btn-gold" style="padding: 0.55rem 1.15rem; font-size: 0.85rem;">
                {{ __('landing.nav_admin') }}
            </a>
        </div>
    </div>
</header>
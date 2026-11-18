@php
    $isAr = app()->getLocale() === 'ar';
    $supportEmail = config('loyalty.agency.support_email');
    $hasConfiguredEmail = !empty($supportEmail) && !str_ends_with((string) $supportEmail, '@example.com');
@endphp

<footer style="border-top: 1px solid rgba(148, 163, 184, 0.16); background: #010A29; padding: 3rem 0 2rem; color: #cbd5e1; font-size: 0.9rem;">
    <div class="dl-container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.65rem; color: #f8fafc; font-weight: 800; font-size: 1.05rem; margin-bottom: 0.65rem;">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 30px; width: auto; display: block;">
                    <span>ThreeDigits Loyalty</span>
                </div>
                <p style="margin: 0 0 0.65rem; color: #cbd5e1; line-height: 1.65; font-size: 0.88rem;">
                    {{ __('landing.footer_agency') }}
                </p>
                <a href="{{ config('loyalty.agency.url', 'https://threedigitsoftware.com/') }}" target="_blank" rel="noopener noreferrer"
                   class="ltr-inline" style="color: #c4b5fd; font-weight: 700; text-decoration: underline; font-size: 0.86rem;">
                    threedigitsoftware.com
                </a>
            </div>

            <div>
                <h4 style="color: #f8fafc; font-weight: 800; margin: 0 0 0.75rem; font-size: 0.95rem;">{{ __('landing.footer_product') }}</h4>
                <div style="display: grid; gap: 0.5rem;">
                    <a href="#how-it-works" style="color: #cbd5e1; text-decoration: none;">{{ __('landing.nav_how_it_works') }}</a>
                    <a href="#features" style="color: #cbd5e1; text-decoration: none;">{{ __('landing.nav_features') }}</a>
                    <a href="#pricing" style="color: #cbd5e1; text-decoration: none;">{{ __('landing.nav_pricing') }}</a>
                </div>
            </div>

            <div>
                <h4 style="color: #f8fafc; font-weight: 800; margin: 0 0 0.75rem; font-size: 0.95rem;">{{ __('landing.footer_company') }}</h4>
                <div style="display: grid; gap: 0.5rem;">
                    <a href="{{ route('merchant.register') }}" style="color: #cbd5e1; text-decoration: none;">{{ __('landing.nav_cta') }}</a>
                    <a href="{{ url('/shop/login') }}" style="color: #cbd5e1; text-decoration: none;">{{ __('landing.nav_login') }}</a>
                    <a href="{{ route('lang.switch', $isAr ? 'en' : 'ar') }}" style="color: #cbd5e1; text-decoration: none;">
                        {{ $isAr ? 'English' : 'عربي' }}
                    </a>
                </div>
            </div>

            <div>
                <h4 style="color: #f8fafc; font-weight: 800; margin: 0 0 0.75rem; font-size: 0.95rem;">{{ __('landing.footer_support') }}</h4>
                <div style="display: grid; gap: 0.55rem; font-size: 0.88rem;">
                    @if($hasConfiguredEmail)
                        <a href="mailto:{{ $supportEmail }}" class="ltr-inline" style="color: #c4b5fd; text-decoration: underline;">
                            {{ $supportEmail }}
                        </a>
                    @else
                        <a href="{{ config('loyalty.agency.url', 'https://threedigitsoftware.com/') }}" target="_blank" rel="noopener noreferrer" style="color: #c4b5fd; text-decoration: underline;">
                            {{ $isAr ? 'الدعم عبر موقع Three Digit Software' : 'Support via Three Digit Software' }}
                        </a>
                    @endif
                    <span style="color: #cbd5e1;">
                        {{ $isAr ? 'بيانات متجرك منفصلة عن بيانات المتاجر الأخرى.' : 'Your store’s data is kept separate from other stores.' }}
                    </span>
                    <span style="color: #94a3b8; font-size: 0.84rem;">
                        {{ $isAr ? 'سياسة الخصوصية وشروط الاستخدام: قيد النشر قبل الإطلاق الرسمي.' : 'Privacy Policy & Terms of Service: pending publication prior to public launch.' }}
                    </span>
                </div>
            </div>
        </div>

        <div style="border-top: 1px solid rgba(148, 163, 184, 0.14); padding-top: 1.25rem; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; font-size: 0.84rem; color: #94a3b8;">
            <span>© <span class="ltr-inline">{{ date('Y') }}</span> ThreeDigits Loyalty — {{ __('landing.footer_agency') }}</span>
            <span>{{ $isAr ? 'جميع الحقوق محفوظة' : 'All rights reserved' }}</span>
        </div>
    </div>
</footer>

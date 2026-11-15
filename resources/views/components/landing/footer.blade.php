<footer style="border-top: 1px solid rgba(255,255,255,0.07); background: #010A29; padding: 3.5rem 0 2.5rem; color: #94a3b8; font-size: 0.9rem;">
    <div class="dl-container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 2rem; margin-bottom: 2.5rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.65rem; color: #f8fafc; font-weight: 800; font-size: 1.1rem; margin-bottom: 0.75rem;">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 30px; width: auto; display: block;">
                    <span>ThreeDigits Loyalty</span>
                </div>
                <p style="margin: 0; color: #64748b; line-height: 1.6;">
                    {{ app()->getLocale() === 'ar' ? 'منصة بطاقات الولاء الرقمية الحديثة للمتاجر والعلامات التجارية.' : 'Modern digital loyalty & passbook infrastructure for ambitious brands.' }}
                </p>
            </div>

            <div>
                <h4 style="color: #f8fafc; font-weight: 700; margin: 0 0 0.85rem;">{{ __('landing.footer_product') }}</h4>
                <div style="display: grid; gap: 0.5rem;">
                    <a href="#features" style="color: #94a3b8; text-decoration: none;">{{ __('landing.nav_features') }}</a>
                    <a href="#pricing" style="color: #94a3b8; text-decoration: none;">{{ app()->getLocale() === 'ar' ? 'الأسعار' : 'Pricing' }}</a>
                </div>
            </div>

            <div>
                <h4 style="color: #f8fafc; font-weight: 700; margin: 0 0 0.85rem;">{{ __('landing.footer_company') }}</h4>
                <div style="display: grid; gap: 0.5rem;">
                    <a href="#clients" style="color: #94a3b8; text-decoration: none;">{{ app()->getLocale() === 'ar' ? 'عملاؤنا' : 'Clients' }}</a>
                    <a href="{{ url('/shop/login') }}" style="color: #94a3b8; text-decoration: none;">{{ __('landing.nav_login') }}</a>
                </div>
            </div>

            <div>
                <h4 style="color: #f8fafc; font-weight: 700; margin: 0 0 0.85rem;">{{ __('landing.footer_legal') }}</h4>
                <div style="display: grid; gap: 0.5rem;">
                    <a href="{{ url('/admin') }}" style="color: #94a3b8; text-decoration: none;">{{ __('landing.nav_admin') }}</a>
                    <a href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" style="color: #94a3b8; text-decoration: none;">
                        {{ app()->getLocale() === 'ar' ? 'English' : 'عربي' }}
                    </a>
                </div>
            </div>
        </div>

        <div style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.5rem; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem; font-size: 0.82rem; color: #64748b;">
            <span>© {{ date('Y') }} ThreeDigits Loyalty.</span>
            <span>{{ app()->getLocale() === 'ar' ? 'مصمم للتجارة الحديثة' : 'Crafted for modern commerce.' }}</span>
        </div>
    </div>
</footer>

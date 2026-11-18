@php $isAr = app()->getLocale() === 'ar'; @endphp

<section class="dl-section" style="background: #040D30; border-top: 1px solid rgba(148, 163, 184, 0.12);">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ __('landing.dashboard_sample_badge') }}</span>
            <h2>{{ __('landing.dashboard_title') }}</h2>
            <p>{{ __('landing.dashboard_subtitle') }}</p>
        </div>

        <div class="dl-card" style="padding: 1.5rem; background: #071033; border-color: rgba(196, 181, 253, 0.24);">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 0.75rem; padding-bottom: 1rem; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(148, 163, 184, 0.14);">
                <div style="font-weight: 800; color: #f8fafc; font-size: 0.98rem;">
                    {{ $isAr ? 'معاينة لوحة التاجر — بيانات توضيحية' : 'Merchant Portal Preview — Sample Data' }}
                </div>
                <span style="padding: 0.25rem 0.75rem; border-radius: 999px; background: rgba(116, 62, 226, 0.16); color: #c4b5fd; font-size: 0.75rem; font-weight: 700;">
                    {{ __('landing.dashboard_sample_badge') }}
                </span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                <div style="padding: 1.15rem; border-radius: 14px; background: #0C1742; border: 1px solid rgba(148, 163, 184, 0.16);">
                    <div style="font-size: 0.85rem; color: #cbd5e1;">{{ $isAr ? 'البطاقات النشطة (نموذج)' : 'Active Cards (Sample)' }}</div>
                    <div class="ltr-inline" style="font-size: 1.85rem; font-weight: 800; color: #f8fafc; margin-top: 0.35rem;">340</div>
                </div>
                <div style="padding: 1.15rem; border-radius: 14px; background: #0C1742; border: 1px solid rgba(148, 163, 184, 0.16);">
                    <div style="font-size: 0.85rem; color: #cbd5e1;">{{ $isAr ? 'أختام هذا الأسبوع (نموذج)' : 'Stamps This Week (Sample)' }}</div>
                    <div class="ltr-inline" style="font-size: 1.85rem; font-weight: 800; color: #c4b5fd; margin-top: 0.35rem;">128</div>
                </div>
                <div style="padding: 1.15rem; border-radius: 14px; background: #0C1742; border: 1px solid rgba(148, 163, 184, 0.16);">
                    <div style="font-size: 0.85rem; color: #cbd5e1;">{{ $isAr ? 'مكافآت مستبدلة (نموذج)' : 'Rewards Redeemed (Sample)' }}</div>
                    <div class="ltr-inline" style="font-size: 1.85rem; font-weight: 800; color: #10b981; margin-top: 0.35rem;">24</div>
                </div>
            </div>

            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; padding: 1rem 1.15rem; border-radius: 14px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(148, 163, 184, 0.12);">
                <div style="font-size: 0.9rem; color: #cbd5e1;">
                    {{ $isAr
                        ? 'تشمل اللوحة الفعلية: ماسح QR للكاشير، إدارة الفروع والموظفين، حملات الولاء، وسجل المعاملات.'
                        : 'The live portal includes: Cashier QR Scanner, Branches & Staff Roles, Loyalty Campaigns, and Transaction History.' }}
                </div>
                <a href="{{ route('merchant.register') }}" class="dl-btn-primary" style="padding: 0.65rem 1.25rem; font-size: 0.88rem;">
                    {{ __('landing.hero_cta_1') }}
                </a>
            </div>
        </div>
    </div>
</section>

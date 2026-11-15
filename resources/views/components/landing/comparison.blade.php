<section class="dl-section">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ app()->getLocale() === 'ar' ? 'المقارنة' : 'Why Switch' }}</span>
            <h2>{{ __('landing.compare_title') }}</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 1.5rem; max-width: 960px; margin: 0 auto;">
            <div class="dl-card" style="padding: 2rem; opacity: 0.78;">
                <h3 style="font-size: 1.2rem; font-weight: 700; color: #94a3b8; margin: 0 0 1.25rem;">{{ __('landing.compare_traditional') }}</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: grid; gap: 0.85rem; color: #64748b; font-size: 0.95rem;">
                    <li>✕ {{ app()->getLocale() === 'ar' ? 'تُفقد أو تُنسى بسهولة' : 'Easily lost, damaged, or forgotten' }}</li>
                    <li>✕ {{ app()->getLocale() === 'ar' ? 'لا توجد بيانات أو تحليلات للعملاء' : 'Zero customer analytics or visit history' }}</li>
                    <li>✕ {{ app()->getLocale() === 'ar' ? 'تكاليف طباعة متكررة' : 'Ongoing printing and design costs' }}</li>
                    <li>✕ {{ app()->getLocale() === 'ar' ? 'عرضة للتلاعب والأختام الوهمية' : 'Vulnerable to manual stamp fraud' }}</li>
                </ul>
            </div>

            <div class="dl-card" style="padding: 2rem; border-color: rgba(116,62,226,0.45); background: linear-gradient(160deg, #0B1644 0%, #050C2A 100%);">
                <h3 class="dl-gold-text" style="font-size: 1.2rem; font-weight: 800; margin: 0 0 1.25rem;">{{ __('landing.compare_threedigits') }}</h3>
                <ul style="list-style: none; padding: 0; margin: 0; display: grid; gap: 0.85rem; color: #e2e8f0; font-size: 0.95rem;">
                    <li><span style="color: #10b981; font-weight: 800;">✓</span> {{ app()->getLocale() === 'ar' ? 'بطاقة رقمية دائمة برمز QR فريد' : 'Always-available QR pass on any smartphone' }}</li>
                    <li><span style="color: #10b981; font-weight: 800;">✓</span> {{ app()->getLocale() === 'ar' ? 'تحليلات فورية وسجل معاملات كامل' : 'Real-time analytics & immutable audit trail' }}</li>
                    <li><span style="color: #10b981; font-weight: 800;">✓</span> {{ app()->getLocale() === 'ar' ? 'إطلاق الحملات وتحديثها في ثوانٍ' : 'Launch & update campaigns in seconds' }}</li>
                    <li><span style="color: #10b981; font-weight: 800;">✓</span> {{ app()->getLocale() === 'ar' ? 'صلاحيات كاشير محمية لكل فرع' : 'Role-based cashier & branch permissions' }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

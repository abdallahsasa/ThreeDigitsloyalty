<section class="dl-section">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Merchant Console' }}</span>
            <h2>{{ __('landing.dashboard_title') }}</h2>
        </div>

        <div class="dl-card" style="padding: 2rem; background: linear-gradient(145deg, #09143E 0%, #040D30 100%); border-color: rgba(116,62,226,0.28);">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
                <div style="padding: 1.25rem; border-radius: 16px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <div style="font-size: 0.8rem; color: #94a3b8;">{{ app()->getLocale() === 'ar' ? 'إجمالي الأختام' : 'Total Stamps Issued' }}</div>
                    <div class="dl-gold-text" style="font-size: 2.2rem; font-weight: 800; margin-top: 0.35rem;">48,920</div>
                </div>
                <div style="padding: 1.25rem; border-radius: 16px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <div style="font-size: 0.8rem; color: #94a3b8;">{{ app()->getLocale() === 'ar' ? 'معدل عودة العملاء' : 'Repeat Visit Lift' }}</div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #10b981; margin-top: 0.35rem;">+42%</div>
                </div>
                <div style="padding: 1.25rem; border-radius: 16px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                    <div style="font-size: 0.8rem; color: #94a3b8;">{{ app()->getLocale() === 'ar' ? 'وقت المسح' : 'Average Scan Time' }}</div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #f8fafc; margin-top: 0.35rem;">1.4s</div>
                </div>
            </div>
        </div>
    </div>
</section>

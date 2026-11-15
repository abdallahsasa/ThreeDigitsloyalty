<section class="dl-section" style="background: #040D30;">
    <div class="dl-container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; text-align: center;">
            <div class="dl-card" style="padding: 1.75rem;">
                <div class="dl-gold-text" style="font-size: 2.25rem; font-weight: 800;">3x</div>
                <div style="color: #94a3b8; font-size: 0.9rem; margin-top: 0.35rem;">{{ app()->getLocale() === 'ar' ? 'زيادة في تكرار الزيارات' : 'Higher Repeat Visits' }}</div>
            </div>
            <div class="dl-card" style="padding: 1.75rem;">
                <div style="font-size: 2.25rem; font-weight: 800; color: #10b981;">0</div>
                <div style="color: #94a3b8; font-size: 0.9rem; margin-top: 0.35rem;">{{ app()->getLocale() === 'ar' ? 'تطبيقات مطلوبة للتحميل' : 'Apps to Download' }}</div>
            </div>
            <div class="dl-card" style="padding: 1.75rem;">
                <div style="font-size: 2.25rem; font-weight: 800; color: #f8fafc;">99.9%</div>
                <div style="color: #94a3b8; font-size: 0.9rem; margin-top: 0.35rem;">{{ app()->getLocale() === 'ar' ? 'توافر واستقرار المنصة' : 'Platform Uptime' }}</div>
            </div>
        </div>
    </div>
</section>

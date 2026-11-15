<section id="features" class="dl-section" style="background: #040D30; border-top: 1px solid rgba(255,255,255,0.05);">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ app()->getLocale() === 'ar' ? 'المزايا' : 'Capabilities' }}</span>
            <h2>{{ __('landing.features_title') }}</h2>
            <p>{{ __('landing.features_subtitle') }}</p>
        </div>

        @php
            $items = app()->getLocale() === 'ar' ? [
                ['icon' => '⚡', 'title' => 'مسح QR فوري', 'desc' => 'أضف الأختام أو استبدل المكافآت في ثوانٍ من أي جهاز كاشير.'],
                ['icon' => '🏢', 'title' => 'إدارة متعددة الفروع', 'desc' => 'تحكم كامل في الفروع والموظفين وصلاحيات الكاشير من مكان واحد.'],
                ['icon' => '🎯', 'title' => 'حملات ولاء مرنة', 'desc' => 'خصص عدد المشتريات والمكافآت وتواريخ الصلاحية لكل حملة.'],
                ['icon' => '📊', 'title' => 'تحليلات لحظية', 'desc' => 'راقب نمو العملاء ومعدل التكرار والمكافآت المصروفة يومياً.'],
                ['icon' => '🔒', 'title' => 'عزل بيانات تام', 'desc' => 'بنية متعددة المستأجرين تضمن خصوصية بيانات كل متجر بشكل كامل.'],
                ['icon' => '🌐', 'title' => 'دعم العربية والإنجليزية', 'desc' => 'تجربة متكاملة تدعم RTL و LTR للعملاء وفريق العمل.'],
            ] : [
                ['icon' => '⚡', 'title' => 'Instant QR Checkout', 'desc' => 'Issue stamps or redeem rewards in under two seconds from any POS or phone.'],
                ['icon' => '🏢', 'title' => 'Multi-Branch Control', 'desc' => 'Manage branches, branch managers, and cashier permissions in one console.'],
                ['icon' => '🎯', 'title' => 'Flexible Campaigns', 'desc' => 'Configure stamp thresholds, reward prizes, and campaign windows effortlessly.'],
                ['icon' => '📊', 'title' => 'Real-Time Analytics', 'desc' => 'Track daily scans, repeat customer growth, and reward redemptions live.'],
                ['icon' => '🔒', 'title' => 'Tenant-Isolated Data', 'desc' => 'Strict shop-level isolation ensures every merchant’s data stays private.'],
                ['icon' => '🌐', 'title' => 'Bilingual RTL & LTR', 'desc' => 'Native English and Arabic (Cairo) passbook experience out of the box.'],
            ];
        @endphp

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 1.25rem;">
            @foreach($items as $item)
                <div class="dl-card" style="padding: 1.75rem;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(116,62,226,0.12); border: 1px solid rgba(116,62,226,0.28); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 1.15rem;">
                        {{ $item['icon'] }}
                    </div>
                    <h3 style="font-size: 1.12rem; font-weight: 700; margin: 0 0 0.5rem; color: #f8fafc;">{{ $item['title'] }}</h3>
                    <p style="font-size: 0.92rem; color: #94a3b8; line-height: 1.6; margin: 0;">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

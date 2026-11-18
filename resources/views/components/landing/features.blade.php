@php
    $isAr = app()->getLocale() === 'ar';
    $items = $isAr ? [
        [
            'icon' => 'qr',
            'title' => 'ماسح QR وبحث برقم الهاتف',
            'desc' => 'يضيف الكاشير الأختام أو يستبدل المكافأة عبر مسح رمز العميل أو البحث برقم هاتفه.',
        ],
        [
            'icon' => 'branch',
            'title' => 'تعدد الفروع وصلاحيات الموظفين',
            'desc' => 'أنشئ عدة فروع وعيّن مدراء الفروع والكاشير بصلاحيات مخصصة.',
        ],
        [
            'icon' => 'gift',
            'title' => 'حملات ولاء قابلة للتخصيص',
            'desc' => 'حدد عدد المشتريات المطلوبة، اسم المكافأة، وفترة صلاحية الحملة.',
        ],
        [
            'icon' => 'chart',
            'title' => 'سجل معاملات وإحصائيات واضحة',
            'desc' => 'تابع عدد العملاء، الأختام المضافة، والمكافآت المصروفة من لوحة متجرك.',
        ],
        [
            'icon' => 'shield',
            'title' => 'خصوصية وعزل بيانات المتاجر',
            'desc' => 'بيانات متجرك منفصلة عن بيانات المتاجر الأخرى.',
        ],
        [
            'icon' => 'globe',
            'title' => 'دعم العربية والإنجليزية',
            'desc' => 'واجهة وتقارير ورسائل ترحيب تدعم العربية (RTL) والإنجليزية (LTR).',
        ],
    ] : [
        [
            'icon' => 'qr',
            'title' => 'QR Scanner & Phone Lookup',
            'desc' => 'Cashiers add stamps or redeem rewards by scanning the customer’s QR or searching their phone number.',
        ],
        [
            'icon' => 'branch',
            'title' => 'Multi-Branch & Staff Permissions',
            'desc' => 'Manage multiple branches and assign Branch Manager and Cashier roles.',
        ],
        [
            'icon' => 'gift',
            'title' => 'Customizable Stamp Campaigns',
            'desc' => 'Configure required stamp counts, reward descriptions, and active dates.',
        ],
        [
            'icon' => 'chart',
            'title' => 'Transaction Log & Analytics',
            'desc' => 'View customer growth, issued stamps, and redeemed rewards in your store dashboard.',
        ],
        [
            'icon' => 'shield',
            'title' => 'Isolated Store Data',
            'desc' => 'Your store’s data is kept separate from other stores.',
        ],
        [
            'icon' => 'globe',
            'title' => 'Arabic & English Support',
            'desc' => 'Full RTL Arabic and LTR English support for both your team and customers.',
        ],
    ];
@endphp

<section id="features" class="dl-section">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ __('landing.nav_features') }}</span>
            <h2>{{ __('landing.features_title') }}</h2>
            <p>{{ __('landing.features_subtitle') }}</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
            @foreach($items as $item)
                <div class="dl-card" style="padding: 1.65rem;">
                    <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(116, 62, 226, 0.16); border: 1px solid rgba(196, 181, 253, 0.28); color: #c4b5fd; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                        @switch($item['icon'])
                            @case('qr')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                    <path d="M14 14h3v3h-3z"></path>
                                    <path d="M18 18h3v3h-3z"></path>
                                </svg>
                                @break
                            @case('branch')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 21h18"></path>
                                    <path d="M5 21V7l8-4v18"></path>
                                    <path d="M19 21V11l-6-4"></path>
                                </svg>
                                @break
                            @case('gift')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 12 20 22 4 22 4 12"></polyline>
                                    <rect x="2" y="7" width="20" height="5"></rect>
                                    <line x1="12" y1="22" x2="12" y2="7"></line>
                                    <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                                    <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                                </svg>
                                @break
                            @case('chart')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                                @break
                            @case('shield')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                                @break
                            @default
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                </svg>
                        @endswitch
                    </div>
                    <h3 style="font-size: 1.08rem; font-weight: 800; margin: 0 0 0.45rem; color: #f8fafc;">{{ $item['title'] }}</h3>
                    <p style="font-size: 0.92rem; color: #cbd5e1; line-height: 1.65; margin: 0;">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

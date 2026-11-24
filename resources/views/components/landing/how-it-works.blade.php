@php
    $isAr = app()->getLocale() === 'ar';
    $steps = $isAr ? [
        [
            'num' => '1',
            'img' => 'images/landing/step-1-scan.webp',
            'alt' => 'يد عميل تمسح رمز QR على طاولة الكاشير في المقهى',
            'badge' => 'كاميرا الهاتف',
            'ui_pill' => 'مسح رمز المتجر · بدون تطبيق',
            'ui_sub' => 'يفتح الرابط مباشرة في المتصفح',
            'title' => 'امسح رمز QR الخاص بالمتجر',
            'desc' => 'يوجّه العميل كاميرا هاتفه نحو لوحة QR عند الكاشير ويدخل اسمه ورقم جواله في ثوانٍ.',
        ],
        [
            'num' => '2',
            'img' => 'images/landing/step-2-card.webp',
            'alt' => 'عميلة تفتح بطاقة الولاء الرقمية على شاشة هاتفها داخل المقهى',
            'badge' => 'بطاقة المتصفح',
            'ui_pill' => 'مقهى الياسمين · 5 من 6 أختام',
            'ui_sub' => 'رمز QR شخصي جاهز للمسح',
            'title' => 'افتح البطاقة على الهاتف',
            'desc' => 'تظهر بطاقة متجرك فوراً في متصفح العميل مع عدد الأختام المتبقية ورمز QR الخاص به.',
        ],
        [
            'num' => '3',
            'img' => 'images/landing/step-3-stamp.webp',
            'alt' => 'باريستا يسلّم كوب قهوة للعميلة عند الكاشير مع إضافة ختم الزيارة',
            'badge' => 'عند الدفع',
            'ui_pill' => '+1 ختم تمت إضافته · 6 من 6',
            'ui_sub' => 'مكافأتك جاهزة: قهوة مجانية',
            'title' => 'احصل على ختم عند الدفع',
            'desc' => 'يمسح الكاشير بطاقة العميل أو يبحث برقم هاتفه ليضيف الختم أو يصرف المكافأة فوراً.',
        ],
    ] : [
        [
            'num' => '1',
            'img' => 'images/landing/step-1-scan.webp',
            'alt' => 'Customer holding a smartphone toward a tabletop QR sign at a café counter',
            'badge' => 'Phone camera',
            'ui_pill' => 'Store QR scanned · No app needed',
            'ui_sub' => 'Opens straight in mobile browser',
            'title' => 'Scan the store QR',
            'desc' => 'Customers point their phone camera at your counter sign and enter their name and mobile number.',
        ],
        [
            'num' => '2',
            'img' => 'images/landing/step-2-card.webp',
            'alt' => 'Customer viewing their digital loyalty card on a phone at a sunlit café counter',
            'badge' => 'Browser card',
            'ui_pill' => 'Jasmine Café · 5 of 6 stamps',
            'ui_sub' => 'Personal QR ready at checkout',
            'title' => 'Open the card on a phone',
            'desc' => 'Your branded stamp card opens immediately in their browser with live stamp progress and their QR code.',
        ],
        [
            'num' => '3',
            'img' => 'images/landing/step-3-stamp.webp',
            'alt' => 'Barista handing a latte to a smiling customer at the café checkout counter',
            'badge' => 'At checkout',
            'ui_pill' => '+1 Stamp added · 6 of 6',
            'ui_sub' => 'Free coffee unlocked',
            'title' => 'Earn a stamp at checkout',
            'desc' => 'Your team scans the customer’s QR—or looks up their phone number—to stamp visits and redeem rewards.',
        ],
    ];
@endphp

<section id="how-it-works" class="dl-section" style="background: #FAF8F5; color: #0F172A; border-top: 1px solid #E7E2DA; border-bottom: 1px solid #E7E2DA;">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow" style="background: #EFEAFE; border-color: rgba(116, 62, 226, 0.25); color: #5B21B6;">
                {{ __('landing.nav_how_it_works') }}
            </span>
            <h2 style="color: #0F172A;">{{ __('landing.how_title') }}</h2>
            <p style="color: #475569;">{{ __('landing.how_subtitle') }}</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.75rem;">
            @foreach($steps as $step)
                <article style="background: #FFFFFF; border: 1px solid #E5E0D8; border-radius: 22px; overflow: hidden; box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06); display: flex; flex-direction: column;">
                    {{-- Coordinated Café Photo + Separate Readable Bilingual HTML UI Overlay --}}
                    <div style="position: relative; aspect-ratio: 4 / 3; background: #EAE4DC; overflow: hidden;">
                        <img src="{{ asset($step['img']) }}"
                             alt="{{ $step['alt'] }}"
                             width="800"
                             height="600"
                             loading="lazy"
                             style="width: 100%; height: 100%; object-fit: cover; display: block;">

                        {{-- Step Number Badge --}}
                        <div style="position: absolute; top: 0.9rem; inset-inline-start: 0.9rem; display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.3rem 0.75rem; border-radius: 999px; background: rgba(1, 10, 41, 0.88); backdrop-filter: blur(8px); color: #ffffff; font-size: 0.78rem; font-weight: 800;">
                            <span style="width: 20px; height: 20px; border-radius: 999px; background: #743EE2; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">{{ $step['num'] }}</span>
                            <span>{{ $step['badge'] }}</span>
                        </div>

                        {{-- Separate Bilingual Product UI Chip Over Photo --}}
                        <div style="position: absolute; bottom: 0.8rem; inset-inline: 0.8rem; padding: 0.6rem 0.85rem; border-radius: 14px; background: rgba(1, 10, 41, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(196, 181, 253, 0.35); color: #f8fafc; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                            <div style="min-width: 0;">
                                <div style="font-size: 0.82rem; font-weight: 800; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $step['ui_pill'] }}</div>
                                <div style="font-size: 0.74rem; color: #cbd5e1; font-weight: 600;">{{ $step['ui_sub'] }}</div>
                            </div>
                            <span aria-hidden="true" style="width: 26px; height: 26px; border-radius: 999px; background: rgba(116, 62, 226, 0.32); border: 1px solid #c4b5fd; color: #ede9fe; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.78rem; font-weight: 800;">✓</span>
                        </div>
                    </div>

                    {{-- Step Title & Short Explanation --}}
                    <div style="padding: 1.4rem 1.5rem 1.6rem;">
                        <h3 style="margin: 0 0 0.45rem; font-size: 1.15rem; font-weight: 800; color: #0F172A; line-height: 1.35;">
                            {{ $step['title'] }}
                        </h3>
                        <p style="margin: 0; font-size: 0.95rem; color: #475569; line-height: 1.65;">
                            {{ $step['desc'] }}
                        </p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

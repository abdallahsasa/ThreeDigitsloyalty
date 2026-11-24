@php
    $isAr = app()->getLocale() === 'ar';
    $steps = $isAr ? [
        [
            'num' => '1',
            'img' => 'images/landing/step-1-scan.webp',
            'alt' => 'يد عميل تمسح رمز QR على طاولة الكاشير في المقهى',
            'title' => 'امسح رمز QR على الطاولة',
            'desc' => 'يوجّه العميل كاميرا هاتفه نحو لوحة QR عند الكاشير — بدون تحميل تطبيق.',
            'badge' => 'كاميرا الهاتف',
        ],
        [
            'num' => '2',
            'img' => 'images/landing/step-2-card.webp',
            'alt' => 'فتح بطاقة الولاء الرقمية على شاشة الهاتف داخل المقهى',
            'title' => 'افتح بطاقة الولاء على هاتفك',
            'desc' => 'تفتح البطاقة فوراً في المتصفح باسم متجرك وعدد الأختام المتبقية للمكافأة.',
            'badge' => 'بطاقة في المتصفح',
        ],
        [
            'num' => '3',
            'img' => 'images/landing/step-3-stamp.webp',
            'alt' => 'إضافة ختم جديد عند الدفع في المقهى',
            'title' => 'احصل على ختم عند الدفع',
            'desc' => 'يمسح الكاشير بطاقة العميل أو يبحث برقم الجوال ليضيف الختم في ثوانٍ.',
            'badge' => '+1 ختم فوري',
        ],
    ] : [
        [
            'num' => '1',
            'img' => 'images/landing/step-1-scan.webp',
            'alt' => 'Customer scanning a tabletop QR sign at a café counter',
            'title' => 'Scan the QR sign',
            'desc' => 'Customers point their phone camera at your counter QR sign — no app needed.',
            'badge' => 'Phone camera',
        ],
        [
            'num' => '2',
            'img' => 'images/landing/step-2-card.webp',
            'alt' => 'Customer opening their digital loyalty card on a smartphone',
            'title' => 'Open the loyalty card on a phone',
            'desc' => 'Their branded stamp card opens right in the browser with live progress.',
            'badge' => 'Browser card',
        ],
        [
            'num' => '3',
            'img' => 'images/landing/step-3-stamp.webp',
            'alt' => 'Barista adding a loyalty stamp at checkout',
            'title' => 'Earn a stamp at checkout',
            'desc' => 'Your cashier scans their card or looks up their phone to stamp in seconds.',
            'badge' => '+1 instant stamp',
        ],
    ];
@endphp

<section id="how-it-works" class="dl-section" style="background: #F5F3FF; color: #0F172A; border-top: 1px solid rgba(116, 62, 226, 0.14); border-bottom: 1px solid rgba(116, 62, 226, 0.14);">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow" style="background: rgba(116, 62, 226, 0.12); border-color: rgba(116, 62, 226, 0.28); color: #5B21B6;">
                {{ __('landing.nav_how_it_works') }}
            </span>
            <h2 style="color: #0F172A;">{{ __('landing.how_title') }}</h2>
            <p style="color: #475569;">{{ __('landing.how_subtitle') }}</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
            @foreach($steps as $step)
                <article style="background: #ffffff; border: 1px solid rgba(116, 62, 226, 0.16); border-radius: 20px; overflow: hidden; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06); display: flex; flex-direction: column;">
                    {{-- Image / Visual Frame stacked above its step --}}
                    <div style="position: relative; aspect-ratio: 4 / 3; background: linear-gradient(135deg, #EDE9FE 0%, #FEF3C7 100%); overflow: hidden;">
                        <img src="{{ asset($step['img']) }}"
                             alt="{{ $step['alt'] }}"
                             width="800"
                             height="600"
                             loading="lazy"
                             style="width: 100%; height: 100%; object-fit: cover; display: block;">

                        {{-- Real HTML step badge overlay --}}
                        <div style="position: absolute; top: 0.9rem; inset-inline-start: 0.9rem; display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.3rem 0.75rem; border-radius: 999px; background: #010A29; color: #ffffff; font-size: 0.8rem; font-weight: 800;">
                            <span style="width: 20px; height: 20px; border-radius: 999px; background: #743EE2; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">{{ $step['num'] }}</span>
                            <span>{{ $step['badge'] }}</span>
                        </div>
                    </div>

                    {{-- Real HTML step label & short caption --}}
                    <div style="padding: 1.35rem 1.45rem 1.5rem;">
                        <h3 style="margin: 0 0 0.45rem; font-size: 1.12rem; font-weight: 800; color: #0F172A;">
                            {{ $step['title'] }}
                        </h3>
                        <p style="margin: 0; font-size: 0.94rem; color: #475569; line-height: 1.65;">
                            {{ $step['desc'] }}
                        </p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

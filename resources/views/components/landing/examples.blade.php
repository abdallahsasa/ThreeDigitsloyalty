@php
    $isAr = app()->getLocale() === 'ar';
    $industries = $isAr ? [
        [
            'category' => 'المقاهي والقهوة المختصة',
            'img' => 'images/landing/industry-cafe.webp',
            'alt' => 'كوب لاتيه مرسوم بعناية بجانب كرواسان طازج على طاولة مقهى خشبية دافئة',
            'title' => 'اجعل قهوة الصباح عادة يومية في مقهاك',
            'desc' => 'بدلاً من بطاقات الورق التي تضيع في الجيب، يفتح زبونك بطاقته من متصفح هاتفه عند كل طلب قهوة.',
            'program' => 'مثال الحملة: اجمع 6 أختام',
            'reward' => 'المكافأة: قهوة مختصة مجانية',
            'filled' => 5,
            'total' => 6,
        ],
        [
            'category' => 'المطاعم والكافيهات',
            'img' => 'images/landing/industry-restaurant.webp',
            'alt' => 'مقدّمة طعام مبتسمة تضع طبق مقبلات طازجاً على طاولة خشبية في مطعم مشرق',
            'title' => 'كافئ الزيارات المتكررة وطلبات الغداء',
            'desc' => 'شجّع ضيوفك على العودة هذا الأسبوع عبر ختم سريع عند الفاتورة ومكافأة واضحة في الزيارة الخامسة.',
            'program' => 'مثال الحملة: اجمع 5 زيارات',
            'reward' => 'المكافأة: طبق جانبي أو حلى مجاني',
            'filled' => 4,
            'total' => 5,
        ],
        [
            'category' => 'الصالونات ومراكز العناية',
            'img' => 'images/landing/industry-salon.webp',
            'alt' => 'ركن تصفيف أنيق بمرآة مقوسة ومناشف لافندر في صالون تجميل حديث',
            'title' => 'حافظ على انتظام مواعيد عملائك في الصالون',
            'desc' => 'امسح بطاقة العميلة بعد كل جلسة قص أو عناية، وكافئ التزامها بترقية مجانية في الموعد الرابع.',
            'program' => 'مثال الحملة: اجمع 4 مواعيد',
            'reward' => 'المكافأة: ترقية مجانية لجلسة العناية',
            'filled' => 3,
            'total' => 4,
        ],
    ] : [
        [
            'category' => 'Cafés & Coffee Bars',
            'img' => 'images/landing/industry-cafe.webp',
            'alt' => 'Freshly poured latte art in a ceramic cup beside a flaky croissant on a sunlit oak café counter',
            'title' => 'Turn morning coffee runs into a daily habit',
            'desc' => 'Replace lost paper punch cards with an instant browser stamp card guests pull up right at the espresso bar.',
            'program' => 'Sample rule: Collect 6 stamps',
            'reward' => 'Reward: Free specialty coffee',
            'filled' => 5,
            'total' => 6,
        ],
        [
            'category' => 'Restaurants & Casual Dining',
            'img' => 'images/landing/industry-restaurant.webp',
            'alt' => 'Smiling server placing a fresh artisan side dish on a sunlit wooden bistro table',
            'title' => 'Bring lunch and dinner guests back sooner',
            'desc' => 'Reward regulars on their fifth visit with a house side or dessert—stamped at the table or register in seconds.',
            'program' => 'Sample rule: Collect 5 visits',
            'reward' => 'Reward: Free side or dessert',
            'filled' => 4,
            'total' => 5,
        ],
        [
            'category' => 'Salons, Barbers & Spas',
            'img' => 'images/landing/industry-salon.webp',
            'alt' => 'Sunlit boutique salon station with a brass arch mirror and folded lavender and cream spa towels',
            'title' => 'Keep clients rebooking with your stylists',
            'desc' => 'Add a stamp after each cut, manicure, or session and unlock a complimentary treatment upgrade on visit four.',
            'program' => 'Sample rule: Collect 4 appointments',
            'reward' => 'Reward: Free treatment upgrade',
            'filled' => 3,
            'total' => 4,
        ],
    ];
@endphp

<style>
    .td-industry-card {
        display: grid;
        grid-template-columns: 1fr;
        background: #FFFFFF;
        border: 1px solid rgba(116, 62, 226, 0.16);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.06);
    }
    @media (min-width: 860px) {
        .td-industry-card {
            grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr);
            align-items: stretch;
        }
        .td-industry-card.td-ind-alt > :first-child {
            order: 2;
        }
        .td-industry-card.td-ind-alt > :last-child {
            order: 1;
        }
    }
</style>

<section id="industries" class="dl-section" style="background: #F5F3FF; color: #0F172A; border-top: 1px solid rgba(116, 62, 226, 0.14); border-bottom: 1px solid rgba(116, 62, 226, 0.14);">
    <span id="examples" aria-hidden="true"></span>
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow" style="background: rgba(116, 62, 226, 0.12); border-color: rgba(116, 62, 226, 0.28); color: #5B21B6;">
                {{ $isAr ? 'مصمم لقطاع الضيافة والخدمات' : 'Built for Local Hospitality & Care' }}
            </span>
            <h2 style="color: #0F172A;">{{ __('landing.examples_title') }}</h2>
            <p style="color: #475569;">{{ __('landing.examples_subtitle') }}</p>
        </div>

        <div style="display: grid; gap: 1.85rem;">
            @foreach($industries as $idx => $ind)
                <article class="td-industry-card {{ $idx === 1 ? 'td-ind-alt' : '' }}">
                    <div style="position: relative; min-height: 250px; background: #EDE9FE;">
                        <img src="{{ asset($ind['img']) }}"
                             alt="{{ $ind['alt'] }}"
                             width="800"
                             height="600"
                             loading="lazy"
                             style="width: 100%; height: 100%; object-fit: cover; display: block;">
                    </div>

                    <div style="padding: 1.75rem 1.85rem; display: flex; flex-direction: column; justify-content: center;">
                        <div style="display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.82rem; font-weight: 800; color: #5B21B6; margin-bottom: 0.55rem;">
                            <span>{{ $ind['category'] }}</span>
                            <span>·</span>
                            <span style="color: #64748b; font-weight: 700;">{{ __('landing.examples_badge') }}</span>
                        </div>

                        <h3 style="margin: 0 0 0.6rem; font-size: clamp(1.25rem, 2.2vw, 1.55rem); font-weight: 800; color: #0F172A; line-height: 1.3;">
                            {{ $ind['title'] }}
                        </h3>

                        <p style="margin: 0 0 1.25rem; font-size: 0.96rem; color: #475569; line-height: 1.7;">
                            {{ $ind['desc'] }}
                        </p>

                        {{-- Sample Stamp Strip + Concrete Reward --}}
                        <div style="padding: 1rem 1.15rem; border-radius: 16px; background: #FAF8F5; border: 1px solid #E5E0D8;">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.7rem;">
                                <span style="font-size: 0.85rem; font-weight: 800; color: #0F172A;">{{ $ind['program'] }}</span>
                                <span style="font-size: 0.85rem; font-weight: 800; color: #5B21B6;">{{ $ind['reward'] }}</span>
                            </div>
                            <div style="display: flex; gap: 0.45rem; flex-wrap: wrap;">
                                @for($i = 1; $i <= $ind['total']; $i++)
                                    <span style="width: 34px; height: 34px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; {{ $i <= $ind['filled'] ? 'background: #010A29; color: #ffffff;' : 'background: #ffffff; border: 1.5px dashed #94a3b8; color: #64748b;' }}">
                                        {{ $i <= $ind['filled'] ? '✓' : $i }}
                                    </span>
                                @endfor
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

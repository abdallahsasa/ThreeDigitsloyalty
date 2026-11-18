@php
    $isAr = app()->getLocale() === 'ar';
    $examples = $isAr ? [
        [
            'category' => 'مقهى',
            'name' => 'مقهى الياسمين المختص',
            'stamps' => '٤ من ٦',
            'filled' => 4,
            'total' => 6,
            'reward' => 'مشروب قهوة مجاني',
        ],
        [
            'category' => 'مطعم',
            'name' => 'مطعم الشام للوجبات',
            'stamps' => '٥ من ٨',
            'filled' => 5,
            'total' => 8,
            'reward' => 'وجبة رئيسية مجانية',
        ],
        [
            'category' => 'صالون',
            'name' => 'صالون نور للتجميل والعناية',
            'stamps' => '٣ من ٥',
            'filled' => 3,
            'total' => 5,
            'reward' => 'جلسة عناية مجانية',
        ],
    ] : [
        [
            'category' => 'Café',
            'name' => 'Jasmine Specialty Coffee',
            'stamps' => '4 of 6',
            'filled' => 4,
            'total' => 6,
            'reward' => 'Free specialty drink',
        ],
        [
            'category' => 'Restaurant',
            'name' => 'Al-Sham Kitchen',
            'stamps' => '5 of 8',
            'filled' => 5,
            'total' => 8,
            'reward' => 'Free signature meal',
        ],
        [
            'category' => 'Salon',
            'name' => 'Noor Beauty & Care Salon',
            'stamps' => '3 of 5',
            'filled' => 3,
            'total' => 5,
            'reward' => 'Free care session',
        ],
    ];
@endphp

<section id="examples" class="dl-section">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ __('landing.examples_badge') }}</span>
            <h2>{{ __('landing.examples_title') }}</h2>
            <p>{{ __('landing.examples_subtitle') }}</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
            @foreach($examples as $ex)
                <article class="dl-card" style="padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between; gap: 1.1rem;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; margin-bottom: 0.85rem;">
                            <span style="padding: 0.25rem 0.65rem; border-radius: 999px; background: rgba(116, 62, 226, 0.16); color: #c4b5fd; font-size: 0.75rem; font-weight: 700;">
                                {{ $ex['category'] }} · {{ __('landing.examples_badge') }}
                            </span>
                            <span style="font-size: 0.82rem; font-weight: 700; color: #6ee7b7;">{{ $ex['stamps'] }}</span>
                        </div>

                        <h3 style="margin: 0 0 1rem; font-size: 1.12rem; font-weight: 800; color: #f8fafc; line-height: 1.35;">
                            {{ $ex['name'] }}
                        </h3>

                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.45rem;">
                            @for($i = 1; $i <= $ex['total']; $i++)
                                <div style="height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; {{ $i <= $ex['filled'] ? 'background: #10b981; color: #ffffff;' : 'border: 1px dashed rgba(148, 163, 184, 0.35); color: #cbd5e1;' }}">
                                    {{ $i <= $ex['filled'] ? '✓' : $i }}
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div style="padding-top: 0.85rem; border-top: 1px dashed rgba(148, 163, 184, 0.2); font-size: 0.88rem; color: #cbd5e1; font-weight: 600;">
                        {{ $isAr ? 'المكافأة:' : 'Reward:' }} <strong style="color: #f8fafc;">{{ $ex['reward'] }}</strong>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

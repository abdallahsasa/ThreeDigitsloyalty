@props(['plans' => collect()])

@php
    $isAr = app()->getLocale() === 'ar';
    $offerEndsRaw = config('loyalty.launch_offer_ends_at', '2026-12-31');
    $offerEndsFormatted = $isAr ? '31 ديسمبر 2026' : 'December 31, 2026';

    $translatePlanName = function (string $name) use ($isAr): string {
        if (!$isAr) {
            return $name;
        }

        return match (strtolower(trim($name))) {
            'starter' => 'البداية (Starter)',
            'business' => 'الأعمال (Business)',
            'enterprise' => 'الشركات (Enterprise)',
            default => $name,
        };
    };

    $formatArabicDollars = function (float $price): string {
        $int = (int) round($price);
        return $int <= 10 ? "{$int} دولارات شهرياً" : "{$int} دولاراً شهرياً";
    };

    $translateFeature = function (string $feature) use ($isAr): string {
        if (!$isAr) {
            return $feature;
        }

        return match (trim($feature)) {
            '1 Branch' => 'فرع واحد',
            'Up to 500 Customers' => 'حتى 500 عميل',
            '1 Active Campaign' => 'حملة ولاء نشطة واحدة',
            'Up to 5 Branches' => 'حتى 5 فروع',
            'Unlimited Customers' => 'عملاء غير محدودين',
            'Unlimited Campaigns' => 'حملات ولاء غير محدودة',
            'Advanced Analytics' => 'تحليلات متقدمة',
            'Unlimited Branches' => 'فروع غير محدودة',
            'Custom Integrations' => 'ربط وتكاملات مخصصة',
            'Dedicated Support' => 'دعم مخصص',
            default => $feature,
        };
    };
@endphp

<section id="pricing" class="dl-section" style="background: #040D30; border-top: 1px solid rgba(148, 163, 184, 0.14);">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ __('landing.nav_pricing') }}</span>
            <h2>{{ __('landing.pricing_title') }}</h2>
            <p style="margin-top: 0.6rem; color: #e2e8f0; font-size: 1rem;">
                {{ __('landing.pricing_free_banner') }}
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(270px, 1fr)); gap: 1.5rem; align-items: stretch;">
            @foreach($plans as $plan)
                @php
                    $planSlug = \Illuminate\Support\Str::slug($plan->name);
                    $isMultiBranch = $planSlug === 'business';
                    $priceFloat = (float) $plan->price;
                @endphp
                <div class="dl-card" style="padding: 1.85rem; display: flex; flex-direction: column; justify-content: space-between; {{ $isMultiBranch ? 'border-color: rgba(116, 62, 226, 0.6); background: #0C1742;' : '' }}">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap; min-height: 2.25rem;">
                            <h3 style="font-size: 1.2rem; font-weight: 800; margin: 0; color: #f8fafc;">{{ $translatePlanName($plan->name) }}</h3>
                            @if($isMultiBranch)
                                <span style="padding: 0.25rem 0.7rem; border-radius: 999px; background: rgba(116, 62, 226, 0.22); color: #c4b5fd; font-size: 0.8rem; font-weight: 700;">
                                    {{ __('landing.pricing_multi_branch_badge') }}
                                </span>
                            @endif
                        </div>

                        <div style="font-size: 1.35rem; font-weight: 800; color: #6ee7b7; margin-bottom: 0.3rem;">
                            {{ __('landing.pricing_free_label') }}
                        </div>
                        <div style="font-size: 0.92rem; color: #f8fafc; font-weight: 700; margin-bottom: 0.35rem;">
                            {{ __('landing.pricing_free_until') }}
                        </div>
                        @if($priceFloat > 0)
                            <div style="font-size: 0.92rem; color: #cbd5e1; font-weight: 600; margin-bottom: 1.35rem;">
                                @if($isAr)
                                    بعد العرض: {{ $formatArabicDollars($priceFloat) }}
                                @else
                                    After offer: <span class="ltr-inline">${{ number_format($priceFloat, 0) }}/month</span>
                                @endif
                            </div>
                        @endif

                        @if(is_array($plan->features))
                            <ul style="list-style: none; padding: 0; margin: 0 0 1.75rem; display: grid; gap: 0.7rem;">
                                @if($planSlug === 'enterprise')
                                    <li style="display: flex; align-items: center; gap: 0.55rem; font-size: 0.94rem; color: #c4b5fd; font-weight: 700;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span>{{ $isAr ? 'تشمل جميع مزايا باقة الأعمال بحدود غير محدودة' : 'Includes unlimited capacity across branches, customers, and campaigns' }}</span>
                                    </li>
                                @elseif($planSlug === 'business')
                                    <li style="display: flex; align-items: center; gap: 0.55rem; font-size: 0.94rem; color: #c4b5fd; font-weight: 700;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span>{{ $isAr ? 'ترقية لحدود البداية مع تعدد الفروع' : 'Expands Starter limits for multi-branch stores' }}</span>
                                    </li>
                                @endif
                                @foreach($plan->features as $feature)
                                    <li style="display: flex; align-items: center; gap: 0.55rem; font-size: 0.95rem; color: #f8fafc;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span>{{ $translateFeature((string) $feature) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    @php $translatedPlanName = $translatePlanName($plan->name); @endphp
                    <a href="{{ route('merchant.register', ['plan' => $planSlug]) }}"
                       onclick="window.trackFunnelEvent && window.trackFunnelEvent('primary_cta_clicked', { location: 'pricing', plan: '{{ $planSlug }}' })"
                       class="{{ $isMultiBranch ? 'dl-btn-primary' : 'dl-btn-ghost' }}"
                       style="width: 100%; font-size: 0.95rem;">
                        {{ $isAr ? "ابدأ مجاناً مع {$translatedPlanName}" : "Start free with {$translatedPlanName}" }}
                    </a>
                </div>
            @endforeach
        </div>

        <div class="dl-card" style="margin-top: 1.75rem; padding: 1.35rem 1.5rem; background: #071033;">
            <p style="margin: 0; font-size: 0.92rem; color: #e2e8f0; line-height: 1.75; text-align: center;">
                {{ $isAr
                    ? "لا يُطلب إدخال بطاقة دفع عند التسجيل، ولا يتم أي خصم تلقائي في 1 يناير 2027 بعد انتهاء العرض في {$offerEndsFormatted}. تبقى بيانات متجرك وبطاقات عملائك محفوظة، وسيتم إشعارك مسبقاً بخيارات التجديد قبل تفعيل أي فوترة."
                    : "No payment card is required to register, and billing does not start automatically on January 1, 2027 after the offer ends on {$offerEndsFormatted}. Your store data and customer cards remain intact, and renewal options will be shared ahead of time before any billing begins." }}
            </p>
        </div>
    </div>
</section>

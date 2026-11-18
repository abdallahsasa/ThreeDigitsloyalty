@props(['plans' => collect()])

@php
    $isAr = app()->getLocale() === 'ar';
    $offerEndsRaw = config('loyalty.launch_offer_ends_at', '2026-12-31');
    $offerEndsFormatted = $isAr ? '31 كانون الأول 2026' : 'December 31, 2026';

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

<section id="pricing" class="dl-section" style="background: #040D30; border-top: 1px solid rgba(148, 163, 184, 0.12);">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ __('landing.nav_pricing') }}</span>
            <h2>{{ __('landing.pricing_title') }}</h2>
            <div style="margin: 1rem auto 0; max-width: 700px; display: inline-flex; align-items: center; gap: 0.6rem; padding: 0.7rem 1.25rem; border-radius: 999px; background: rgba(16, 185, 129, 0.14); border: 1px solid rgba(16, 185, 129, 0.38); color: #6ee7b7; font-size: 0.9rem; font-weight: 700;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ __('landing.pricing_free_banner') }}</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(270px, 1fr)); gap: 1.5rem; align-items: stretch;">
            @foreach($plans as $plan)
                @php
                    $planSlug = \Illuminate\Support\Str::slug($plan->name);
                    $isMultiBranch = $planSlug === 'business';
                @endphp
                <div class="dl-card" style="padding: 1.85rem; display: flex; flex-direction: column; justify-content: space-between; {{ $isMultiBranch ? 'border-color: rgba(116, 62, 226, 0.6); background: #0C1742;' : '' }}">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                            <h3 style="font-size: 1.18rem; font-weight: 800; margin: 0; color: #f8fafc;">{{ $translatePlanName($plan->name) }}</h3>
                            @if($isMultiBranch)
                                <span style="padding: 0.25rem 0.65rem; border-radius: 999px; background: rgba(116, 62, 226, 0.2); color: #c4b5fd; font-size: 0.75rem; font-weight: 700;">
                                    {{ __('landing.pricing_multi_branch_badge') }}
                                </span>
                            @endif
                        </div>

                        <div style="display: flex; align-items: baseline; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 0.5rem;">
                            <span style="font-size: 2.25rem; font-weight: 800; color: #10b981;">{{ __('landing.pricing_free_label') }}</span>
                            @if((float) $plan->price > 0)
                                <span class="ltr-inline" style="color: #cbd5e1; font-size: 0.98rem; font-weight: 600; text-decoration: line-through;">
                                    ${{ number_format((float) $plan->price, 0) }}/{{ $isAr ? 'شهر' : 'mo' }}
                                </span>
                            @endif
                        </div>

                        <div style="margin-bottom: 1.35rem; font-size: 0.84rem; color: #cbd5e1; font-weight: 600;">
                            {{ __('landing.pricing_free_until') }}
                            @if((float) $plan->price > 0)
                                · {{ $isAr ? 'ثم' : 'then' }}
                                <span class="ltr-inline">${{ number_format((float) $plan->price, 0) }}/{{ $isAr ? 'شهر' : 'month' }}</span>
                            @endif
                        </div>

                        @if(is_array($plan->features))
                            <ul style="list-style: none; padding: 0; margin: 0 0 1.75rem; display: grid; gap: 0.7rem;">
                                @if($planSlug !== 'starter')
                                    <li style="display: flex; align-items: center; gap: 0.55rem; font-size: 0.9rem; color: #c4b5fd; font-weight: 700;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span>{{ $isAr ? 'تشمل جميع مزايا الخطة الأساسية' : 'Includes all Starter features' }}</span>
                                    </li>
                                @endif
                                @foreach($plan->features as $feature)
                                    <li style="display: flex; align-items: center; gap: 0.55rem; font-size: 0.92rem; color: #f8fafc;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span>{{ $translateFeature((string) $feature) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <a href="{{ route('merchant.register', ['plan' => $planSlug]) }}"
                       onclick="window.trackFunnelEvent && window.trackFunnelEvent('primary_cta_clicked', { location: 'pricing', plan: '{{ $planSlug }}' })"
                       class="{{ $isMultiBranch ? 'dl-btn-primary' : 'dl-btn-ghost' }}"
                       style="width: 100%;">
                        {{ __('landing.pricing_cta_free') }}
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Transparent Commercial Terms --}}
        <div class="dl-card" style="margin-top: 2rem; padding: 1.35rem 1.5rem; background: #071033;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; font-size: 0.88rem; color: #cbd5e1; line-height: 1.65;">
                <div>
                    <strong style="color: #f8fafc; display: block; margin-bottom: 0.2rem;">
                        {{ $isAr ? 'نهاية العرض والأسعار اللاحقة' : 'Offer end date & post-offer pricing' }}
                    </strong>
                    {{ $isAr
                        ? "العرض المجاني سارٍ حتى {$offerEndsFormatted}. بعد هذا التاريخ تصبح الأسعار الشهرية $9 و$19 و$39."
                        : "The free launch period ends on {$offerEndsFormatted}. Standard monthly pricing afterward is $9, $19, or $39." }}
                </div>
                <div>
                    <strong style="color: #f8fafc; display: block; margin-bottom: 0.2rem;">
                        {{ $isAr ? 'بدون بطاقة دفع أو تجديد تلقائي' : 'No payment card or auto-billing' }}
                    </strong>
                    {{ $isAr
                        ? 'لا يُطلب إدخال بطاقة دفع عند التسجيل ولا يتم الخصم تلقائياً؛ تختار بنفسك الاستمرار بعد انتهاء العرض.'
                        : 'No payment card is collected at signup and billing does not start automatically. You decide whether to continue after the offer.' }}
                </div>
                <div>
                    <strong style="color: #f8fafc; display: block; margin-bottom: 0.2rem;">
                        {{ $isAr ? 'الإعداد والدعم والحدود' : 'Setup, support & plan limits' }}
                    </strong>
                    {{ $isAr
                        ? 'الإعداد ذاتي ومجاني عبر لوحة المتجر. إذا لم تستمر بعد فترة العرض تتوقف الخطة دون أي رسوم، ويمكنك الترقية في أي وقت.'
                        : 'Self-service setup is included free. If you choose not to continue after the free period, no charges occur and you can upgrade anytime.' }}
                </div>
            </div>
        </div>
    </div>
</section>

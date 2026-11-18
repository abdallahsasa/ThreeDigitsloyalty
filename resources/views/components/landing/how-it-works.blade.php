@php $isAr = app()->getLocale() === 'ar'; @endphp

<section id="how-it-works" class="dl-section" style="background: #040D30; border-top: 1px solid rgba(148, 163, 184, 0.12);">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ __('landing.nav_how_it_works') }}</span>
            <h2>{{ __('landing.how_title') }}</h2>
            <p>{{ __('landing.how_subtitle') }}</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 1.5rem;">
            {{-- Merchant Setup Journey --}}
            <div class="dl-card" style="padding: 1.75rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem;">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(116, 62, 226, 0.18); color: #c4b5fd; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #f8fafc;">{{ __('landing.merchant_journey_title') }}</h3>
                </div>

                <ol style="list-style: none; padding: 0; margin: 0; display: grid; gap: 1rem;">
                    @foreach([1, 2, 3, 4] as $step)
                        <li style="display: flex; gap: 0.85rem; align-items: flex-start;">
                            <span style="width: 28px; height: 28px; border-radius: 999px; background: #743EE2; color: #ffffff; font-size: 0.82rem; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                {{ $step }}
                            </span>
                            <div>
                                <div style="font-weight: 700; color: #f8fafc; font-size: 0.98rem;">{{ __("landing.merchant_step_{$step}_title") }}</div>
                                <div style="color: #cbd5e1; font-size: 0.88rem; line-height: 1.55; margin-top: 0.15rem;">{{ __("landing.merchant_step_{$step}_desc") }}</div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- Customer Usage Journey --}}
            <div class="dl-card" style="padding: 1.75rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem;">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(16, 185, 129, 0.16); color: #6ee7b7; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                            <line x1="12" y1="18" x2="12.01" y2="18"></line>
                        </svg>
                    </div>
                    <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #f8fafc;">{{ __('landing.customer_journey_title') }}</h3>
                </div>

                <ol style="list-style: none; padding: 0; margin: 0; display: grid; gap: 1rem;">
                    @foreach([1, 2, 3, 4] as $step)
                        <li style="display: flex; gap: 0.85rem; align-items: flex-start;">
                            <span style="width: 28px; height: 28px; border-radius: 999px; background: #10b981; color: #010A29; font-size: 0.82rem; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                {{ $step }}
                            </span>
                            <div>
                                <div style="font-weight: 700; color: #f8fafc; font-size: 0.98rem;">{{ __("landing.customer_step_{$step}_title") }}</div>
                                <div style="color: #cbd5e1; font-size: 0.88rem; line-height: 1.55; margin-top: 0.15rem;">{{ __("landing.customer_step_{$step}_desc") }}</div>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</section>

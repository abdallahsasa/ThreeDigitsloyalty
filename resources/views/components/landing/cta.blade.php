@php $isAr = app()->getLocale() === 'ar'; @endphp

<section class="dl-section" style="padding-top: 2rem;">
    <div class="dl-container">
        <div class="dl-card" style="padding: 3rem 1.5rem; text-align: center; background: #071033; border-color: rgba(196, 181, 253, 0.28);">
            <h2 style="font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 800; max-width: 680px; margin: 0 auto 0.75rem; line-height: 1.25; color: #f8fafc;">
                {{ __('landing.final_cta_title') }}
            </h2>
            <p style="margin: 0 auto 1.75rem; max-width: 580px; color: #cbd5e1; font-size: 1rem; line-height: 1.65;">
                {{ __('landing.final_cta_subtitle') }}
            </p>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.85rem;">
                <a href="{{ route('merchant.register') }}"
                   onclick="window.trackFunnelEvent && window.trackFunnelEvent('primary_cta_clicked', { location: 'final_cta' })"
                   class="dl-btn-primary">
                    <span>{{ __('landing.hero_cta_1') }}</span>
                    <span aria-hidden="true">{{ $isAr ? '←' : '→' }}</span>
                </a>
                <a href="#interactive-demo" class="dl-btn-ghost">
                    <span>{{ __('landing.hero_cta_2') }}</span>
                </a>
            </div>
        </div>
    </div>
</section>

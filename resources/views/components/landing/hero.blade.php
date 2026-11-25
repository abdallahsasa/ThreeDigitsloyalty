@php
    $isAr = app()->getLocale() === 'ar';
    $langSuffix = $isAr ? 'ar' : 'en';
@endphp

<style>
    .td-hero-wrap {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #743EE2 0%, #5B21B6 52%, #2E1065 100%);
        padding: 2.75rem 0 3.75rem;
    }
    @media (min-width: 980px) {
        .td-hero-wrap {
            padding: 3.25rem 0 4.25rem;
        }
    }
    .td-hero-dots-tr {
        position: absolute;
        top: 1.5rem;
        inset-inline-end: 0;
        width: 240px;
        height: 150px;
        background-image: radial-gradient(rgba(255, 255, 255, 0.22) 2px, transparent 2px);
        background-size: 16px 16px;
        pointer-events: none;
    }
    .td-hero-dots-bl {
        position: absolute;
        bottom: 1.5rem;
        inset-inline-start: 0;
        width: 220px;
        height: 140px;
        background-image: radial-gradient(rgba(255, 255, 255, 0.18) 2px, transparent 2px);
        background-size: 16px 16px;
        pointer-events: none;
    }
    .td-hero-grid {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.25rem;
        align-items: center;
    }
    @media (min-width: 960px) {
        .td-hero-grid {
            grid-template-columns: minmax(0, 0.96fr) minmax(0, 1.04fr);
            gap: 2.5rem;
        }
    }
    .td-hero-btn-white {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #ffffff;
        color: #4C1D95;
        font-weight: 800;
        padding: 0.92rem 1.85rem;
        border-radius: 999px;
        border: 2px solid #ffffff;
        text-decoration: none;
        box-shadow: 0 14px 32px rgba(1, 10, 41, 0.28);
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .td-hero-btn-white:hover {
        background: #F5F3FF;
        transform: translateY(-1px);
    }
    .td-hero-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        font-weight: 700;
        padding: 0.92rem 1.6rem;
        border-radius: 999px;
        border: 1.5px solid rgba(255, 255, 255, 0.35);
        text-decoration: none;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
    .td-hero-btn-outline:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.55);
    }
    .td-hero-visual {
        width: 100%;
        max-width: 560px;
        height: auto;
        display: block;
        margin: 0 auto;
    }
</style>

<section class="td-hero-wrap">
    {{-- Subtle Geometric Halo & Corner Dot Accents --}}
    <div aria-hidden="true" style="position: absolute; top: -120px; inset-inline-start: 18%; width: 520px; height: 520px; border-radius: 999px; background: radial-gradient(circle, rgba(255, 255, 255, 0.11) 0%, transparent 68%); pointer-events: none;"></div>
    <div aria-hidden="true" class="td-hero-dots-tr"></div>
    <div aria-hidden="true" class="td-hero-dots-bl"></div>

    <div class="dl-container">
        <div class="td-hero-grid">
            {{-- Copy & CTAs (Always first on mobile) --}}
            <div>
                <h1 style="font-size: {{ $isAr ? 'clamp(2rem, 3.8vw, 3.1rem)' : 'clamp(2.15rem, 4.2vw, 3.35rem)' }}; font-weight: 800; line-height: {{ $isAr ? '1.28' : '1.14' }}; max-width: {{ $isAr ? '20ch' : '17ch' }}; margin: 0 0 1.25rem; color: #ffffff;">
                    {{ __('landing.hero_title') }}
                </h1>

                <p style="font-size: 1.08rem; color: #EDE9FE; line-height: 1.75; margin: 0 0 2rem; max-width: 540px; font-weight: 500;">
                    {{ __('landing.hero_subtitle') }}
                </p>

                <div style="display: flex; flex-wrap: wrap; gap: 0.9rem; margin-bottom: 1.35rem;">
                    <a href="{{ route('merchant.register') }}"
                       onclick="window.trackFunnelEvent && window.trackFunnelEvent('primary_cta_clicked', { location: 'hero' })"
                       class="td-hero-btn-white" style="font-size: 1.02rem;">
                        <span>{{ __('landing.hero_cta_1') }}</span>
                        <span aria-hidden="true">{{ $isAr ? '←' : '→' }}</span>
                    </a>
                    <a href="#interactive-demo" class="td-hero-btn-outline" style="font-size: 0.98rem;">
                        <span>{{ __('landing.hero_cta_2') }}</span>
                    </a>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem; color: #F5F3FF; font-size: 0.94rem; font-weight: 700; margin-bottom: 1.25rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>{{ $isAr ? 'مجاني حتى 31 ديسمبر 2026 — بدون بطاقة دفع للبدء' : 'Free until December 31, 2026 — no payment card needed to get started' }}</span>
                </div>

                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 0.75rem 1.35rem; color: #DDD6FE; font-size: 0.9rem; font-weight: 600;">
                    @foreach([__('landing.hero_benefit_1'), __('landing.hero_benefit_2'), __('landing.hero_benefit_3')] as $benefit)
                        <li style="display: inline-flex; align-items: center; gap: 0.4rem;">
                            <span aria-hidden="true" style="color: #6ee7b7; font-weight: 800;">✓</span>
                            <span>{{ $benefit }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Right Visual: 3D Tilted Smartphone + Floating Wallet & Stamp Metrics Collage --}}
            <div>
                <img src="{{ asset("images/landing/collage-stamper-{$langSuffix}.webp") }}"
                     alt="{{ $isAr ? 'هاتف يعرض بطاقة أختام ThreeDigits Loyalty مع خيارات إضافة الأختام وحفظ البطاقة في Apple Wallet وGoogle Wallet' : 'ThreeDigits Loyalty 3D smartphone displaying a digital coffee stamp card, cashier stamp controls, Apple Wallet and Google Wallet badges, and sample store metrics' }}"
                     width="840"
                     height="840"
                     loading="eager"
                     fetchpriority="high"
                     class="td-hero-visual">
            </div>
        </div>
    </div>
</section>

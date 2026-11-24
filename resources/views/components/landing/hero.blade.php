@php $isAr = app()->getLocale() === 'ar'; @endphp

<style>
    @keyframes tdStampSettle {
        0% { transform: scale(0.8); }
        55% { transform: scale(1.08); }
        100% { transform: scale(1); }
    }
    .td-stamp-pop {
        animation: tdStampSettle 320ms cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    @media (prefers-reduced-motion: reduce) {
        .td-stamp-pop {
            animation: none !important;
        }
    }
    .td-hero-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.25rem;
        align-items: center;
    }
    @media (min-width: 980px) {
        .td-hero-grid {
            grid-template-columns: minmax(0, 0.92fr) minmax(0, 1.08fr);
            gap: 2.5rem;
        }
    }
    .td-hero-stage {
        position: relative;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
    }
    .td-hero-photo-frame {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(196, 181, 253, 0.3);
        background: #071033;
        box-shadow: 0 24px 50px rgba(0, 0, 0, 0.4);
    }
    .td-hero-photo-frame img {
        display: block;
        width: 100%;
        height: auto;
        object-fit: cover;
    }
    .td-hero-phone {
        position: relative;
        max-width: 370px;
        margin: -2.25rem auto 0;
        z-index: 2;
    }
    @media (min-width: 640px) {
        .td-hero-stage {
            padding-bottom: 1.5rem;
        }
        .td-hero-photo-frame {
            width: 86%;
            margin-left: auto;
            margin-right: 0;
        }
        .td-hero-phone {
            max-width: 340px;
            margin: -11.5rem auto 0 0;
        }
    }
</style>

<section style="padding: 2.75rem 0 3.75rem; background: radial-gradient(circle at 80% 20%, rgba(116, 62, 226, 0.16), transparent 55%), #010A29;">
    <div class="dl-container">
        <div class="td-hero-grid">
            {{-- Copy & CTAs (always first on mobile) --}}
            <div>
                <h1 style="font-size: {{ $isAr ? 'clamp(1.75rem, 3.2vw, 2.55rem)' : 'clamp(1.9rem, 3.6vw, 2.95rem)' }}; font-weight: 800; line-height: {{ $isAr ? '1.3' : '1.2' }}; max-width: {{ $isAr ? '23ch' : '19ch' }}; margin: 0 0 1.15rem; color: #f8fafc;">
                    {{ __('landing.hero_title') }}
                </h1>

                <p style="font-size: 1.02rem; color: #e2e8f0; line-height: 1.75; margin: 0 0 1.85rem; max-width: 540px;">
                    {{ __('landing.hero_subtitle') }}
                </p>

                <div style="display: flex; flex-wrap: wrap; gap: 0.9rem; margin-bottom: 1.85rem;">
                    <a href="{{ route('merchant.register') }}"
                       onclick="window.trackFunnelEvent && window.trackFunnelEvent('primary_cta_clicked', { location: 'hero' })"
                       class="dl-btn-primary" style="font-size: 1rem; padding: 0.9rem 1.8rem;">
                        <span>{{ __('landing.hero_cta_1') }}</span>
                        <span aria-hidden="true">{{ $isAr ? '←' : '→' }}</span>
                    </a>
                    <a href="#interactive-demo" class="dl-btn-ghost" style="font-size: 0.98rem; padding: 0.9rem 1.55rem;">
                        <span>{{ __('landing.hero_cta_2') }}</span>
                    </a>
                </div>

                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 0.85rem 1.5rem; color: #cbd5e1; font-size: 0.95rem; font-weight: 600;">
                    @foreach([__('landing.hero_benefit_1'), __('landing.hero_benefit_2'), __('landing.hero_benefit_3')] as $benefit)
                        <li style="display: inline-flex; align-items: center; gap: 0.45rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span>{{ $benefit }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Checkout Scene + Phone-Style Interactive Loyalty Card Demo --}}
            <div id="interactive-demo"
                 x-data="{
                     stamps: 5,
                     total: 6,
                     started: false,
                     completed: false,
                     justStamped: false,
                     addStamp() {
                         if (!this.started) {
                             this.started = true;
                             window.trackFunnelEvent && window.trackFunnelEvent('demo_started', {});
                         }
                         if (this.stamps < this.total) {
                             this.stamps++;
                             this.justStamped = true;
                             if (this.stamps === this.total && !this.completed) {
                                 this.completed = true;
                                 window.trackFunnelEvent && window.trackFunnelEvent('demo_completed', {});
                             }
                         }
                     },
                     resetDemo() {
                         this.stamps = 5;
                         this.completed = false;
                         this.justStamped = false;
                     },
                     formattedCount() {
                         return this.stamps + ' {{ __('landing.demo_stamps_of') }} ' + this.total;
                     }
                 }"
                 style="width: 100%;">
                <div class="td-hero-stage">
                    @if(file_exists(public_path('images/landing/hero-checkout.webp')))
                        <div class="td-hero-photo-frame">
                            <picture>
                                @if(file_exists(public_path('images/landing/hero-checkout-mobile.webp')))
                                    <source media="(max-width: 639px)" srcset="{{ asset('images/landing/hero-checkout-mobile.webp') }}" width="800" height="540" type="image/webp">
                                @endif
                                <img src="{{ asset('images/landing/hero-checkout.webp') }}"
                                     alt="{{ $isAr ? 'باريستا يحضّر القهوة خلف طاولة الكاشير وبجانبه حامل رمز QR للولاء' : 'Barista preparing coffee behind a sunlit café counter next to a tabletop QR stand' }}"
                                     width="1280"
                                     height="720"
                                     loading="eager"
                                     fetchpriority="high">
                            </picture>
                        </div>
                    @endif

                    {{-- Phone-Style Frame (overlaps the clean left plaster area of the photo on >=640px, stacks cleanly below on mobile) --}}
                    <div class="dl-card td-hero-phone" style="padding: 1.1rem 1.2rem 1.3rem; background: #071033; border: 2px solid rgba(196, 181, 253, 0.42); border-radius: 30px; box-shadow: 0 24px 55px rgba(1, 10, 41, 0.72);">
                        {{-- Phone speaker pill --}}
                        <div aria-hidden="true" style="width: 64px; height: 5px; border-radius: 999px; background: rgba(148, 163, 184, 0.32); margin: 0 auto 0.9rem;"></div>
                        {{-- Group 1: Demo label & progress --}}
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <span class="dl-eyebrow">{{ __('landing.demo_badge') }}</span>
                            <span style="font-size: 0.95rem; font-weight: 800; color: #f8fafc;" x-text="formattedCount()"></span>
                        </div>

                    {{-- Loyalty Card Frame --}}
                    <div style="border-radius: 18px; overflow: hidden; border: 1px solid rgba(148, 163, 184, 0.22); background: #0C1742;">
                        {{-- Group 2: Business Name --}}
                        <div style="padding: 1.15rem 1.25rem; background: #5B21B6; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.12);">
                            <div>
                                <div style="font-size: 1.12rem; font-weight: 800; color: #ffffff;">{{ __('landing.demo_shop_name') }}</div>
                                <div style="font-size: 0.875rem; color: #ede9fe; font-weight: 600;">{{ __('landing.demo_program_name') }}</div>
                            </div>
                            <div style="width: 42px; height: 42px; border-radius: 12px; background: rgba(255,255,255,0.16); color: #ffffff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                    <line x1="6" y1="2" x2="6" y2="4"></line>
                                    <line x1="10" y1="2" x2="10" y2="4"></line>
                                    <line x1="14" y1="2" x2="14" y2="4"></line>
                                </svg>
                            </div>
                        </div>

                        {{-- Group 3: 3x2 Circular Coffee Stamp Slots --}}
                        <div style="padding: 1.35rem 1.25rem 1.15rem;">
                            <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.9rem; max-width: 280px; margin: 0 auto 1.25rem;"
                                 role="group"
                                 aria-label="{{ $isAr ? 'أختام البطاقة' : 'Loyalty stamp slots' }}">
                                <template x-for="slot in total" :key="slot">
                                    <div style="position: relative; aspect-ratio: 1; border-radius: 999px; display: flex; align-items: center; justify-content: center; transition: background-color 0.25s ease, border-color 0.25s ease;"
                                         :class="{ 'td-stamp-pop': justStamped && slot === total }"
                                         :aria-label="slot <= stamps ? '{{ $isAr ? 'ختم مكتمل' : 'Filled stamp' }}' : '{{ $isAr ? 'ختم متبقي' : 'Empty stamp' }}'"
                                         :style="slot <= stamps
                                            ? 'background: #743EE2; border: 2px solid #c4b5fd; color: #ffffff; box-shadow: 0 4px 12px rgba(116, 62, 226, 0.35);'
                                            : 'background: rgba(1, 10, 41, 0.55); border: 2px dashed rgba(196, 181, 253, 0.45); color: rgba(196, 181, 253, 0.48);'">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display: block;">
                                            <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                            <line x1="6" y1="2" x2="6" y2="4"></line>
                                            <line x1="10" y1="2" x2="10" y2="4"></line>
                                            <line x1="14" y1="2" x2="14" y2="4"></line>
                                        </svg>
                                        <span x-show="slot <= stamps"
                                              aria-hidden="true"
                                              style="position: absolute; bottom: -2px; inset-inline-end: -2px; width: 18px; height: 18px; border-radius: 999px; background: #10b981; border: 2px solid #0C1742; display: flex; align-items: center; justify-content: center;">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        </span>
                                    </div>
                                </template>
                            </div>

                            {{-- Group 4: Reward Panel (Accessible live region) --}}
                            <div aria-live="polite">
                                <div x-show="!completed"
                                     style="padding: 0.85rem 1rem; border-radius: 12px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(148, 163, 184, 0.22); color: #e2e8f0; font-size: 0.92rem; font-weight: 700; display: flex; align-items: center; gap: 0.55rem;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c4b5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                                        <polyline points="20 12 20 22 4 22 4 12"></polyline>
                                        <rect x="2" y="7" width="20" height="5"></rect>
                                        <line x1="12" y1="22" x2="12" y2="7"></line>
                                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                                    </svg>
                                    <span>{{ __('landing.demo_reward_label') }}</span>
                                </div>

                                <div x-show="completed"
                                     x-cloak
                                     style="padding: 0.9rem 1rem; border-radius: 12px; background: rgba(16, 185, 129, 0.18); border: 1px solid rgba(16, 185, 129, 0.55); color: #ecfdf5; font-size: 0.96rem; font-weight: 800; display: flex; align-items: center; gap: 0.6rem;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                                        <polyline points="20 12 20 22 4 22 4 12"></polyline>
                                        <rect x="2" y="7" width="20" height="5"></rect>
                                        <line x1="12" y1="22" x2="12" y2="7"></line>
                                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                                    </svg>
                                    <span>{{ __('landing.demo_reward_ready') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Controls --}}
                    <div style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.65rem;">
                        <button type="button"
                                x-show="!completed"
                                @click="addStamp()"
                                class="dl-btn-primary"
                                style="flex: 1; min-width: 190px; font-size: 0.98rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>{{ __('landing.demo_add_stamp') }}</span>
                        </button>

                        <button type="button"
                                x-show="completed"
                                x-cloak
                                @click="resetDemo()"
                                class="dl-btn-ghost"
                                style="flex: 1; min-width: 190px; border-color: rgba(16, 185, 129, 0.5); color: #6ee7b7; font-size: 0.95rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                            </svg>
                            <span>{{ __('landing.demo_replay') }}</span>
                        </button>
                    </div>

                    <p style="margin: 0.85rem 0 0; font-size: 0.875rem; color: #cbd5e1; text-align: center;">
                        {{ __('landing.demo_note') }}
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

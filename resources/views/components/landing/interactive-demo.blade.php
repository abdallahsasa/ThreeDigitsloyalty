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
    .td-stamp-slot {
        position: relative;
        aspect-ratio: 1 / 1;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.25s ease, border-color 0.25s ease;
    }
    .td-stamp-filled {
        background: #743EE2;
        border: 2px solid #c4b5fd;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(116, 62, 226, 0.35);
    }
    .td-stamp-empty {
        background: rgba(1, 10, 41, 0.55);
        border: 2px dashed rgba(196, 181, 253, 0.45);
        color: rgba(196, 181, 253, 0.48);
    }
    .td-stamp-check {
        position: absolute;
        bottom: -2px;
        inset-inline-end: -2px;
        width: 19px;
        height: 19px;
        border-radius: 999px;
        background: #ede9fe;
        border: 2px solid #0C1742;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .td-demo-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.5rem;
        align-items: center;
    }
    @media (min-width: 960px) {
        .td-demo-grid {
            grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr);
            gap: 3rem;
        }
    }
    .td-demo-stage {
        position: relative;
        width: 100%;
        max-width: 580px;
        margin: 0 auto;
    }
    .td-demo-photo-frame {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(196, 181, 253, 0.28);
        background: #071033;
        box-shadow: 0 24px 50px rgba(0, 0, 0, 0.4);
    }
    .td-demo-photo-frame img {
        display: block;
        width: 100%;
        height: auto;
        object-fit: cover;
    }
    .td-demo-phone {
        position: relative;
        max-width: 370px;
        margin: -2.25rem auto 0;
        z-index: 2;
    }
    @media (min-width: 640px) {
        .td-demo-stage {
            padding-bottom: 1rem;
        }
        .td-demo-photo-frame {
            width: 86%;
            margin-left: auto;
            margin-right: 0;
        }
        .td-demo-phone {
            max-width: 340px;
            margin: -11.5rem auto 0 0;
        }
    }
</style>

<section id="interactive-demo" class="dl-section" style="background: #010A29; border-bottom: 1px solid rgba(148, 163, 184, 0.14);">
    <div class="dl-container">
        <div class="td-demo-grid">
            {{-- Left/Start: Checkout Context & Invitation --}}
            <div>
                <span class="dl-eyebrow" style="margin-bottom: 0.9rem;">{{ $isAr ? 'جرّب البطاقة بنفسك' : 'Try the Card Live' }}</span>
                <h2 style="font-size: clamp(1.65rem, 3vw, 2.35rem); font-weight: 800; color: #f8fafc; margin: 0 0 0.85rem; line-height: 1.25;">
                    {{ $isAr ? 'شاهد كيف يختم الكاشير البطاقة ويفتح المكافأة في لحظة' : 'See how a final stamp unlocks a reward in one tap' }}
                </h2>
                <p style="font-size: 1rem; color: #cbd5e1; line-height: 1.75; margin: 0 0 1.5rem;">
                    {{ $isAr
                        ? 'اضغط على زر «جرّب إضافة ختم» في بطاقة مقهى الياسمين التفاعلية لترى كيف يتحدّث رصيد العميل من 5 إلى 6 أختام وتظهر له المكافأة فوراً بدون تحميل أي تطبيق.'
                        : 'Click “Try adding a stamp” on Jasmine Café’s interactive card to see the stamp animate into place, update from 5 of 6 to 6 of 6, and unlock a free coffee right in the browser.' }}
                </p>

                <ul style="list-style: none; padding: 0; margin: 0 0 1.75rem; display: grid; gap: 0.7rem; color: #e2e8f0; font-size: 0.95rem; font-weight: 600;">
                    <li style="display: flex; align-items: center; gap: 0.55rem;">
                        <span style="color: #a78bfa; font-weight: 800;">✓</span>
                        <span>{{ $isAr ? 'تحديث فوري لعدد الأختام وحالة المكافأة' : 'Instant stamp progress and reward-ready confirmation' }}</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 0.55rem;">
                        <span style="color: #a78bfa; font-weight: 800;">✓</span>
                        <span>{{ $isAr ? 'يعمل بالكامل على متصفح iPhone وAndroid' : 'Works directly in iPhone and Android browsers' }}</span>
                    </li>
                </ul>

                <a href="{{ route('merchant.register') }}"
                   onclick="window.trackFunnelEvent && window.trackFunnelEvent('primary_cta_clicked', { location: 'demo_section' })"
                   class="dl-btn-primary">
                    <span>{{ __('landing.hero_cta_1') }}</span>
                    <span aria-hidden="true">{{ $isAr ? '←' : '→' }}</span>
                </a>
            </div>

            {{-- Right/End: Café Checkout Photo + Interactive Phone Stamp Card --}}
            <div x-data="{
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
                <div class="td-demo-stage">
                    <div class="td-demo-photo-frame">
                        <picture>
                            <source media="(max-width: 639px)" srcset="{{ asset('images/landing/hero-checkout-mobile.webp') }}" width="800" height="540" type="image/webp">
                            <img src="{{ asset('images/landing/hero-checkout.webp') }}"
                                 alt="{{ $isAr ? 'باريستا يحضّر القهوة خلف طاولة الكاشير وبجانبه حامل رمز QR للولاء' : 'Barista preparing coffee behind a sunlit café counter next to a tabletop QR stand' }}"
                                 width="1280"
                                 height="720"
                                 loading="lazy">
                        </picture>
                    </div>

                    {{-- Phone-Style Interactive Card --}}
                    <div class="dl-card td-demo-phone" style="padding: 1.1rem 1.2rem 1.3rem; background: #071033; border: 2px solid rgba(196, 181, 253, 0.42); border-radius: 30px; box-shadow: 0 24px 55px rgba(1, 10, 41, 0.72);">
                        <div aria-hidden="true" style="width: 64px; height: 5px; border-radius: 999px; background: rgba(148, 163, 184, 0.32); margin: 0 auto 0.9rem;"></div>

                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <span class="dl-eyebrow">{{ __('landing.demo_badge') }}</span>
                            <span style="font-size: 0.95rem; font-weight: 800; color: #f8fafc;" x-text="formattedCount()"></span>
                        </div>

                        <div style="border-radius: 18px; overflow: hidden; border: 1px solid rgba(148, 163, 184, 0.22); background: #0C1742;">
                            <div style="padding: 1.15rem 1.25rem; background: linear-gradient(135deg, #1E1B4B 0%, #312E81 100%); display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; border-bottom: 1px solid rgba(196, 181, 253, 0.2);">
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

                            <div style="padding: 1.35rem 1.25rem 1.15rem;">
                                <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.9rem; max-width: 280px; margin: 0 auto 1.25rem;"
                                     role="group"
                                     aria-label="{{ $isAr ? 'أختام البطاقة' : 'Loyalty stamp slots' }}">
                                    <template x-for="slot in total" :key="slot">
                                        <div class="td-stamp-slot"
                                             :class="{
                                                 'td-stamp-filled': slot <= stamps,
                                                 'td-stamp-empty': slot > stamps,
                                                 'td-stamp-pop': justStamped && slot === total
                                             }"
                                             :aria-label="slot <= stamps ? '{{ $isAr ? 'ختم مكتمل' : 'Filled stamp' }}' : '{{ $isAr ? 'ختم متبقي' : 'Empty stamp' }}'">
                                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display: block;">
                                                <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                                <line x1="6" y1="2" x2="6" y2="4"></line>
                                                <line x1="10" y1="2" x2="10" y2="4"></line>
                                                <line x1="14" y1="2" x2="14" y2="4"></line>
                                            </svg>
                                            <span x-show="slot <= stamps" aria-hidden="true" class="td-stamp-check">
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#5B21B6" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                            </span>
                                        </div>
                                    </template>
                                </div>

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

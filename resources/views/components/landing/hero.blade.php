@php $isAr = app()->getLocale() === 'ar'; @endphp

<section style="padding: 3.25rem 0 3.75rem; background: radial-gradient(circle at top, rgba(116, 62, 226, 0.14), transparent 55%), #010A29;">
    <div class="dl-container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem; align-items: center;">
            {{-- Left / Start: Copy & CTAs --}}
            <div>
                <h1 style="font-size: clamp(2rem, 4.2vw, 3.25rem); font-weight: 800; line-height: 1.18; margin: 0 0 1.1rem; color: #f8fafc;">
                    {{ __('landing.hero_title') }}
                </h1>

                <p style="font-size: 1.05rem; color: #cbd5e1; line-height: 1.75; margin: 0 0 1.75rem; max-width: 560px;">
                    {{ __('landing.hero_subtitle') }}
                </p>

                <div style="display: flex; flex-wrap: wrap; gap: 0.85rem; margin-bottom: 1.75rem;">
                    <a href="{{ route('merchant.register') }}"
                       onclick="window.trackFunnelEvent && window.trackFunnelEvent('primary_cta_clicked', { location: 'hero' })"
                       class="dl-btn-primary">
                        <span>{{ __('landing.hero_cta_1') }}</span>
                        <span aria-hidden="true">{{ $isAr ? '←' : '→' }}</span>
                    </a>
                    <a href="#interactive-demo" class="dl-btn-ghost">
                        <span>{{ __('landing.hero_cta_2') }}</span>
                    </a>
                </div>

                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; color: #cbd5e1; font-size: 0.92rem; font-weight: 600;">
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

            {{-- Right / End: Isolated Interactive Café Card Demo (5 of 6 -> 6 of 6) --}}
            <div id="interactive-demo"
                 x-data="{
                     stamps: 5,
                     total: 6,
                     started: false,
                     completed: false,
                     addStamp() {
                         if (!this.started) {
                             this.started = true;
                             window.trackFunnelEvent && window.trackFunnelEvent('demo_started', {});
                         }
                         if (this.stamps < this.total) {
                             this.stamps++;
                             if (this.stamps === this.total && !this.completed) {
                                 this.completed = true;
                                 window.trackFunnelEvent && window.trackFunnelEvent('demo_completed', {});
                             }
                         }
                     },
                     resetDemo() {
                         this.stamps = 5;
                         this.completed = false;
                     },
                     formattedCount() {
                         const arDigits = ['٠','١','٢','٣','٤','٥','٦'];
                         @if($isAr)
                             return (arDigits[this.stamps] || this.stamps) + ' {{ __('landing.demo_stamps_of') }} ' + (arDigits[this.total] || this.total);
                         @else
                             return this.stamps + ' {{ __('landing.demo_stamps_of') }} ' + this.total;
                         @endif
                     }
                 }"
                 style="max-width: 440px; width: 100%; margin: 0 auto;">
                <div class="dl-card" style="padding: 1.5rem; background: #071033; border: 1px solid rgba(196, 181, 253, 0.28);">
                    {{-- Clear Demo Label --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                        <span class="dl-eyebrow">{{ __('landing.demo_badge') }}</span>
                        <span style="font-size: 0.8rem; font-weight: 700; color: #cbd5e1;" x-text="formattedCount()"></span>
                    </div>

                    {{-- Café Card Visual --}}
                    <div style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(148, 163, 184, 0.2); background: #0C1742;">
                        <div style="padding: 1.1rem 1.25rem; background: linear-gradient(135deg, #5B21B6 0%, #743EE2 100%); display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                            <div style="min-width: 0;">
                                <div style="font-size: 1.05rem; font-weight: 800; color: #ffffff;">{{ __('landing.demo_shop_name') }}</div>
                                <div style="font-size: 0.82rem; color: #ede9fe; font-weight: 600;">{{ __('landing.demo_program_name') }}</div>
                            </div>
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,0.16); color: #ffffff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                    <line x1="6" y1="1" x2="6" y2="4"></line>
                                    <line x1="10" y1="1" x2="10" y2="4"></line>
                                    <line x1="14" y1="1" x2="14" y2="4"></line>
                                </svg>
                            </div>
                        </div>

                        <div style="padding: 1.25rem;">
                            {{-- 6 Stamp Slots --}}
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.65rem; margin-bottom: 1rem;">
                                <template x-for="slot in total" :key="slot">
                                    <div style="aspect-ratio: 1.25; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.95rem; transition: background-color 0.25s ease, transform 0.25s ease, border-color 0.25s ease;"
                                         :style="slot <= stamps
                                            ? 'background: #10b981; color: #ffffff; border: 1px solid #34d399;'
                                            : 'background: rgba(255,255,255,0.03); color: #cbd5e1; border: 1px dashed rgba(148,163,184,0.4);'">
                                        <template x-if="slot <= stamps">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        </template>
                                        <template x-if="slot > stamps">
                                            <span x-text="slot"></span>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            {{-- Reward Status --}}
                            <div style="padding: 0.75rem 0.9rem; border-radius: 12px; font-size: 0.88rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;"
                                 :style="completed
                                    ? 'background: rgba(16, 185, 129, 0.16); border: 1px solid rgba(16, 185, 129, 0.45); color: #6ee7b7;'
                                    : 'background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(148, 163, 184, 0.18); color: #cbd5e1;'">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 12 20 22 4 22 4 12"></polyline>
                                    <rect x="2" y="7" width="20" height="5"></rect>
                                    <line x1="12" y1="22" x2="12" y2="7"></line>
                                    <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                                    <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                                </svg>
                                <span x-show="!completed">{{ __('landing.demo_reward_label') }}</span>
                                <span x-show="completed" x-cloak>{{ __('landing.demo_reward_ready') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Demo Action Controls --}}
                    <div style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.65rem;">
                        <button type="button"
                                x-show="!completed"
                                @click="addStamp()"
                                class="dl-btn-primary"
                                style="flex: 1; min-width: 180px;">
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
                                style="flex: 1; min-width: 180px; border-color: rgba(16, 185, 129, 0.45); color: #6ee7b7;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                            </svg>
                            <span>{{ __('landing.demo_replay') }}</span>
                        </button>
                    </div>

                    <p style="margin: 0.75rem 0 0; font-size: 0.8rem; color: #94a3b8; text-align: center;">
                        {{ __('landing.demo_note') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

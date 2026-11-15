<section class="dl-grid-bg" style="position: relative; padding: 5.5rem 0 4.5rem; overflow: hidden;">
    {{-- Ambient Luxury Glow --}}
    <div style="position: absolute; top: -120px; left: 50%; transform: translateX(-50%); width: 760px; height: 420px; background: radial-gradient(circle, rgba(116,62,226,0.16) 0%, rgba(16,185,129,0.08) 45%, transparent 72%); filter: blur(40px); pointer-events: none;"></div>

    <div class="dl-container" style="position: relative; text-align: center;">
        <div class="dl-eyebrow" style="margin-bottom: 1.5rem;">
            <span style="width: 7px; height: 7px; border-radius: 999px; background: #10b981; box-shadow: 0 0 10px #10b981;"></span>
            <span>{{ app()->getLocale() === 'ar' ? 'منصة الولاء الرقمية الفاخرة' : 'Next-Gen Digital Loyalty & Passbook' }}</span>
        </div>

        <h1 class="font-heading" style="font-size: clamp(2.4rem, 5.5vw, 4.3rem); font-weight: 800; line-height: 1.08; letter-spacing: -0.03em; max-width: 860px; margin: 0 auto 1.35rem;">
            {{ __('landing.hero_title') }}
        </h1>

        <p style="font-size: 1.15rem; color: #94a3b8; max-width: 660px; margin: 0 auto 2.25rem; line-height: 1.7;">
            {{ __('landing.hero_subtitle') }}
        </p>

        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-bottom: 4rem;">
            <a href="{{ url('/shop/login') }}" class="dl-btn-gold">
                <span>{{ __('landing.hero_cta_1') }}</span>
                <span>→</span>
            </a>
            <a href="#features" class="dl-btn-ghost">
                <span>{{ __('landing.hero_cta_2') }}</span>
            </a>
        </div>

        {{-- Dark Luxury Console + Digital Pass Mockup --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 1.5rem; align-items: stretch; max-width: 1020px; margin: 0 auto; text-align: start;">
            {{-- Merchant Analytics Console --}}
            <div class="dl-card" style="padding: 1.5rem; background: linear-gradient(180deg, #09143E 0%, #050C2A 100%); grid-column: span 2 / span 2;">
                <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 1rem; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.07);">
                    <div style="display: flex; align-items: center; gap: 0.45rem;">
                        <span style="width: 10px; height: 10px; border-radius: 999px; background: #ef4444;"></span>
                        <span style="width: 10px; height: 10px; border-radius: 999px; background: #f59e0b;"></span>
                        <span style="width: 10px; height: 10px; border-radius: 999px; background: #10b981;"></span>
                    </div>
                    <span style="font-size: 0.78rem; font-weight: 600; color: #64748b;">ThreeDigits Merchant Terminal</span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 14px; padding: 1rem;">
                        <div style="font-size: 0.75rem; color: #94a3b8;">Active Passes</div>
                        <div style="font-size: 1.65rem; font-weight: 800; margin-top: 0.25rem;">2,840</div>
                        <div style="font-size: 0.75rem; color: #10b981; font-weight: 600; margin-top: 0.2rem;">+24.6% MoM</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 14px; padding: 1rem;">
                        <div style="font-size: 0.75rem; color: #94a3b8;">Stamps Today</div>
                        <div class="dl-gold-text" style="font-size: 1.65rem; font-weight: 800; margin-top: 0.25rem;">418</div>
                        <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.2rem;">6 active branches</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 14px; padding: 1rem;">
                        <div style="font-size: 0.75rem; color: #94a3b8;">Rewards Redeemed</div>
                        <div style="font-size: 1.65rem; font-weight: 800; color: #10b981; margin-top: 0.25rem;">92</div>
                        <div style="font-size: 0.75rem; color: #10b981; font-weight: 600; margin-top: 0.2rem;">96% repeat rate</div>
                    </div>
                </div>

                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 14px; padding: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem;">
                        <span style="font-size: 0.8rem; font-weight: 600; color: #cbd5e1;">Weekly Stamp Velocity</span>
                        <span style="font-size: 0.7rem; font-weight: 700; color: #10b981; background: rgba(16,185,129,0.12); padding: 0.2rem 0.6rem; border-radius: 999px;">LIVE</span>
                    </div>
                    <div style="display: flex; align-items: flex-end; gap: 0.6rem; height: 90px;">
                        @foreach([38, 52, 46, 68, 76, 92, 84] as $idx => $h)
                            <div style="flex: 1; height: {{ $h }}%; border-radius: 8px 8px 3px 3px; background: {{ $idx === 5 ? 'linear-gradient(180deg, #A78BFA, #743EE2)' : 'linear-gradient(180deg, rgba(116,62,226,0.45), rgba(116,62,226,0.12))' }};"></div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Luxury Digital Pass Card --}}
            <div class="dl-card dl-float" style="padding: 1.5rem; background: linear-gradient(145deg, #0C1742 0%, #040D30 100%); border: 1px solid rgba(116,62,226,0.35); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                        <div>
                            <div style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: #743EE2; font-weight: 700;">VIP Pass</div>
                            <div style="font-size: 1.1rem; font-weight: 800;">Roast & Co Coffee</div>
                        </div>
                        <span style="padding: 0.25rem 0.65rem; border-radius: 999px; background: rgba(16,185,129,0.15); color: #10b981; font-size: 0.72rem; font-weight: 700;">6 / 8</span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; margin-bottom: 1.25rem;">
                        @for($i = 1; $i <= 8; $i++)
                            <div style="aspect-ratio: 1; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 800; {{ $i <= 6 ? 'background: linear-gradient(135deg, #A78BFA, #743EE2); color: #ffffff;' : 'border: 1px dashed rgba(255,255,255,0.2); color: #64748b;' }}">
                                {{ $i <= 6 ? '✓' : $i }}
                            </div>
                        @endfor
                    </div>
                </div>

                <div style="padding-top: 1rem; border-top: 1px dashed rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 0.72rem; color: #94a3b8;">Next Reward</div>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #f8fafc;">Free Signature Latte</div>
                    </div>
                    <div style="width: 44px; height: 44px; background: #ffffff; border-radius: 10px; padding: 4px; display: flex; align-items: center; justify-content: center;">
                        <div style="width: 100%; height: 100%; background: repeating-conic-gradient(#010A29 0% 25%, #fff 0% 50%) 50% / 10px 10px; border-radius: 4px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

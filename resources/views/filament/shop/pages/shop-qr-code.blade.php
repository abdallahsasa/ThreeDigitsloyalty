<x-filament-panels::page>
    @php
        $isAr = app()->getLocale() === 'ar';
        $cardBg = $shop ? $shop->resolvedCardBgColor() : '#743EE2';
        $cardAccent = $shop ? $shop->resolvedCardAccentColor() : '#3B42D5';
        $cardText = $shop ? $shop->resolvedCardTextColor() : '#FFFFFF';
        $category = $shop ? $shop->resolvedCategory() : 'cafe';
        $categorySubtitle = $shop ? $shop->categoryPosterSubtitle($isAr ? 'ar' : 'en') : 'COFFEE';
        $hasArabicShopName = $shop ? (bool) preg_match('/\p{Arabic}/u', (string) $shop->name) : false;
        $reqStamps = (int) ($campaign?->required_purchases_count ?? 8);
        $stampsWordAr = ($reqStamps >= 3 && $reqStamps <= 10) ? 'أختام' : 'ختماً';
    @endphp

    <style>
        .td-qr-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.75rem;
            align-items: start;
        }
        @media (min-width: 980px) {
            .td-qr-grid {
                grid-template-columns: 440px 1fr;
            }
        }
        .td-poster-card {
            background: #F7F1E8;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 22px 48px -14px rgba(15, 10, 8, 0.3), 0 0 0 1px rgba(30, 22, 18, 0.12);
        }
        .td-arch-top {
            background: {{ $cardBg }};
            color: {{ $cardText }};
            padding: 0;
        }
        .td-arch-header-inner {
            padding: 1.45rem 1.65rem 0;
        }
        .td-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.24);
        }
        .td-brand-side {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            min-width: 0;
        }
        .td-poster-logo {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            background: #ffffff;
            padding: 2.5px;
            flex-shrink: 0;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .td-poster-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            display: block;
        }
        .td-poster-shop {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.15;
            margin: 0;
            color: {{ $cardText }};
        }
        .td-poster-cat {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            opacity: 0.86;
            margin-top: 0.2rem;
            color: {{ $cardText }};
        }
        .td-poster-cat.ar-cat {
            letter-spacing: 0;
            font-size: 0.74rem;
        }
        .td-cat-icon {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            color: {{ $cardText }};
        }
        .td-arch-dome {
            display: block;
            width: 100%;
            height: 102px;
            margin-top: 1.65rem;
            margin-bottom: -1px;
        }
        .td-arch-accent {
            background: {{ $cardAccent }};
            color: {{ $cardText }};
            padding: 1px 1.5rem 1.65rem;
            text-align: center;
        }
        .td-medallion {
            width: 128px;
            height: 128px;
            margin: -130px auto 0.9rem;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 12px 20px rgba(15, 10, 8, 0.34));
        }
        .td-offer-line1 {
            font-size: 1.65rem;
            font-weight: 900;
            line-height: 1.25;
            color: {{ $cardText }};
        }
        .td-offer-line2 {
            font-size: 1.18rem;
            font-weight: 800;
            line-height: 1.35;
            color: #F6CFA0;
            margin-top: 0.2rem;
        }
        .td-cream-bottom {
            background: #F7F1E8;
            color: #1E1612;
            padding: 1.45rem 1.65rem 1.15rem;
        }
        .td-qr-split {
            display: grid;
            grid-template-columns: auto 1.5px 1fr;
            align-items: center;
            gap: 1.15rem;
        }
        .td-qr-frame {
            display: inline-flex;
            padding: 0.6rem;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(30, 22, 18, 0.08);
        }
        .td-qr-sep {
            width: 1.5px;
            height: 98px;
            background: rgba(30, 22, 18, 0.2);
        }
        .td-qr-cta {
            text-align: center;
            font-size: 1.22rem;
            font-weight: 900;
            line-height: 1.35;
            color: #1E1612;
        }
        .td-qr-sub {
            margin-top: 0.28rem;
            font-size: 0.78rem;
            font-weight: 700;
            color: #5C4E46;
        }
        .td-powered-footer {
            margin-top: 1.15rem;
            padding-top: 0.8rem;
            border-top: 1px solid rgba(30, 22, 18, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.76rem;
            font-weight: 700;
            color: #3B302A;
            direction: ltr;
        }
        .td-btn-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            margin-top: 1rem;
        }
        .td-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: linear-gradient(135deg, {{ $cardBg }} 0%, {{ $cardAccent }} 100%);
            color: {{ $cardText }};
            text-decoration: none;
            font-weight: 700;
            font-size: 0.84rem;
            padding: 0.68rem 1.15rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 20px -6px rgba(15, 23, 42, 0.35);
        }
        .td-btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.84rem;
            padding: 0.68rem 1.15rem;
            border-radius: 12px;
            cursor: pointer;
        }
        .dark .td-btn-ghost {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.16);
            color: #f8fafc;
        }
        .td-steps-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 1.75rem;
        }
        .dark .td-steps-card {
            background: #111827;
            border-color: rgba(255, 255, 255, 0.1);
        }
        .td-steps-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #010A29;
            margin-bottom: 0.35rem;
        }
        .dark .td-steps-title {
            color: #f8fafc;
        }
        .td-steps-desc {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 1.35rem;
        }
        .td-step-list {
            display: grid;
            gap: 0.85rem;
        }
        .td-step-item {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            padding: 1rem 1.1rem;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .dark .td-step-item {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.08);
        }
        .td-step-num {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, {{ $cardBg }}, {{ $cardAccent }});
            color: {{ $cardText }};
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
            flex-shrink: 0;
        }
        .td-step-text {
            font-size: 0.88rem;
            line-height: 1.6;
            color: #334155;
        }
        .dark .td-step-text {
            color: #cbd5e1;
        }
    </style>

    @if($shop)
        <div class="td-qr-grid">
            {{-- Left: Live Tent-Card Poster Preview --}}
            <div>
                <div class="td-poster-card">
                    <div class="td-arch-top">
                        <div class="td-arch-header-inner">
                            <div class="td-header-row" dir="{{ $hasArabicShopName ? 'rtl' : 'ltr' }}">
                                <div class="td-brand-side">
                                    @if($shop->logo_url)
                                        <div class="td-poster-logo">
                                            <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}">
                                        </div>
                                    @endif
                                    <div style="min-width: 0; text-align: start;">
                                        <h2 class="td-poster-shop">{{ $shop->name }}</h2>
                                        <div class="td-poster-cat {{ $hasArabicShopName ? 'ar-cat' : '' }}">{{ $categorySubtitle }}</div>
                                    </div>
                                </div>

                                <div class="td-cat-icon">
                                    <x-poster.category-art :category="$category" mode="icon" />
                                </div>
                            </div>
                        </div>

                        <svg class="td-arch-dome" viewBox="0 0 500 130" preserveAspectRatio="none" aria-hidden="true">
                            <path d="M0,130 C90,10 410,10 500,130 L500,130 L0,130 Z" fill="{{ $cardAccent }}"/>
                        </svg>
                    </div>

                    <div class="td-arch-accent">
                        <div class="td-medallion">
                            <x-poster.category-art :category="$category" mode="medallion" :accent="$cardAccent" :bg="$cardBg" />
                        </div>

                        @if($campaign)
                            <div class="td-offer-line1">
                                {{ $isAr ? "جمّع {$reqStamps} {$stampsWordAr}،" : "Collect {$reqStamps} stamps," }}
                            </div>
                            <div class="td-offer-line2">
                                @if($isAr)
                                    وخُذ {{ $campaign->reward_name }}{{ str_contains($campaign->reward_name, 'مجان') ? '.' : ' مجاناً.' }}
                                @else
                                    and get {{ $campaign->reward_name }}!
                                @endif
                            </div>
                        @else
                            <div class="td-offer-line1">
                                {{ $isAr ? 'بطاقة أختامك الرقمية' : 'Your Digital Stamp Card' }}
                            </div>
                            <div class="td-offer-line2">
                                {{ $isAr ? 'اجمع الأختام واستبدلها بمكافآت مجانية.' : 'Collect stamps & unlock free rewards.' }}
                            </div>
                        @endif
                    </div>

                    <div class="td-cream-bottom">
                        <div class="td-qr-split" dir="ltr">
                            <div class="td-qr-frame">
                                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(126)->margin(0)->generate($joinUrl) !!}
                            </div>
                            <div class="td-qr-sep" aria-hidden="true"></div>
                            <div class="td-qr-cta" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
                                @if($isAr)
                                    امسح لتفتح<br>بطاقة أختامك
                                @else
                                    Scan to open<br>your stamp card
                                @endif
                                <div class="td-qr-sub">
                                    {{ $isAr ? 'بدون تنزيل تطبيق' : 'No app download needed' }}
                                </div>
                            </div>
                        </div>

                        <div class="td-powered-footer">
                            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 20px; width: auto; display: block;">
                            <span>Powered by ThreeDigits Loyalty</span>
                        </div>
                    </div>
                </div>

                <div class="td-btn-row">
                    <a href="{{ route('shop.join.poster', ['shop' => $shop->slug ?: $shop->id, 'print' => 1, 'style' => 'arch']) }}" target="_blank" class="td-btn-primary">
                        <span>🖨️</span>
                        <span>{{ $isAr ? 'طباعة البوستر المميز (PDF)' : 'Print Featured Poster (PDF)' }}</span>
                    </a>

                    <a href="{{ route('shop.join.poster', ['shop' => $shop->slug ?: $shop->id, 'print' => 1, 'style' => 'minimal']) }}" target="_blank" class="td-btn-ghost">
                        <span>📄</span>
                        <span>{{ $isAr ? 'طباعة التصميم الكلاسيكي' : 'Print Classic Cream Poster' }}</span>
                    </a>

                    <a href="{{ url("/shop/{$shop->id}/card-branding") }}" class="td-btn-ghost">
                        <span>🎨</span>
                        <span>{{ $isAr ? 'تغيير الألوان والأيقونة' : 'Customize Colors & Icon' }}</span>
                    </a>
                </div>
            </div>

            {{-- Right: How It Works Steps --}}
            <div class="td-steps-card">
                <h3 class="td-steps-title">
                    {{ $isAr ? 'كيف يعمل رمز QR لعملائك؟' : 'How It Works for Your Customers' }}
                </h3>
                <p class="td-steps-desc">
                    {{ $isAr
                        ? 'اعرض هذا البوستر عند الكاشير أو على الطاولات لتمكين عملائك من فتح بطاقة الأختام في ثوانٍ.'
                        : 'Display or print this counter stand at checkout so customers can open their stamp card in seconds.' }}
                </p>

                <div class="td-step-list">
                    <div class="td-step-item">
                        <div class="td-step-num">1</div>
                        <div class="td-step-text">
                            @if($isAr)
                                يمسح العميل رمز QR الخاص بمتجر <strong>{{ $shop->name }}</strong> بكاميرا هاتفه مباشرة بدون تنزيل أي تطبيق.
                            @else
                                Customer scans your <strong>{{ $shop->name }}</strong> QR code with their phone camera — no app download needed.
                            @endif
                        </div>
                    </div>

                    <div class="td-step-item">
                        <div class="td-step-num">2</div>
                        <div class="td-step-text">
                            @if($isAr)
                                يدخل العميل <strong>الاسم ورقم الجوال</strong> فقط لتفتح بطاقة أختامه فوراً في المتصفح.
                            @else
                                They enter their <strong>Name and Phone Number</strong> to open their stamp card right in the browser.
                            @endif
                        </div>
                    </div>

                    <div class="td-step-item">
                        <div class="td-step-num">3</div>
                        <div class="td-step-text">
                            @if($isAr)
                                يمكنه حفظ البطاقة في <strong>Apple Wallet أو Google Wallet</strong> وتلقي إشعارات الأختام والمكافآت تلقائياً.
                            @else
                                Optionally save to <strong>Apple Wallet or Google Wallet</strong> for instant stamp and reward updates.
                            @endif
                        </div>
                    </div>
                </div>

                <div style="margin-top: 1.25rem; padding: 0.85rem 1rem; border-radius: 14px; background: rgba(116, 62, 226, 0.06); border: 1px dashed rgba(116, 62, 226, 0.28); font-size: 0.82rem; color: #475569;">
                    <div style="font-weight: 800; margin-bottom: 0.25rem; color: #0f172a;">
                        {{ $isAr ? 'رابط صفحة التسجيل المباشر:' : 'Direct Customer Join Link:' }}
                    </div>
                    <a href="{{ $joinUrl }}" target="_blank" style="color: {{ $cardBg }}; font-weight: 700; word-break: break-all;">{{ $joinUrl }}</a>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>

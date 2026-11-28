@php
    $isAr = app()->getLocale() === 'ar';
    $cardBg = $shop->resolvedCardBgColor();
    $cardAccent = $shop->resolvedCardAccentColor();
    $cardText = $shop->resolvedCardTextColor();
    $category = $shop->resolvedCategory();
    $categorySubtitle = $shop->categoryPosterSubtitle($isAr ? 'ar' : 'en');
    $hasArabicShopName = (bool) preg_match('/\p{Arabic}/u', (string) $shop->name);
    $style = request()->query('style') === 'minimal' ? 'minimal' : 'arch';
    $reqStamps = (int) ($campaign?->required_purchases_count ?? 8);
    $stampsWordAr = ($reqStamps >= 3 && $reqStamps <= 10) ? 'أختام' : 'ختماً';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ $shop->logo_url ?: asset('images/logo-icon.png') }}">
    <title>{{ $shop->name }} — QR Poster</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;600;700;800;900&family=Playfair+Display:wght@700;800&family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --card-bg: {{ $cardBg }};
            --card-accent: {{ $cardAccent }};
            --card-text: {{ $cardText }};
            --paper-cream: #F7F1E8;
            --paper-ink: #1E1612;
            --paper-muted: #5C4E46;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Cairo', 'Montserrat', sans-serif;
            background: #EAE4DC;
            color: var(--paper-ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.75rem 1rem 2.5rem;
        }

        /* Screen-only Controls Toolbar */
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            margin-bottom: 1.35rem;
        }

        .style-switch {
            display: inline-flex;
            background: rgba(255, 255, 255, 0.85);
            padding: 0.25rem;
            border-radius: 999px;
            border: 1px solid rgba(30, 22, 18, 0.12);
            gap: 0.2rem;
        }

        .style-pill {
            padding: 0.45rem 0.95rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 800;
            text-decoration: none;
            color: #475569;
            transition: all 0.15s ease;
        }

        .style-pill.active {
            background: var(--card-bg);
            color: var(--card-text);
        }

        .toolbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.62rem 1.3rem;
            border-radius: 999px;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 800;
            border: none;
            cursor: pointer;
            text-decoration: none;
            background: linear-gradient(135deg, {{ $cardBg }} 0%, {{ $cardAccent }} 100%);
            color: {{ $cardText }};
            box-shadow: 0 10px 22px -6px rgba(30, 22, 18, 0.35);
        }

        /* Printable Tent-Card Sheet */
        .poster-sheet {
            width: 100%;
            max-width: 500px;
            background: var(--paper-cream);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 28px 60px -15px rgba(20, 14, 10, 0.35), 0 0 0 1px rgba(30, 22, 18, 0.12);
        }

        /* =========================================================
           STYLE 1: FEATURED ARCH TENT POSTER (Default)
           ========================================================= */
        .arch-top-band {
            background: {{ $cardBg }};
            color: {{ $cardText }};
            padding: 0;
            position: relative;
        }

        .arch-header-inner {
            padding: 1.85rem 2.15rem 0;
        }

        .poster-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-bottom: 1.05rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.24);
        }

        .poster-brand-side {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 0;
        }

        .shop-logo-badge {
            width: 52px;
            height: 52px;
            border-radius: 13px;
            background: #ffffff;
            padding: 3px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.22);
            flex-shrink: 0;
            overflow: hidden;
        }

        .shop-logo-badge img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            display: block;
        }

        .shop-title-group {
            min-width: 0;
            text-align: start;
        }

        .shop-title {
            font-size: 1.85rem;
            font-weight: 800;
            line-height: 1.12;
            letter-spacing: -0.01em;
            word-break: break-word;
        }

        .shop-title.latin-display {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 800;
        }

        .shop-category-label {
            margin-top: 0.28rem;
            font-size: 0.76rem;
            font-weight: 700;
            opacity: 0.88;
        }

        .shop-category-label.caps {
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            font-size: 0.72rem;
        }

        .category-icon-box {
            width: 58px;
            height: 58px;
            flex-shrink: 0;
            color: {{ $cardText }};
        }

        /* Dome Arch Transition */
        .arch-spacer {
            height: 2.1rem;
        }

        .arch-dome-svg {
            display: block;
            width: 100%;
            height: 126px;
            margin-bottom: -1px;
        }

        .arch-accent-band {
            background: {{ $cardAccent }};
            color: {{ $cardText }};
            padding: 1px 2rem 2.1rem;
            text-align: center;
            position: relative;
        }

        .medallion-wrap {
            width: 156px;
            height: 156px;
            margin: -158px auto 1.15rem;
            border-radius: 999px;
            position: relative;
            z-index: 3;
            filter: drop-shadow(0 16px 24px rgba(15, 10, 8, 0.36));
        }

        .offer-headline-primary {
            font-size: 2.15rem;
            font-weight: 900;
            line-height: 1.22;
            color: {{ $cardText }};
            margin-bottom: 0.25rem;
        }

        .offer-headline-reward {
            font-size: 1.48rem;
            font-weight: 800;
            line-height: 1.35;
            color: #F6CFA0;
        }

        /* Warm Cream Lower Split Section */
        .cream-lower-band {
            background: var(--paper-cream);
            color: var(--paper-ink);
            padding: 1.85rem 2.15rem 1.35rem;
        }

        .qr-split-row {
            display: grid;
            grid-template-columns: auto 1.5px 1fr;
            align-items: center;
            gap: 1.45rem;
        }

        .qr-white-frame {
            background: #FFFFFF;
            padding: 0.75rem;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(30, 22, 18, 0.08);
            display: inline-flex;
        }

        .qr-divider-v {
            width: 1.5px;
            height: 118px;
            background: rgba(30, 22, 18, 0.22);
        }

        .qr-cta-copy {
            text-align: center;
        }

        .qr-cta-title {
            font-size: 1.55rem;
            font-weight: 900;
            line-height: 1.35;
            color: var(--paper-ink);
        }

        .qr-cta-sub {
            margin-top: 0.35rem;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--paper-muted);
        }

        /* =========================================================
           STYLE 2: CLASSIC CREAM TENT POSTER (?style=minimal)
           ========================================================= */
        .minimal-sheet {
            background: var(--paper-cream);
            color: var(--paper-ink);
            padding: 2rem 2.25rem 1.45rem;
            text-align: center;
            border-top: 8px solid {{ $cardBg }};
        }

        .minimal-sheet .poster-header-row {
            border-bottom: 1.5px solid rgba(30, 22, 18, 0.18);
            color: {{ $cardBg }};
        }

        .minimal-sheet .category-icon-box {
            color: {{ $cardBg }};
        }

        .minimal-headline {
            margin: 2rem 0 1.5rem;
        }

        .minimal-headline-1 {
            font-size: 2.15rem;
            font-weight: 900;
            line-height: 1.25;
            color: var(--paper-ink);
        }

        .minimal-headline-2 {
            font-size: 2.05rem;
            font-weight: 900;
            line-height: 1.25;
            color: {{ $cardAccent }};
        }

        .minimal-reward-pill {
            display: inline-block;
            margin-top: 0.65rem;
            padding: 0.35rem 0.95rem;
            border-radius: 999px;
            background: rgba(30, 22, 18, 0.06);
            color: var(--paper-ink);
            font-size: 0.86rem;
            font-weight: 800;
        }

        .minimal-qr-wrap {
            display: inline-flex;
            background: #FFFFFF;
            padding: 1.05rem;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(30, 22, 18, 0.08);
            margin-bottom: 1.35rem;
        }

        .minimal-no-app {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--paper-ink);
            margin-bottom: 0.4rem;
        }

        /* Shared Powered-By Footer */
        .poster-footer {
            margin-top: 1.45rem;
            padding-top: 0.95rem;
            border-top: 1px solid rgba(30, 22, 18, 0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            font-size: 0.82rem;
            font-weight: 700;
            color: #3B302A;
            direction: ltr;
        }

        .poster-footer img.td-logo {
            height: 22px;
            width: auto;
            display: block;
        }

        @media (max-width: 480px) {
            .poster-header-row {
                padding-bottom: 0.85rem;
            }
            .shop-title {
                font-size: 1.5rem;
            }
            .offer-headline-primary {
                font-size: 1.75rem;
            }
            .offer-headline-reward {
                font-size: 1.22rem;
            }
            .qr-split-row {
                grid-template-columns: 1fr;
                justify-items: center;
                gap: 1rem;
            }
            .qr-divider-v {
                display: none;
            }
        }

        @media print {
            body {
                background: #FFFFFF !important;
                padding: 0 !important;
            }
            .toolbar {
                display: none !important;
            }
            .poster-sheet {
                box-shadow: none !important;
                border: 1px solid rgba(30, 22, 18, 0.2) !important;
                margin: 0 auto !important;
            }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <div class="style-switch" role="group" aria-label="{{ $isAr ? 'نمط البوستر' : 'Poster Style' }}">
        <a href="{{ route('shop.join.poster', ['shop' => $shop->slug ?: $shop->id, 'style' => 'arch']) }}"
           class="style-pill {{ $style === 'arch' ? 'active' : '' }}">
            {{ $isAr ? 'تصميم القوس المميز' : 'Featured Arch' }}
        </a>
        <a href="{{ route('shop.join.poster', ['shop' => $shop->slug ?: $shop->id, 'style' => 'minimal']) }}"
           class="style-pill {{ $style === 'minimal' ? 'active' : '' }}">
            {{ $isAr ? 'التصميم الكلاسيكي الفاتح' : 'Classic Cream' }}
        </a>
    </div>

    <button type="button" class="toolbar-btn" onclick="window.print()">
        <span>🖨️</span>
        <span>{{ $isAr ? 'طباعة / حفظ كملف PDF' : 'Print / Save as PDF' }}</span>
    </button>
</div>

@if($style === 'minimal')
    {{-- =========================================================
         CLASSIC CREAM COUNTER POSTER (Left tent-card in reference)
         ========================================================= --}}
    <div class="poster-sheet minimal-sheet">
        <div class="poster-header-row" dir="{{ $hasArabicShopName ? 'rtl' : 'ltr' }}">
            <div class="poster-brand-side">
                @if($shop->logo_url)
                    <div class="shop-logo-badge">
                        <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}">
                    </div>
                @endif
                <div class="shop-title-group">
                    <h1 class="shop-title {{ ! $hasArabicShopName ? 'latin-display' : '' }}">{{ $shop->name }}</h1>
                    <div class="shop-category-label {{ ! $hasArabicShopName ? 'caps' : '' }}">{{ $categorySubtitle }}</div>
                </div>
            </div>

            <div class="category-icon-box">
                <x-poster.category-art :category="$category" mode="icon" />
            </div>
        </div>

        <div class="minimal-headline">
            <div class="minimal-headline-1">
                {{ $isAr ? 'بطاقة أختامك' : 'Your stamp card' }}
            </div>
            <div class="minimal-headline-2">
                {{ $isAr ? 'على موبايلك' : 'on your phone' }}
            </div>
            @if($campaign)
                <div class="minimal-reward-pill">
                    @if($isAr)
                        جمّع {{ $reqStamps }} {{ $stampsWordAr }} ← {{ $campaign->reward_name }}
                    @else
                        Collect {{ $reqStamps }} stamps → {{ $campaign->reward_name }}
                    @endif
                </div>
            @endif
        </div>

        <div class="minimal-qr-wrap">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(210)->margin(0)->generate($joinUrl) !!}
        </div>

        <div class="minimal-no-app">
            {{ $isAr ? 'بدون تنزيل تطبيق' : 'No app download needed' }}
        </div>

        <div class="poster-footer">
            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" class="td-logo">
            <span>Powered by ThreeDigits Loyalty</span>
        </div>
    </div>
@else
    {{-- =========================================================
         FEATURED SPLIT-ARCH COUNTER POSTER (Right tent-card in reference)
         ========================================================= --}}
    <div class="poster-sheet">
        <div class="arch-top-band">
            <div class="arch-header-inner">
                <div class="poster-header-row" dir="{{ $hasArabicShopName ? 'rtl' : 'ltr' }}">
                    <div class="poster-brand-side">
                        @if($shop->logo_url)
                            <div class="shop-logo-badge">
                                <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}">
                            </div>
                        @endif
                        <div class="shop-title-group">
                            <h1 class="shop-title {{ ! $hasArabicShopName ? 'latin-display' : '' }}">{{ $shop->name }}</h1>
                            <div class="shop-category-label {{ ! $hasArabicShopName ? 'caps' : '' }}">{{ $categorySubtitle }}</div>
                        </div>
                    </div>

                    <div class="category-icon-box">
                        <x-poster.category-art :category="$category" mode="icon" />
                    </div>
                </div>
            </div>

            <div class="arch-spacer"></div>

            {{-- Smooth Edge-to-Edge Upward Dome Arch Curve --}}
            <svg class="arch-dome-svg" viewBox="0 0 500 130" preserveAspectRatio="none" aria-hidden="true">
                <path d="M0,130 C90,10 410,10 500,130 L500,130 L0,130 Z" fill="{{ $cardAccent }}"/>
            </svg>
        </div>

        <div class="arch-accent-band">
            <div class="medallion-wrap">
                <x-poster.category-art :category="$category" mode="medallion" :accent="$cardAccent" :bg="$cardBg" />
            </div>

            @if($campaign)
                <div class="offer-headline-primary">
                    @if($isAr)
                        جمّع {{ $reqStamps }} {{ $stampsWordAr }}،
                    @else
                        Collect {{ $reqStamps }} stamps,
                    @endif
                </div>
                <div class="offer-headline-reward">
                    @if($isAr)
                        وخُذ {{ $campaign->reward_name }}{{ str_contains($campaign->reward_name, 'مجان') ? '.' : ' مجاناً.' }}
                    @else
                        and get {{ $campaign->reward_name }}!
                    @endif
                </div>
            @else
                <div class="offer-headline-primary">
                    {{ $isAr ? 'بطاقة أختامك الرقمية' : 'Your Digital Stamp Card' }}
                </div>
                <div class="offer-headline-reward">
                    {{ $isAr ? 'اجمع الأختام واستبدلها بمكافآت مجانية.' : 'Collect stamps & unlock free rewards.' }}
                </div>
            @endif
        </div>

        <div class="cream-lower-band">
            <div class="qr-split-row" dir="ltr">
                <div class="qr-white-frame">
                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(152)->margin(0)->generate($joinUrl) !!}
                </div>

                <div class="qr-divider-v" aria-hidden="true"></div>

                <div class="qr-cta-copy" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
                    <div class="qr-cta-title">
                        @if($isAr)
                            امسح لتفتح<br>بطاقة أختامك
                        @else
                            Scan to open<br>your stamp card
                        @endif
                    </div>
                    <div class="qr-cta-sub">
                        {{ $isAr ? 'بدون تنزيل تطبيق' : 'No app download needed' }}
                    </div>
                </div>
            </div>

            <div class="poster-footer">
                <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" class="td-logo">
                <span>Powered by ThreeDigits Loyalty</span>
            </div>
        </div>
    </div>
@endif

@if(request()->boolean('print'))
<script>
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 350);
    });
</script>
@endif
</body>
</html>

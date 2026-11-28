@php
    $isAr = app()->getLocale() === 'ar';
    $cardBg = $shop->resolvedCardBgColor();
    $cardAccent = $shop->resolvedCardAccentColor();
    $cardText = $shop->resolvedCardTextColor();
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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --card-bg: {{ $cardBg }};
            --card-accent: {{ $cardAccent }};
            --card-text: {{ $cardText }};
        }

        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', 'Cairo', sans-serif !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            background: #f1f5f9;
            color: #010A29;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .toolbar {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .toolbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.65rem 1.25rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            background: linear-gradient(135deg, var(--card-bg) 0%, var(--card-accent) 100%);
            color: var(--card-text);
            box-shadow: 0 8px 20px -6px rgba(15, 23, 42, 0.35);
        }

        .poster-sheet {
            width: 100%;
            max-width: 520px;
            background: linear-gradient(165deg, #010A29 0%, #09143E 100%);
            color: #ffffff;
            border-radius: 32px;
            overflow: hidden;
            text-align: center;
            border: 2px solid var(--card-bg);
            box-shadow: 0 25px 50px -12px rgba(1, 10, 41, 0.35);
        }

        .poster-top {
            background: linear-gradient(135deg, var(--card-bg) 0%, var(--card-accent) 100%);
            color: var(--card-text);
            padding: 2rem 2rem 1.85rem;
        }

        .brand-row {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(1, 10, 41, 0.78);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.24);
            padding: 0.38rem 0.95rem;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .shop-logo-frame {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            margin: 0 auto 0.85rem;
            background: #ffffff;
            padding: 4px;
            border: 2px solid rgba(255, 255, 255, 0.55);
            box-shadow: 0 10px 24px rgba(1, 10, 41, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .shop-logo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 14px;
            display: block;
        }

        .shop-name {
            font-size: 2rem;
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 0.4rem;
            color: var(--card-text);
        }

        .shop-tagline {
            font-size: 0.95rem;
            color: var(--card-text);
            opacity: 0.9;
            font-weight: 600;
        }

        .poster-content {
            padding: 2.15rem 2rem 1.85rem;
        }

        .qr-card {
            display: inline-flex;
            padding: 1.35rem;
            background: #ffffff;
            border-radius: 26px;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.4);
            margin-bottom: 1.5rem;
        }

        .reward-box {
            background: linear-gradient(135deg, var(--card-bg) 0%, var(--card-accent) 100%);
            color: var(--card-text);
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 18px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.35rem;
        }

        .reward-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--card-text);
            opacity: 0.88;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }

        .reward-value {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--card-text);
        }

        .steps-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.65rem;
            margin-bottom: 1.35rem;
        }

        .step-pill {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 0.7rem 0.5rem;
            font-size: 0.76rem;
            font-weight: 700;
            color: #e2e8f0;
        }

        .poster-footer {
            padding-top: 1rem;
            border-top: 1px dashed rgba(148, 163, 184, 0.24);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .join-url {
            font-size: 0.78rem;
            color: #94a3b8;
            word-break: break-all;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .toolbar {
                display: none !important;
            }
            .poster-sheet {
                box-shadow: none !important;
                margin: 0 auto !important;
            }
        }
    </style>
</head>
<body>
<div class="toolbar">
    <button type="button" class="toolbar-btn" onclick="window.print()">
        <span>🖨️</span>
        <span>{{ $isAr ? 'طباعة / حفظ كملف PDF' : 'Print / Save as PDF' }}</span>
    </button>
</div>

<div class="poster-sheet">
    <div class="poster-top">
        <div class="brand-row">
            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 18px; width: auto; display: block;">
            <span>ThreeDigits Loyalty</span>
        </div>

        @if($shop->logo_url)
            <div class="shop-logo-frame">
                <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}">
            </div>
        @endif

        <h1 class="shop-name">{{ $shop->name }}</h1>
        <p class="shop-tagline">
            {{ $isAr ? 'امسح رمز QR للانضمام لبرنامج الولاء الرقمي' : 'Scan the QR Code to Join Our Digital Loyalty Club' }}
        </p>
    </div>

    <div class="poster-content">
        <div class="qr-card">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(260)->margin(1)->generate($joinUrl) !!}
        </div>

        @if($campaign)
            <div class="reward-box">
                <div class="reward-label">{{ $campaign->name }}</div>
                <div class="reward-value">
                    🎁 {{ $campaign->reward_name }} ({{ $campaign->required_purchases_count }} {{ $isAr ? 'أختام' : 'Stamps' }})
                </div>
            </div>
        @endif

        <div class="steps-strip">
            <div class="step-pill">1. {{ $isAr ? 'امسح الرمز' : 'Scan QR Code' }}</div>
            <div class="step-pill">2. {{ $isAr ? 'سجّل في 15 ثانية' : '15s Sign-Up' }}</div>
            <div class="step-pill">3. {{ $isAr ? 'أضف للمحفظة' : 'Save to Phone' }}</div>
        </div>

        <div class="poster-footer">
            <div style="display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.74rem; font-weight: 700; color: #cbd5e1;">
                <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 18px; width: auto; display: block;">
                <span>ThreeDigits Loyalty</span>
            </div>
            <div class="join-url">{{ $joinUrl }}</div>
            @if($shop->logo_url)
                <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" style="height: 22px; width: 22px; border-radius: 6px; object-fit: cover; background: #ffffff;">
            @endif
        </div>
    </div>
</div>

@if(request()->boolean('print'))
<script>
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 250);
    });
</script>
@endif
</body>
</html>

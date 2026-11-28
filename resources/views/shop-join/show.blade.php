@php
    $isAr = app()->getLocale() === 'ar';
    $cardBg = $shop->resolvedCardBgColor();
    $cardAccent = $shop->resolvedCardAccentColor();
    $cardText = $shop->resolvedCardTextColor();
    $category = $shop->resolvedCategory();
    $categorySubtitle = $shop->categoryPosterSubtitle($isAr ? 'ar' : 'en');
    $bgRgb = \App\Models\Shop::hexToRgbString($cardBg);
    $accentRgb = \App\Models\Shop::hexToRgbString($cardAccent);
    $deepBgHex = \App\Models\Shop::darkenedHex($cardBg, 0.24);
    $surfaceHex = \App\Models\Shop::darkenedHex($cardBg, 0.36);
    $requiredStamps = max(1, (int) ($campaign->required_purchases_count ?? 8));
    $rewardName = $campaign->reward_name ?? ($isAr ? 'مكافأة مجانية' : 'Free Signature Reward');
    $campaignName = $campaign->name ?? ($isAr ? 'نادي الولاء VIP' : 'VIP Loyalty Club');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="{{ $cardBg }}">
    <link rel="icon" type="image/png" href="{{ $shop->logo_url ?: asset('images/logo-icon.png') }}">
    <title>{{ $shop->name }} — {{ $isAr ? 'انضم لبرنامج الولاء' : 'Join Loyalty Club' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-deep: {{ $deepBgHex }};
            --surface: {{ $surfaceHex }};
            --primary: {{ $cardBg }};
            --primary-deep: {{ $cardAccent }};
            --card-text: {{ $cardText }};
            --border-subtle: rgba({{ $accentRgb }}, 0.28);
            --border-accent: rgba({{ $accentRgb }}, 0.52);
            --emerald: #10b981;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
        }

        *, *::before, *::after, body, input, button, select, textarea {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', 'Cairo', sans-serif !important;
        }

        body {
            background:
                radial-gradient(circle at 14% 18%, rgba({{ $bgRgb }}, 0.55), transparent 48%),
                radial-gradient(circle at 88% 82%, rgba({{ $accentRgb }}, 0.38), transparent 48%),
                var(--bg-deep);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.25rem 1rem 2rem;
            -webkit-font-smoothing: antialiased;
        }

        .page-wrap {
            width: 100%;
            max-width: 1040px;
            margin: 0 auto;
        }

        /* Shop-Branded Header */
        .nav-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
            padding: 0.5rem 0 1.75rem;
        }

        .shop-brand-header {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }

        .shop-header-crest {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            background: linear-gradient(135deg, var(--primary), var(--primary-deep));
            color: var(--card-text);
            border: 1.5px solid rgba(255, 255, 255, 0.28);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 7px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .shop-header-crest.has-logo {
            background: #ffffff;
            padding: 2px;
        }

        .shop-header-crest img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            display: block;
        }

        .shop-header-title {
            font-weight: 800;
            font-size: 1.05rem;
            line-height: 1.15;
            color: #ffffff;
        }

        .shop-header-cat {
            display: block;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.76);
            margin-top: 0.15rem;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .pill-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-main);
            padding: 0.45rem 0.95rem;
            border-radius: 999px;
            font-family: inherit;
            font-size: 0.76rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pill-btn:hover {
            background: rgba(255, 255, 255, 0.16);
            border-color: rgba(255, 255, 255, 0.35);
        }

        /* Split Layout */
        .split-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            align-items: center;
        }

        @media (min-width: 880px) {
            .split-grid {
                grid-template-columns: 1.05fr 0.95fr;
                gap: 3rem;
            }
        }

        /* Form Card Tinted to Shop Brand */
        .form-card {
            background: linear-gradient(165deg, rgba(255, 255, 255, 0.06) 0%, rgba(0, 0, 0, 0.36) 100%), var(--surface);
            border: 1px solid rgba({{ $accentRgb }}, 0.38);
            border-radius: 28px;
            padding: 2.25rem 2rem;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.65);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(14px);
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 10%;
            right: 10%;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--primary), var(--primary-deep), transparent);
        }

        .step-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.36rem 0.9rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.09);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #f8fafc;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.1rem;
        }

        .step-dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: var(--primary-deep);
            box-shadow: 0 0 10px var(--primary-deep);
        }

        .hero-heading {
            font-size: clamp(1.55rem, 2.8vw, 2.1rem);
            font-weight: 800;
            line-height: 1.22;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
            color: #ffffff;
        }

        .hero-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 1.65rem;
        }

        .input-group {
            margin-bottom: 1.1rem;
        }

        .input-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 0.45rem;
            letter-spacing: 0.02em;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            inset-inline-start: 1rem;
            color: #94a3b8;
            font-size: 0.95rem;
            pointer-events: none;
        }

        .text-field {
            width: 100%;
            background: rgba(0, 0, 0, 0.26);
            border: 1.5px solid rgba(255, 255, 255, 0.16);
            border-radius: 16px;
            padding: 0.9rem 1rem 0.9rem 2.75rem;
            color: #ffffff;
            font-family: inherit;
            font-size: 0.92rem;
            font-weight: 500;
            outline: none;
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        [dir="rtl"] .text-field {
            padding: 0.9rem 2.75rem 0.9rem 1rem;
        }

        .text-field::placeholder {
            color: rgba(203, 213, 225, 0.52);
        }

        .text-field:focus {
            background: rgba(0, 0, 0, 0.36);
            border-color: var(--primary-deep);
            box-shadow: 0 0 0 4px rgba({{ $accentRgb }}, 0.25);
        }

        .error-msg {
            color: #fca5a5;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.35rem;
        }

        .phone-combo {
            display: flex;
            align-items: stretch;
            direction: ltr;
            background: rgba(0, 0, 0, 0.26);
            border: 1.5px solid rgba(255, 255, 255, 0.16);
            border-radius: 16px;
            overflow: hidden;
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .phone-combo:focus-within {
            background: rgba(0, 0, 0, 0.36);
            border-color: var(--primary-deep);
            box-shadow: 0 0 0 4px rgba({{ $accentRgb }}, 0.25);
        }

        .country-select {
            background: rgba(255, 255, 255, 0.07);
            border: none;
            border-right: 1px solid rgba(255, 255, 255, 0.14);
            color: #ffffff;
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 700;
            padding: 0.9rem 0.65rem 0.9rem 0.85rem;
            outline: none;
            cursor: pointer;
            flex-shrink: 0;
        }

        .country-select option {
            background: #0f172a;
            color: #ffffff;
        }

        .phone-digits-input {
            flex: 1;
            min-width: 0;
            background: transparent;
            border: none;
            color: #ffffff;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.9rem 1rem;
            outline: none;
            letter-spacing: 0.02em;
        }

        .phone-digits-input::placeholder {
            color: rgba(203, 213, 225, 0.52);
        }

        .cta-btn {
            width: 100%;
            margin-top: 0.5rem;
            padding: 1rem 1.4rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 16px;
            background: linear-gradient(135deg, {{ $cardBg }} 0%, {{ $cardAccent }} 100%);
            color: {{ $cardText }};
            font-family: inherit;
            font-size: 0.98rem;
            font-weight: 800;
            letter-spacing: 0.01em;
            cursor: pointer;
            box-shadow: 0 14px 30px -8px rgba({{ $accentRgb }}, 0.55);
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .cta-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.06);
            box-shadow: 0 18px 36px -8px rgba({{ $accentRgb }}, 0.72);
        }

        .trust-strip {
            margin-top: 1.25rem;
            padding-top: 1.1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.65rem;
        }

        /* Live Pass Preview */
        .preview-col {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .pass-preview {
            width: 100%;
            max-width: 390px;
            background: #ffffff;
            color: #0f172a;
            border-radius: 26px;
            overflow: hidden;
            box-shadow: 0 28px 60px -12px rgba(0, 0, 0, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .pass-top {
            background: linear-gradient(135deg, {{ $cardBg }} 0%, {{ $cardAccent }} 100%);
            color: {{ $cardText }};
            padding: 1.4rem 1.4rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.85rem;
        }

        .pass-shop {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            min-width: 0;
        }

        .pass-avatar {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.18);
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            color: {{ $cardText }};
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .pass-avatar.has-logo {
            background: #ffffff;
            padding: 2px;
        }

        .pass-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 11px;
        }

        .pass-shop-name {
            font-size: 0.98rem;
            font-weight: 800;
            line-height: 1.2;
            word-break: break-word;
        }

        .pass-member-live {
            font-size: 0.78rem;
            opacity: 0.86;
            margin-top: 0.15rem;
        }

        .pass-tag {
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.32);
            color: {{ $cardText }};
            padding: 0.3rem 0.7rem;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .pass-inner {
            padding: 1.35rem 1.4rem;
        }

        .reward-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1rem;
        }

        .reward-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.3rem;
        }

        .reward-title {
            font-size: 0.88rem;
            font-weight: 800;
            color: #0f172a;
        }

        .reward-count {
            font-size: 0.82rem;
            font-weight: 800;
            color: {{ $cardBg }};
        }

        .reward-prize-line {
            font-size: 0.8rem;
            color: #475569;
            margin-bottom: 0.85rem;
        }

        .stamps-preview-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.45rem;
        }

        .stamp-circle {
            aspect-ratio: 1;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            font-weight: 800;
        }

        .stamp-circle.welcome-bonus {
            background: linear-gradient(135deg, {{ $cardBg }}, {{ $cardAccent }});
            color: {{ $cardText }};
            box-shadow: 0 4px 12px rgba({{ $bgRgb }}, 0.32);
        }

        .stamp-circle.slot {
            background: #ffffff;
            border: 1.5px dashed #cbd5e1;
            color: #94a3b8;
        }

        .perforation {
            position: relative;
            border-top: 2px dashed #e2e8f0;
            margin: 1.2rem -1.4rem;
        }

        .perforation::before,
        .perforation::after {
            content: '';
            position: absolute;
            top: -10px;
            width: 18px;
            height: 18px;
            border-radius: 999px;
            background: var(--bg-deep);
        }

        .perforation::before { left: -9px; }
        .perforation::after { right: -9px; }

        .pass-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .pass-bottom-text {
            font-size: 0.76rem;
            color: #64748b;
            line-height: 1.5;
        }

        .mini-qr {
            padding: 0.45rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            flex-shrink: 0;
        }

        .page-powered-footer {
            margin-top: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.78rem;
            font-weight: 700;
            color: rgba(248, 250, 252, 0.72);
            text-decoration: none;
            direction: ltr;
        }

        /* Owner QR Modal */
        .qr-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(6, 7, 11, 0.82);
            backdrop-filter: blur(8px);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .qr-modal-backdrop.open {
            display: flex;
        }

        .qr-modal-box {
            background: var(--surface);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 24px;
            padding: 2rem;
            max-width: 390px;
            width: 100%;
            text-align: center;
        }

        .qr-white-box {
            display: inline-flex;
            padding: 1rem;
            background: #ffffff;
            border-radius: 18px;
            margin: 1.1rem 0;
        }
    </style>
</head>
<body>

<div class="page-wrap">
    {{-- Shop-Branded Navigation Header --}}
    <header class="nav-bar">
        <div class="shop-brand-header">
            <div class="shop-header-crest {{ $shop->logo_url ? 'has-logo' : '' }}">
                @if($shop->logo_url)
                    <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}">
                @else
                    <x-poster.category-art :category="$category" mode="icon" />
                @endif
            </div>
            <div>
                <div class="shop-header-title">{{ $shop->name }}</div>
                <span class="shop-header-cat">{{ $categorySubtitle }}</span>
            </div>
        </div>

        <div class="nav-actions">
            <button type="button" class="pill-btn" onclick="toggleQrModal()">
                {{ $isAr ? 'رمز QR للمتجر' : 'Store QR Poster' }}
            </button>
            <a href="{{ route('lang.switch', $isAr ? 'en' : 'ar') }}" class="pill-btn">
                {{ $isAr ? 'English' : 'عربي' }}
            </a>
        </div>
    </header>

    {{-- Main Split Content --}}
    <main class="split-grid">
        {{-- Sign-Up Form Tinted to Shop Brand --}}
        <div class="form-card">
            <div class="step-badge">
                <span class="step-dot"></span>
                <span>{{ $isAr ? 'تسجيل سريع في 15 ثانية' : '15-Second Instant Pass' }}</span>
            </div>

            <h1 class="hero-heading">
                {{ $isAr ? 'انضم إلى برنامج ولاء ' . $shop->name : 'Join ' . $shop->name . ' Rewards' }}
            </h1>
            <p class="hero-desc">
                {{ $isAr
                    ? 'أدخل بياناتك البسيطة بالأسفل لإصدار بطاقتك الرقمية فوراً وحفظها على هاتفك.'
                    : 'Enter your basic details below to unlock your digital stamp card and save it to your phone.' }}
            </p>

            <form method="POST" action="{{ route('shop.join.store', $shop->slug ?: $shop->id) }}">
                @csrf

                <div class="input-group">
                    <label class="input-label" for="name">{{ $isAr ? 'الاسم الكامل' : 'Full Name' }}</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            class="text-field"
                            value="{{ old('name') }}"
                            required
                            autocomplete="name"
                            placeholder="{{ $isAr ? 'مثال: أحمد القحطاني' : 'e.g. Ahmed Al-Qahtani' }}"
                            oninput="updateLivePass()"
                        >
                    </div>
                    @error('name') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="input-group">
                    <label class="input-label" for="email">{{ $isAr ? 'البريد الإلكتروني (اختياري — لإرسال رابط البطاقة)' : 'Email Address (optional — for your pass link)' }}</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✉️</span>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            dir="ltr"
                            class="text-field"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            placeholder="name@example.com"
                        >
                    </div>
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="input-group">
                    <label class="input-label" for="phone">{{ $isAr ? 'رقم الجوال' : 'Phone Number' }}</label>
                    @php
                        $countries = [
                            ['code' => '+963', 'label' => '🇸🇾 +963'],
                            ['code' => '+90',  'label' => '🇹🇷 +90'],
                            ['code' => '+961', 'label' => '🇱🇧 +961'],
                            ['code' => '+962', 'label' => '🇯🇴 +962'],
                            ['code' => '+966', 'label' => '🇸🇦 +966'],
                            ['code' => '+971', 'label' => '🇦🇪 +971'],
                            ['code' => '+964', 'label' => '🇮🇶 +964'],
                            ['code' => '+20',  'label' => '🇪🇬 +20'],
                            ['code' => '+965', 'label' => '🇰🇼 +965'],
                            ['code' => '+974', 'label' => '🇶🇦 +974'],
                            ['code' => '+968', 'label' => '🇴🇲 +968'],
                            ['code' => '+973', 'label' => '🇧🇭 +973'],
                            ['code' => '+49',  'label' => '🇩🇪 +49'],
                            ['code' => '+44',  'label' => '🇬🇧 +44'],
                            ['code' => '+1',   'label' => '🇺🇸 +1'],
                        ];
                        $selectedCode = old('country_code', '+963');
                    @endphp
                    <div class="phone-combo">
                        <select id="country_code" name="country_code" class="country-select" aria-label="{{ $isAr ? 'رمز الدولة' : 'Country code' }}" onchange="updateLivePass()">
                            @foreach($countries as $country)
                                <option value="{{ $country['code'] }}" {{ $selectedCode === $country['code'] ? 'selected' : '' }}>
                                    {{ $country['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            inputmode="numeric"
                            pattern="[0-9]{7,15}"
                            class="phone-digits-input"
                            value="{{ old('phone') }}"
                            required
                            autocomplete="tel-national"
                            placeholder="9XX XXX XXX"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); updateLivePass();"
                        >
                    </div>
                    <div style="font-size: 0.74rem; color: rgba(203, 213, 225, 0.75); margin-top: 0.35rem;">
                        {{ $isAr ? 'أدخل أرقام الجوال فقط بدون صفر في البداية (الافتراضي سوريا 963+).' : 'Enter digits only without a leading zero (Syria +963 selected by default).' }}
                    </div>
                    @error('phone') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <p style="font-size: 0.78rem; color: rgba(203, 213, 225, 0.8); line-height: 1.6; margin: 0.25rem 0 0.85rem;">
                    @if($isAr)
                        قبل إصدار بطاقتك، يمكنك مراجعة
                        <a href="{{ route('legal.privacy.ar') }}" target="_blank" rel="noopener noreferrer" style="color: #ffffff; font-weight: 700; text-decoration: underline;">سياسة الخصوصية</a>
                        و<a href="{{ route('legal.terms.ar') }}" target="_blank" rel="noopener noreferrer" style="color: #ffffff; font-weight: 700; text-decoration: underline;">شروط الاستخدام</a>.
                    @else
                        Before creating your pass, review our
                        <a href="{{ route('legal.privacy.en') }}" target="_blank" rel="noopener noreferrer" style="color: #ffffff; font-weight: 700; text-decoration: underline;">Privacy Policy</a>
                        and
                        <a href="{{ route('legal.terms.en') }}" target="_blank" rel="noopener noreferrer" style="color: #ffffff; font-weight: 700; text-decoration: underline;">Terms of Service</a>.
                    @endif
                </p>

                <button type="submit" class="cta-btn">
                    <span>{{ $isAr ? 'إصدار بطاقتي الآن' : 'Create My Digital Pass' }}</span>
                    <span>{{ $isAr ? '←' : '→' }}</span>
                </button>
            </form>

            <div class="trust-strip">
                <x-poster.wallet-badges :compact="true" />
                <div style="font-size: 0.74rem; color: rgba(226, 232, 240, 0.78); font-weight: 600;">
                    ⚡ {{ $isAr ? 'تفتح فوراً في المتصفح بدون تنزيل تطبيق' : 'Opens right in your browser — no app download needed' }}
                </div>
            </div>
        </div>

        {{-- Live Interactive Passbook Preview --}}
        <div class="preview-col">
            <div class="pass-preview">
                <div class="pass-top">
                    <div class="pass-shop">
                        <div class="pass-avatar {{ $shop->logo_url ? 'has-logo' : '' }}">
                            @if($shop->logo_url)
                                <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}">
                            @else
                                <x-poster.category-art :category="$category" mode="icon" />
                            @endif
                        </div>
                        <div style="min-width: 0;">
                            <div class="pass-shop-name" dir="auto">{{ $shop->name }}</div>
                            <div class="pass-member-live" id="live-member-line">
                                {{ $isAr ? 'اسم العضو يظهر هنا' : 'Your Name Appears Here' }}
                            </div>
                        </div>
                    </div>
                    <span class="pass-tag">VIP PASS</span>
                </div>

                <div class="pass-inner">
                    <div class="reward-card">
                        <div class="reward-head">
                            <span class="reward-title">{{ $campaignName }}</span>
                            <span class="reward-count">0 / {{ $requiredStamps }}</span>
                        </div>
                        <div class="reward-prize-line">
                            🎁 {{ $isAr ? 'المكافأة:' : 'Reward:' }} <strong>{{ $rewardName }}</strong>
                        </div>

                        <div class="stamps-preview-grid">
                            @for($i = 1; $i <= min($requiredStamps, 8); $i++)
                                <div class="stamp-circle {{ $i === 1 ? 'welcome-bonus' : 'slot' }}">
                                    {{ $i === 1 ? '★' : $i }}
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="perforation"></div>

                    <div class="pass-bottom">
                        <div class="pass-bottom-text">
                            <strong style="color: #0f172a; display: block; margin-bottom: 0.2rem;">
                                {{ $isAr ? 'معاينة حية لبطاقتك' : 'Live Pass Preview' }}
                            </strong>
                            {{ $isAr
                                ? 'فور التسجيل، ستحصل على رمز QR خاص بك ويمكنك إضافتها إلى محفظة هاتفك.'
                                : 'Once registered, you get your personal QR code and one-tap wallet save.' }}
                        </div>
                        <div class="mini-qr">
                            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(68)->margin(0)->generate($joinUrl) !!}
                        </div>
                    </div>

                    <div style="margin-top: 0.9rem; padding-top: 0.75rem; border-top: 1px dashed #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                        <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; color: #64748b; font-weight: 700;" dir="ltr">
                            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 18px; width: auto; display: block;">
                            <span>Powered by ThreeDigits Loyalty</span>
                        </div>
                        @if($shop->logo_url)
                            <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" style="height: 20px; width: 20px; border-radius: 5px; object-fit: cover; border: 1px solid #e2e8f0;">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <a href="{{ route($isAr ? 'home.ar' : 'home.en') }}" class="page-powered-footer">
        <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 22px; width: auto; display: block;">
        <span>Powered by ThreeDigits Loyalty</span>
    </a>
</div>

{{-- Store QR Poster Modal --}}
<div id="qr-modal" class="qr-modal-backdrop" onclick="if(event.target === this) toggleQrModal()">
    <div class="qr-modal-box">
        @if($shop->logo_url)
            <div style="width: 56px; height: 56px; margin: 0 auto 0.65rem; border-radius: 16px; background: #ffffff; padding: 3px; overflow: hidden; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);">
                <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px; display: block;">
            </div>
        @else
            <div style="width: 52px; height: 52px; margin: 0 auto 0.65rem; border-radius: 15px; background: linear-gradient(135deg, {{ $cardBg }}, {{ $cardAccent }}); color: {{ $cardText }}; padding: 8px;">
                <x-poster.category-art :category="$category" mode="icon" />
            </div>
        @endif

        <div style="font-size: 0.9rem; font-weight: 800; color: #ffffff;">
            {{ $shop->name }}
        </div>
        <h3 style="font-size: 1.15rem; font-weight: 800; margin-top: 0.25rem; color: #f8fafc;">
            {{ $isAr ? 'امسح الرمز للانضمام لبرنامج الولاء' : 'Scan to Join Our Loyalty Club' }}
        </h3>
        <div class="qr-white-box">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->margin(1)->generate($joinUrl) !!}
        </div>
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.5rem; margin-top: 0.25rem;">
            <a href="{{ route('shop.join.poster', ['shop' => $shop->slug ?: $shop->id, 'print' => 1]) }}" target="_blank" class="pill-btn" style="background: linear-gradient(135deg, {{ $cardBg }}, {{ $cardAccent }}); color: {{ $cardText }}; border: none;">
                🖨️ {{ $isAr ? 'فتح البوستر للطباعة' : 'Open Printable Poster' }}
            </a>
            <button type="button" class="pill-btn" onclick="toggleQrModal()">
                {{ $isAr ? 'إغلاق' : 'Close' }}
            </button>
        </div>
    </div>
</div>

<script>
    function updateLivePass() {
        const nameInput = document.getElementById('name');
        const phoneInput = document.getElementById('phone');
        const codeSelect = document.getElementById('country_code');
        const target = document.getElementById('live-member-line');
        if (!target) return;

        const nameVal = nameInput ? nameInput.value.trim() : '';
        const digitsVal = phoneInput ? phoneInput.value.replace(/^0+/, '').trim() : '';
        const codeVal = codeSelect ? codeSelect.value : '+963';
        const phoneVal = digitsVal ? (codeVal + ' ' + digitsVal) : '';

        if (nameVal && phoneVal) {
            target.textContent = nameVal + ' · ' + phoneVal;
        } else if (nameVal) {
            target.textContent = nameVal;
        } else if (phoneVal) {
            target.textContent = phoneVal;
        } else {
            target.textContent = '{{ $isAr ? "اسم العضو يظهر هنا" : "Your Name Appears Here" }}';
        }
    }

    function toggleQrModal() {
        const modal = document.getElementById('qr-modal');
        if (modal) modal.classList.toggle('open');
    }
</script>
</body>
</html>

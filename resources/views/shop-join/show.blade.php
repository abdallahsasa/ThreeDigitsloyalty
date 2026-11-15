<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <title>{{ $shop->name }} — {{ app()->getLocale() === 'ar' ? 'انضم لبرنامج الولاء' : 'Join Loyalty Club' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-deep: #010A29;
            --surface: #071033;
            --surface-elevated: #0C1742;
            --border-subtle: rgba(116, 62, 226, 0.22);
            --border-accent: rgba(116, 62, 226, 0.5);
            --primary: #743EE2;
            --primary-mid: #6145E8;
            --primary-deep: #3B42D5;
            --primary-glow: #9F7AEA;
            --gold: #724CDA;
            --emerald: #10b981;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        *, *::before, *::after, body, input, button, select, textarea {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', 'Cairo', sans-serif !important;
        }

        body {
            background:
                radial-gradient(circle at 12% 18%, rgba(116, 62, 226, 0.32), transparent 44%),
                radial-gradient(circle at 88% 82%, rgba(59, 66, 213, 0.28), transparent 44%),
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

        /* Header */
        .nav-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0 1.75rem;
        }

        .brand-link {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-link img {
            height: 32px;
            width: auto;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1rem;
            letter-spacing: -0.02em;
        }

        .brand-sub {
            display: block;
            font-size: 0.58rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            color: var(--primary-glow);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .pill-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-subtle);
            color: var(--text-main);
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            font-family: inherit;
            font-size: 0.76rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pill-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
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

        /* Left Column: Form Card */
        .form-card {
            background: linear-gradient(165deg, rgba(12, 23, 66, 0.94) 0%, rgba(5, 12, 42, 0.98) 100%);
            border: 1px solid rgba(116, 62, 226, 0.32);
            border-radius: 28px;
            padding: 2.25rem 2rem;
            box-shadow: 0 30px 60px -15px rgba(1, 10, 41, 0.85);
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 15%;
            right: 15%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #743EE2, #6145E8, #3B42D5, transparent);
        }

        .step-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            background: rgba(99, 102, 241, 0.12);
            border: 1px solid rgba(129, 140, 248, 0.28);
            color: var(--primary-glow);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 1.15rem;
        }

        .step-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: var(--emerald);
            box-shadow: 0 0 8px var(--emerald);
        }

        .hero-heading {
            font-size: clamp(1.55rem, 2.8vw, 2.1rem);
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
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
            color: #cbd5e1;
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
            color: #64748b;
            font-size: 0.95rem;
            pointer-events: none;
        }

        .text-field {
            width: 100%;
            background: rgba(255, 255, 255, 0.035);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
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
            color: #475569;
        }

        .text-field:focus {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--primary-glow);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .error-msg {
            color: #f87171;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.35rem;
        }

        .cta-btn {
            width: 100%;
            margin-top: 0.5rem;
            padding: 1rem 1.4rem;
            border: none;
            border-radius: 16px;
            background: linear-gradient(135deg, #743EE2 0%, #6145E8 50%, #3B42D5 100%);
            color: #ffffff;
            font-family: inherit;
            font-size: 0.96rem;
            font-weight: 800;
            letter-spacing: 0.01em;
            cursor: pointer;
            box-shadow: 0 14px 30px -8px rgba(116, 62, 226, 0.6);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 36px -8px rgba(116, 62, 226, 0.78);
        }

        .trust-strip {
            margin-top: 1.25rem;
            padding-top: 1.1rem;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.25rem;
            flex-wrap: wrap;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* Right Column: Live Pass Preview */
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
            border: 1px solid rgba(255, 255, 255, 0.16);
        }

        .pass-top {
            background: linear-gradient(135deg, #743EE2 0%, #6145E8 52%, #3B42D5 100%);
            color: #ffffff;
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
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.18);
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.15rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .pass-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pass-shop-name {
            font-size: 1.05rem;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pass-member-live {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.82);
            margin-top: 0.15rem;
        }

        .pass-tag {
            background: rgba(245, 208, 97, 0.2);
            border: 1px solid rgba(245, 208, 97, 0.45);
            color: #fde68a;
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
            font-size: 0.8rem;
            font-weight: 800;
            color: #4f46e5;
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
            background: linear-gradient(135deg, #743EE2, #3B42D5);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(116, 62, 226, 0.32);
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
            background: var(--surface-elevated);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 24px;
            padding: 2rem;
            max-width: 380px;
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
@php
    $isAr = app()->getLocale() === 'ar';
    $requiredStamps = max(1, (int) ($campaign->required_purchases_count ?? 8));
    $rewardName = $campaign->reward_name ?? ($isAr ? 'مكافأة مجانية' : 'Free Signature Reward');
    $campaignName = $campaign->name ?? ($isAr ? 'نادي الولاء VIP' : 'VIP Loyalty Club');
@endphp

<div class="page-wrap">
    {{-- Top Navigation --}}
    <header class="nav-bar">
        <a href="/" class="brand-link">
            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty">
            <div>
                <span class="brand-title">ThreeDigits</span>
                <span class="brand-sub">LOYALTY</span>
            </div>
        </a>

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
        {{-- Left: Minimal 3-Field Sign-Up Form --}}
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
                    ? 'أدخل بياناتك البسيطة بالأسفل لإصدار بطاقتك الرقمية فوراً وإضافتها إلى iPhone أو Android.'
                    : 'Enter your basic details below to unlock your digital pass and save it straight to your iPhone or Android.' }}
            </p>

            <form method="POST" action="{{ route('shop.join.store', $shop->id) }}">
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
                    <label class="input-label" for="email">{{ $isAr ? 'البريد الإلكتروني (لإرسال البطاقة)' : 'Email Address (for your pass link)' }}</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✉️</span>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="text-field"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            placeholder="name@example.com"
                        >
                    </div>
                    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="input-group">
                    <label class="input-label" for="phone">{{ $isAr ? 'رقم الجوال' : 'Phone Number' }}</label>
                    <div class="input-wrapper">
                        <span class="input-icon">📱</span>
                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            class="text-field"
                            value="{{ old('phone') }}"
                            required
                            autocomplete="tel"
                            placeholder="+966 5X XXX XXXX"
                            oninput="updateLivePass()"
                        >
                    </div>
                    @error('phone') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="cta-btn">
                    <span>{{ $isAr ? 'إصدار بطاقتي الآن' : 'Create My Digital Pass' }}</span>
                    <span>{{ $isAr ? '←' : '→' }}</span>
                </button>
            </form>

            <div class="trust-strip">
                <span> {{ $isAr ? 'يدعم iPhone' : 'iPhone Pass' }}</span>
                <span>🤖 {{ $isAr ? 'يدعم Android' : 'Android Pass' }}</span>
                <span>⚡ {{ $isAr ? 'بدون تحميل تطبيق' : 'No App Download' }}</span>
            </div>
        </div>

        {{-- Right: Live Interactive Passbook Preview --}}
        <div class="preview-col">
            <div class="pass-preview">
                <div class="pass-top">
                    <div class="pass-shop">
                        <div class="pass-avatar">
                            @if($shop->logo_url)
                                <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}">
                            @else
                                {{ mb_substr($shop->name, 0, 1) }}
                            @endif
                        </div>
                        <div style="min-width: 0;">
                            <div class="pass-shop-name">{{ $shop->name }}</div>
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
                                ? 'فور التسجيل، ستحصل على رمز QR خاص بك وتصلك رسالة ترحيبية على بريدك.'
                                : 'Once registered, you get your personal QR code and an instant welcome email.' }}
                        </div>
                        <div class="mini-qr">
                            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(68)->margin(0)->generate($joinUrl) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Store QR Poster Modal (For Shop Owner Display) --}}
<div id="qr-modal" class="qr-modal-backdrop" onclick="if(event.target === this) toggleQrModal()">
    <div class="qr-modal-box">
        <div style="font-size: 0.75rem; font-weight: 800; color: var(--gold); text-transform: uppercase; letter-spacing: 0.08em;">
            {{ $shop->name }}
        </div>
        <h3 style="font-size: 1.2rem; font-weight: 800; margin-top: 0.35rem;">
            {{ $isAr ? 'امسح الرمز للانضمام لبرنامج الولاء' : 'Scan to Join Our Loyalty Club' }}
        </h3>
        <div class="qr-white-box">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->margin(1)->generate($joinUrl) !!}
        </div>
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem;">
            {{ $joinUrl }}
        </p>
        <button type="button" class="pill-btn" onclick="toggleQrModal()">
            {{ $isAr ? 'إغلاق' : 'Close' }}
        </button>
    </div>
</div>

<script>
    function updateLivePass() {
        const nameInput = document.getElementById('name');
        const phoneInput = document.getElementById('phone');
        const target = document.getElementById('live-member-line');
        const fallback = @json($isAr ? 'اسم العضو يظهر هنا' : 'Your Name Appears Here');

        const nameVal = nameInput.value.trim();
        const phoneVal = phoneInput.value.trim();

        if (nameVal && phoneVal) {
            target.textContent = nameVal + ' · ' + phoneVal;
        } else if (nameVal) {
            target.textContent = nameVal;
        } else if (phoneVal) {
            target.textContent = phoneVal;
        } else {
            target.textContent = fallback;
        }
    }

    function toggleQrModal() {
        document.getElementById('qr-modal').classList.toggle('open');
    }
</script>
</body>
</html>

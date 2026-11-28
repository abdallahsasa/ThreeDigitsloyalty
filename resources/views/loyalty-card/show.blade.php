@php
    $cardBg = $card->shop->resolvedCardBgColor();
    $cardAccent = $card->shop->resolvedCardAccentColor();
    $cardText = $card->shop->resolvedCardTextColor();
    $category = $card->shop->resolvedCategory();
    $bgRgb = \App\Models\Shop::hexToRgbString($cardBg);
    $accentRgb = \App\Models\Shop::hexToRgbString($cardAccent);
    $deepBgHex = \App\Models\Shop::darkenedHex($cardBg, 0.24);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="{{ $cardBg }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $card->shop->name }}">
    <link rel="icon" type="image/png" href="{{ $card->shop->logo_url ?: asset('images/logo-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ $card->shop->logo_url ?: asset('images/logo-icon.png') }}">
    <link rel="manifest" href="{{ route('loyalty-card.manifest', $card->token) }}">
    <title>{{ $card->shop->name }} — {{ app()->getLocale() === 'ar' ? 'بطاقة الولاء' : 'Loyalty Pass' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-page: {{ $deepBgHex }};
            --surface-card: #ffffff;
            --surface-muted: #f8fafc;
            --text-primary: #010A29;
            --text-secondary: #64748b;
            --card-bg: {{ $cardBg }};
            --card-accent: {{ $cardAccent }};
            --card-text: {{ $cardText }};
            --accent: {{ $cardBg }};
            --accent-soft: rgba({{ $bgRgb }}, 0.08);
            --success: #10b981;
            --success-soft: #ecfdf5;
            --border: #e2e8f0;
        }

        *, *::before, *::after, body, button, input {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', 'Cairo', sans-serif !important;
        }

        body {
            background:
                radial-gradient(circle at 15% 15%, rgba({{ $bgRgb }}, 0.52), transparent 46%),
                radial-gradient(circle at 85% 85%, rgba({{ $accentRgb }}, 0.36), transparent 46%),
                var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
        }

        .pass-shell {
            width: 100%;
            max-width: 410px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            color: #cbd5e1;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            letter-spacing: 0.02em;
            opacity: 0.85;
        }

        .brand-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #6366f1;
            box-shadow: 0 0 10px #6366f1;
        }

        .lang-btn {
            background: rgba(255, 255, 255, 0.1);
            color: #f8fafc;
            border: 1px solid rgba(255, 255, 255, 0.16);
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 600;
            transition: background 0.2s ease;
        }

        .lang-btn:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        .pass-card {
            background: var(--surface-card);
            border-radius: 26px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .pass-header {
            background: linear-gradient(135deg, var(--card-bg) 0%, var(--card-accent) 100%);
            color: var(--card-text);
            padding: 1.5rem 1.5rem 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .shop-identity {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 0;
        }

        .shop-avatar {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            object-fit: cover;
            background: rgba(255, 255, 255, 0.18);
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .shop-name {
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .customer-sub {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 0.2rem;
        }

        .status-chip {
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .status-ready {
            background: #10b981;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.45);
        }

        .status-not_ready {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
        }

        .status-expired,
        .status-redeemed {
            background: rgba(15, 23, 42, 0.35);
            color: #e2e8f0;
        }

        .pass-body {
            padding: 1.5rem;
        }

        .reward-banner {
            background: var(--surface-muted);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem 1.1rem;
            margin-bottom: 1.25rem;
        }

        .reward-banner.is-ready {
            background: var(--success-soft);
            border-color: #a7f3d0;
        }

        .campaign-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 0.5rem;
            margin-bottom: 0.35rem;
        }

        .campaign-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stamp-counter {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--accent);
        }

        .reward-banner.is-ready .stamp-counter {
            color: #059669;
        }

        .reward-prize {
            font-size: 0.82rem;
            color: var(--text-secondary);
            margin-bottom: 0.85rem;
        }

        .stamp-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 0.5rem;
            margin-bottom: 0.9rem;
        }

        .stamp-slot {
            aspect-ratio: 1;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            transition: transform 0.2s ease;
        }

        .stamp-slot.filled {
            background: linear-gradient(135deg, var(--card-bg), var(--card-accent));
            color: var(--card-text);
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.22);
        }

        .reward-banner.is-ready .stamp-slot.filled {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.28);
        }

        .stamp-slot.empty {
            background: #ffffff;
            color: #cbd5e1;
            border: 1.5px dashed #cbd5e1;
        }

        .progress-track {
            width: 100%;
            height: 7px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--card-bg), var(--card-accent));
            border-radius: 999px;
            transition: width 0.4s ease;
        }

        .reward-banner.is-ready .progress-fill {
            background: linear-gradient(90deg, #10b981, #34d399);
        }

        .ready-callout {
            margin-top: 0.75rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: #047857;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .ticket-divider {
            position: relative;
            height: 1px;
            border-top: 2px dashed #e2e8f0;
            margin: 1.35rem -1.5rem;
        }

        .ticket-divider::before,
        .ticket-divider::after {
            content: '';
            position: absolute;
            top: -11px;
            width: 20px;
            height: 20px;
            border-radius: 999px;
            background: var(--bg-page);
        }

        .ticket-divider::before { left: -10px; }
        .ticket-divider::after { right: -10px; }

        .qr-section {
            text-align: center;
        }

        .qr-frame {
            display: inline-flex;
            padding: 1rem;
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
        }

        .qr-hint {
            margin-top: 0.85rem;
            font-size: 0.82rem;
            color: var(--text-secondary);
        }

        .actions-row {
            margin-top: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            justify-content: center;
        }

        .wallet-panel {
            margin-top: 1.15rem;
            padding: 1rem;
            border-radius: 18px;
            background: linear-gradient(145deg, rgba(116, 62, 226, 0.08), rgba(59, 66, 213, 0.05));
            border: 1.5px solid rgba(116, 62, 226, 0.25);
        }

        .wallet-panel-title {
            font-size: 0.84rem;
            font-weight: 800;
            color: #0f172a;
            text-align: center;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .wallet-stack {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.65rem;
        }

        .wallet-cta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.85rem 1.05rem;
            border-radius: 14px;
            text-decoration: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
            box-shadow: 0 8px 20px -6px rgba(15, 23, 42, 0.32);
        }

        .wallet-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.42);
        }

        .wallet-cta-apple {
            background: #000000;
            color: #ffffff;
            border: 1.5px solid #27272a;
        }

        .wallet-cta-google {
            background: #111827;
            color: #ffffff;
            border: 1.5px solid #374151;
        }

        .wallet-cta-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .wallet-cta-sub {
            font-size: 0.68rem;
            font-weight: 600;
            color: #cbd5e1;
            line-height: 1.1;
        }

        .wallet-cta-main {
            font-size: 0.96rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
        }

        .wallet-cta-arrow {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .copy-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--accent-soft);
            color: var(--accent);
            border: none;
            padding: 0.55rem 1rem;
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.15s ease;
        }

        .copy-btn:hover {
            background: #e0e7ff;
            transform: translateY(-1px);
        }

        .pass-footer {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>
@php
    $isAr = app()->getLocale() === 'ar';
    $requiredStamps = max(1, (int) ($card->campaign->required_purchases_count ?? 1));
    $progressPercent = min(100, ($card->current_stamps_count / $requiredStamps) * 100);
    $isReady = $card->reward_status === 'ready';
    $statusLabel = match ($card->reward_status) {
        'ready' => $isAr ? 'مكافأة جاهزة' : 'Reward Ready',
        'not_ready' => $isAr ? 'قيد التجميع' : 'Collecting',
        'redeemed' => $isAr ? 'تم الاستبدال' : 'Redeemed',
        'expired' => $isAr ? 'منتهية' : 'Expired',
        default => str_replace('_', ' ', $card->reward_status),
    };
@endphp

<div class="pass-shell">
    <div class="top-bar">
        <div class="brand-pill">
            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits" style="height: 22px; width: auto; display: block;">
            <span>ThreeDigits Pass</span>
        </div>
        <a href="{{ route('lang.switch', $isAr ? 'en' : 'ar') }}" class="lang-btn">
            {{ $isAr ? 'English' : 'عربي' }}
        </a>
    </div>

    @if(request()->boolean('joined'))
        <div style="margin-bottom: 0.9rem; padding: 0.85rem 1rem; border-radius: 16px; background: rgba(16, 185, 129, 0.16); border: 1px solid rgba(16, 185, 129, 0.4); color: #ecfdf5; font-size: 0.82rem; font-weight: 600; text-align: center;">
            🎉 {{ $isAr ? 'تم إنشاء بطاقتك وإرسال رسالة الترحيب إلى بريدك الإلكتروني! أضف البطاقة الآن إلى iPhone أو Android بالأسفل.' : 'Your pass is ready and a welcome email has been sent! Add it to your iPhone or Android below.' }}
        </div>
    @endif

    <div class="pass-card">
        <div class="pass-header">
            <div class="shop-identity">
                @if($card->shop?->logo_url)
                    <img src="{{ $card->shop->logo_url }}" alt="{{ $card->shop->name }}" class="shop-avatar" style="background: #ffffff; padding: 2px;">
                @else
                    <div class="shop-avatar" style="padding: 8px; color: {{ $cardText }};">
                        <x-poster.category-art :category="$category" mode="icon" />
                    </div>
                @endif
                <div style="min-width: 0;">
                    <div class="shop-name">{{ $card->shop?->name }}</div>
                    <div class="customer-sub">
                        {{ $card->customer?->name ?? ($isAr ? 'عميل المتجر' : 'Store Member') }}
                        @if($card->customer?->phone)
                            · <span dir="ltr" style="unicode-bidi: isolate;">{{ $card->customer->phone }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <span class="status-chip status-{{ $card->reward_status }}">{{ $statusLabel }}</span>
        </div>

        <div class="pass-body">
            <div class="reward-banner {{ $isReady ? 'is-ready' : '' }}">
                <div class="campaign-row">
                    <div class="campaign-title">{{ $card->campaign?->name ?? ($isAr ? 'بطاقة الأختام' : 'VIP Stamp Card') }}</div>
                    <div class="stamp-counter">{{ $card->current_stamps_count }} / {{ $requiredStamps }}</div>
                </div>

                @if($card->campaign?->reward_name)
                    <div class="reward-prize">
                        {{ $isAr ? 'المكافأة:' : 'Reward:' }} <strong>{{ $card->campaign->reward_name }}</strong>
                    </div>
                @endif

                <div class="stamp-grid">
                    @for($i = 1; $i <= min($requiredStamps, 20); $i++)
                        @php $filled = $i <= $card->current_stamps_count; @endphp
                        <div class="stamp-slot {{ $filled ? 'filled' : 'empty' }}">
                            {{ $filled ? '✓' : $i }}
                        </div>
                    @endfor
                </div>

                <div class="progress-track">
                    <div class="progress-fill" style="width: {{ $progressPercent }}%;"></div>
                </div>

                @if($isReady)
                    <div class="ready-callout">
                        <span>🎉</span>
                        <span>{{ $isAr ? 'مكافأتك جاهزة للاستخدام الآن!' : 'Your reward is unlocked and ready to redeem!' }}</span>
                    </div>
                @endif
            </div>

            {{-- Prominent Wallet CTA Panel right above QR --}}
            <div class="wallet-panel">
                <div class="wallet-panel-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $cardBg }}" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="2" y="5" width="20" height="14" rx="3"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                    <span>{{ $isAr ? 'احفظ بطاقتك الآن في محفظة هاتفك' : 'Save Your Card to Phone Wallet' }}</span>
                </div>

                <div class="wallet-stack">
                    <a href="{{ route('loyalty-card.apple-pass', $card->token) }}" class="wallet-cta wallet-cta-apple">
                        <div class="wallet-cta-left">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="flex-shrink: 0;">
                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.33c.64-.78 1.08-1.86.96-2.94-.93.04-2.06.62-2.72 1.4-.58.68-1.1 1.79-.96 2.84 1.04.08 2.09-.52 2.72-1.3z"/>
                            </svg>
                            <div style="text-align: start;">
                                <div class="wallet-cta-sub">{{ $isAr ? 'لمستخدمي iPhone' : 'For iPhone' }}</div>
                                <div class="wallet-cta-main">{{ $isAr ? 'أضف إلى Apple Wallet' : 'Add to Apple Wallet' }}</div>
                            </div>
                        </div>
                        <span class="wallet-cta-arrow" aria-hidden="true">{{ $isAr ? '←' : '→' }}</span>
                    </a>

                    <a href="{{ route('loyalty-card.google-pass', $card->token) }}" class="wallet-cta wallet-cta-google">
                        <div class="wallet-cta-left">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="flex-shrink: 0;">
                                <rect x="2" y="4" width="20" height="16" rx="4" fill="#1f2937" stroke="#60a5fa" stroke-width="1.5"/>
                                <path d="M2 9h20" stroke="#34d399" stroke-width="2"/>
                                <path d="M2 12h20" stroke="#fbbf24" stroke-width="2"/>
                                <path d="M2 15h20" stroke="#f87171" stroke-width="2"/>
                            </svg>
                            <div style="text-align: start;">
                                <div class="wallet-cta-sub">{{ $isAr ? 'لمستخدمي Android' : 'For Android' }}</div>
                                <div class="wallet-cta-main">{{ $isAr ? 'أضف إلى Google Wallet' : 'Add to Google Wallet' }}</div>
                            </div>
                        </div>
                        <span class="wallet-cta-arrow" aria-hidden="true">{{ $isAr ? '←' : '→' }}</span>
                    </a>
                </div>
            </div>

            <div class="ticket-divider"></div>

            <div class="qr-section">
                <div class="qr-frame">
                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(190)->margin(1)->generate($qrUrl) !!}
                </div>
                <div class="qr-hint">
                    {{ $isAr ? 'أظهر رمز QR للكاشير عند الدفع لإضافة ختم.' : 'Present this QR code to the cashier at checkout.' }}
                </div>

                <div class="actions-row">
                    <button type="button" class="copy-btn" onclick="copyCardUrl(this)">
                        <span>{{ $isAr ? 'نسخ رابط البطاقة' : 'Copy Pass Link' }}</span>
                    </button>
                </div>

                {{-- Dual Branding Strip Inside Card: ThreeDigits Logo + Shop Logo --}}
                <div style="margin-top: 1.25rem; padding-top: 0.9rem; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                    <div style="display: inline-flex; align-items: center; gap: 0.45rem; font-size: 0.75rem; font-weight: 700; color: #64748b;">
                        <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 20px; width: auto; display: block;">
                        <span>ThreeDigits Loyalty</span>
                    </div>
                    @if($card->shop->logo_url)
                        <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; font-weight: 700; color: #334155;">
                            <img src="{{ $card->shop->logo_url }}" alt="{{ $card->shop->name }}" style="height: 22px; width: 22px; border-radius: 6px; object-fit: cover; border: 1px solid #e2e8f0;">
                            <span>{{ $card->shop->name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="pass-footer">
        {{ $isAr ? 'اضغط على Apple Wallet أو Google Wallet لإضافة البطاقة مباشرة إلى محفظة هاتفك.' : 'Tap Apple Wallet or Google Wallet above to save this card directly to your phone wallet.' }}
    </div>
</div>

<script>
    function copyCardUrl(btn) {
        if (!navigator.clipboard) return;
        navigator.clipboard.writeText(window.location.href).then(function () {
            const label = btn.querySelector('span');
            const original = label.textContent;
            label.textContent = '{{ $isAr ? "تم النسخ ✓" : "Copied ✓" }}';
            setTimeout(() => { label.textContent = original; }, 1800);
        });
    }
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b1120">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $card->shop->name }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-icon.png') }}">
    <link rel="manifest" href="{{ route('loyalty-card.manifest', $card->token) }}">
    <title>{{ $card->shop->name }} — {{ app()->getLocale() === 'ar' ? 'بطاقة الولاء' : 'Loyalty Pass' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-page: #010A29;
            --surface-card: #ffffff;
            --surface-muted: #f8fafc;
            --text-primary: #010A29;
            --text-secondary: #64748b;
            --accent: #743EE2;
            --accent-soft: #f3effe;
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
                radial-gradient(circle at 15% 15%, rgba(116, 62, 226, 0.34), transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(59, 66, 213, 0.28), transparent 45%),
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
            background: linear-gradient(135deg, #743EE2 0%, #6145E8 52%, #3B42D5 100%);
            color: #ffffff;
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
            background: linear-gradient(135deg, #743EE2, #6145E8, #3B42D5);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(116, 62, 226, 0.32);
        }

        .reward-banner.is-ready .stamp-slot.filled {
            background: linear-gradient(135deg, #10b981, #059669);
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
            background: linear-gradient(90deg, #743EE2, #6145E8, #3B42D5);
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

        .wallet-grid {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.6rem;
        }

        .wallet-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            background: #0f172a;
            color: #f8fafc;
            border: 1px solid #1e293b;
            padding: 0.65rem 0.75rem;
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.76rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.15s ease;
        }

        .wallet-btn:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .wallet-modal {
            display: none;
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            text-align: start;
            font-size: 0.8rem;
            color: #334155;
            line-height: 1.55;
        }

        .wallet-modal.active {
            display: block;
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
                @if($card->shop->logo_url)
                    <img src="{{ $card->shop->logo_url }}" alt="{{ $card->shop->name }}" class="shop-avatar">
                @else
                    <div class="shop-avatar">{{ mb_substr($card->shop->name, 0, 1) }}</div>
                @endif
                <div style="min-width: 0;">
                    <div class="shop-name">{{ $card->shop->name }}</div>
                    <div class="customer-sub">{{ $card->customer->name }} · {{ $card->customer->phone }}</div>
                </div>
            </div>
            <span class="status-chip status-{{ $card->reward_status }}">{{ $statusLabel }}</span>
        </div>

        <div class="pass-body">
            <div class="reward-banner {{ $isReady ? 'is-ready' : '' }}">
                <div class="campaign-row">
                    <div class="campaign-title">{{ $card->campaign->name }}</div>
                    <div class="stamp-counter">{{ $card->current_stamps_count }} / {{ $requiredStamps }}</div>
                </div>

                @if($card->campaign->reward_name)
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

            <div class="ticket-divider"></div>

            <div class="qr-section">
                <div class="qr-frame">
                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(190)->margin(1)->generate($qrUrl) !!}
                </div>
                <div class="qr-hint">
                    {{ $isAr ? 'أظهر رمز QR للكاشير عند الدفع لإضافة ختم.' : 'Present this QR code to the cashier at checkout.' }}
                </div>
                <div class="wallet-grid">
                    <a href="{{ route('loyalty-card.apple-pass', $card->token) }}" class="wallet-btn" style="text-decoration: none;">
                        <span style="font-size: 1.05rem; line-height: 1;"></span>
                        <span>{{ $isAr ? 'أضف إلى Apple Wallet' : 'Add to Apple Wallet' }}</span>
                    </a>
                    <a href="{{ route('loyalty-card.google-pass', $card->token) }}" class="wallet-btn" style="text-decoration: none;">
                        <span style="font-size: 0.95rem; line-height: 1;">💳</span>
                        <span>{{ $isAr ? 'أضف إلى Google Wallet' : 'Add to Google Wallet' }}</span>
                    </a>
                </div>

                <div class="actions-row">
                    <button type="button" class="copy-btn" onclick="copyCardUrl(this)">
                        <span>{{ $isAr ? 'نسخ رابط البطاقة' : 'Copy Pass Link' }}</span>
                    </button>
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

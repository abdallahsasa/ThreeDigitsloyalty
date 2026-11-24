<!DOCTYPE html>
@php
    $locale = app()->getLocale() === 'en' ? 'en' : 'ar';
    $isAr = $locale === 'ar';
    $supportEmail = config('loyalty.agency.support_email');
    $hasConfiguredEmail = !empty($supportEmail) && !str_ends_with((string) $supportEmail, '@example.com');
    $homeUrl = route($isAr ? 'home.ar' : 'home.en');
    $switchUrl = route($isAr ? 'legal.terms.en' : 'legal.terms.ar');
    $privacyUrl = route($isAr ? 'legal.privacy.ar' : 'legal.privacy.en');
@endphp
<html lang="{{ $locale }}" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $isAr ? 'شروط الاستخدام لمنصة ThreeDigits Loyalty — قيد استكمال الصياغة القانونية المعتمدة قبل النشر النهائي.' : 'Terms of Service for ThreeDigits Loyalty — awaiting owner-approved legal text prior to final publication.' }}">
    <link rel="canonical" href="{{ route($isAr ? 'legal.terms.ar' : 'legal.terms.en') }}">
    <link rel="alternate" hreflang="ar" href="{{ route('legal.terms.ar') }}">
    <link rel="alternate" hreflang="en" href="{{ route('legal.terms.en') }}">
    <link rel="alternate" hreflang="x-default" href="{{ route('legal.terms.ar') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <title>{{ $isAr ? 'شروط الاستخدام — ThreeDigits Loyalty' : 'Terms of Service — ThreeDigits Loyalty' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; font-family: 'Cairo', sans-serif !important; }
        html, body { margin: 0; background: #010A29; color: #f8fafc; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
        a:focus-visible, button:focus-visible { outline: 2px solid #c4b5fd; outline-offset: 2px; }
        .wrap { width: 100%; max-width: 880px; margin: 0 auto; padding: 1.5rem 1.25rem 3.5rem; }
        .topbar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(148, 163, 184, 0.16); margin-bottom: 2rem; flex-wrap: wrap; }
        .brand { display: inline-flex; align-items: center; gap: 0.65rem; color: #f8fafc; text-decoration: none; font-weight: 800; font-size: 1.05rem; }
        .pill { padding: 0.45rem 0.9rem; border-radius: 999px; border: 1px solid rgba(148, 163, 184, 0.28); color: #f8fafc; text-decoration: none; font-size: 0.85rem; font-weight: 700; }
        .notice { background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.4); border-radius: 16px; padding: 1.15rem 1.25rem; color: #fde68a; margin-bottom: 1.75rem; line-height: 1.7; font-size: 0.94rem; }
        .card { background: #071033; border: 1px solid rgba(148, 163, 184, 0.18); border-radius: 20px; padding: 1.5rem 1.65rem; margin-bottom: 1.25rem; }
        h1 { font-size: clamp(1.6rem, 3.2vw, 2.2rem); font-weight: 800; margin: 0 0 0.5rem; }
        h2 { font-size: 1.12rem; font-weight: 800; margin: 0 0 0.6rem; color: #e2e8f0; }
        p, li { color: #cbd5e1; line-height: 1.75; font-size: 0.95rem; }
        ul { margin: 0.5rem 0 0; padding-inline-start: 1.25rem; }
        .footer-links { margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid rgba(148, 163, 184, 0.16); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; font-size: 0.88rem; color: #94a3b8; }
        .footer-links a { color: #c4b5fd; text-decoration: underline; font-weight: 700; }
    </style>
</head>
<body>
<div class="wrap">
    <header class="topbar">
        <a href="{{ $homeUrl }}" class="brand">
            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 30px; width: auto;">
            <span>ThreeDigits Loyalty</span>
        </a>
        <div style="display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap;">
            <a href="{{ $homeUrl }}" class="pill">{{ $isAr ? 'الصفحة الرئيسية' : 'Home' }}</a>
            <a href="{{ $privacyUrl }}" class="pill">{{ $isAr ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a>
            <a href="{{ $switchUrl }}" class="pill">{{ $isAr ? 'English' : 'عربي' }}</a>
        </div>
    </header>

    <main>
        <h1>{{ $isAr ? 'شروط الاستخدام' : 'Terms of Service' }}</h1>
        <p style="margin: 0 0 1.25rem; color: #94a3b8;">
            {{ $isAr ? 'ThreeDigits Loyalty — منتج من Three Digit Software' : 'ThreeDigits Loyalty — A product by Three Digit Software' }}
        </p>

        <div class="notice" role="status">
            @if($isAr)
                <strong>ملاحظة قبل النشر الرسمي:</strong> النص القانوني النهائي لشروط الاستخدام قيد الاعتماد من مالك المنصة. لم يتم وضع بنود تعاقدية أو التزامات قانونية افتراضية في هذه الصفحة.
            @else
                <strong>Pre-publication notice:</strong> The final owner-approved Terms of Service text is pending publication. No contractual terms, billing rules, or legal obligations have been invented on this page.
            @endif
        </div>

        <section class="card">
            <h2>{{ $isAr ? 'بنود مطلوبة من مالك المنصة قبل النشر النهائي' : 'Items required from the business owner before publication' }}</h2>
            <ul>
                @if($isAr)
                    <li>اسم الجهة القانونية المشغّلة للمنصة ومعلومات التواصل الرسمية.</li>
                    <li>مسؤوليات المتجر تجاه حملات الولاء والمكافآت الممنوحة للعملاء.</li>
                    <li>سياسة الفوترة والاشتراكات وما يحدث بعد انتهاء عرض الإطلاق في 31 ديسمبر 2026.</li>
                    <li>شروط الاستخدام المقبول وتعليق الحسابات وإنهائها والاختصاص القانوني.</li>
                @else
                    <li>Operating legal entity name and official contact details.</li>
                    <li>Merchant responsibilities for loyalty campaigns and customer reward fulfillment.</li>
                    <li>Subscription, renewal, and post-launch-offer policy after December 31, 2026.</li>
                    <li>Acceptable use, account suspension/termination, limitation of liability, and governing law.</li>
                @endif
            </ul>
        </section>

        <section class="card">
            <h2>{{ $isAr ? 'التواصل' : 'Contact' }}</h2>
            <p style="margin: 0;">
                @if($hasConfiguredEmail)
                    {{ $isAr ? 'للاستفسارات، تواصل عبر:' : 'For inquiries, contact:' }}
                    <a href="mailto:{{ $supportEmail }}" style="color: #c4b5fd; font-weight: 700;">{{ $supportEmail }}</a>
                @else
                    <a href="{{ config('loyalty.agency.url', 'https://threedigitsoftware.com/') }}" target="_blank" rel="noopener noreferrer" style="color: #c4b5fd; font-weight: 700;">
                        {{ $isAr ? 'التواصل عبر موقع Three Digit Software' : 'Contact via Three Digit Software' }}
                    </a>
                @endif
            </p>
        </section>
    </main>

    <footer class="footer-links">
        <span>© {{ date('Y') }} ThreeDigits Loyalty</span>
        <div style="display: flex; gap: 1rem;">
            <a href="{{ $homeUrl }}">{{ $isAr ? 'الرئيسية' : 'Home' }}</a>
            <a href="{{ $privacyUrl }}">{{ $isAr ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a>
        </div>
    </footer>
</div>
</body>
</html>

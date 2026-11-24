@php
    $currentLocale = app()->getLocale() === 'en' ? 'en' : 'ar';
    $canonicalUrl = route($currentLocale === 'en' ? 'home.en' : 'home.ar');
@endphp
<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $currentLocale === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ __('landing.meta_description') }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="ar" href="{{ route('home.ar') }}">
    <link rel="alternate" hreflang="en" href="{{ route('home.en') }}">
    <link rel="alternate" hreflang="x-default" href="{{ route('home.ar') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <title>{{ __('landing.meta_title') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --dl-bg: #010A29;
            --dl-surface: #071033;
            --dl-surface-2: #0C1742;
            --dl-border: rgba(148, 163, 184, 0.18);
            --dl-border-purple: rgba(116, 62, 226, 0.55);
            --dl-text: #f8fafc;
            --dl-muted: #cbd5e1;
            --dl-subtle: #94a3b8;
            --dl-purple: #743EE2;
            --dl-purple-dark: #5B21B6;
            --dl-purple-soft: #c4b5fd;
            --dl-green: #10b981;
            --dl-green-soft: #6ee7b7;
        }

        *, *::before, *::after, body, button, input, select, h1, h2, h3, h4, h5, h6 {
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif !important;
        }

        html, body {
            margin: 0;
            background-color: var(--dl-bg);
            color: var(--dl-text);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        a:focus-visible, button:focus-visible {
            outline: 2px solid #c4b5fd;
            outline-offset: 2px;
        }

        .dl-container {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        .dl-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: var(--dl-purple);
            color: #ffffff;
            font-weight: 700;
            padding: 0.85rem 1.6rem;
            border-radius: 999px;
            border: 1px solid rgba(196, 181, 253, 0.25);
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .dl-btn-primary:hover {
            background: var(--dl-purple-dark);
            transform: translateY(-1px);
        }

        .dl-btn-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            color: var(--dl-text);
            border: 1px solid var(--dl-border);
            font-weight: 700;
            padding: 0.85rem 1.6rem;
            border-radius: 999px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .dl-btn-ghost:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .dl-card {
            background: var(--dl-surface);
            border: 1px solid var(--dl-border);
            border-radius: 18px;
        }

        .dl-section {
            padding: 4rem 0;
        }

        @media (min-width: 768px) {
            .dl-section {
                padding: 4.75rem 0;
            }
        }

        .dl-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
            background: rgba(116, 62, 226, 0.16);
            border: 1px solid rgba(196, 181, 253, 0.28);
            color: var(--dl-purple-soft);
            font-size: 0.8rem;
            font-weight: 700;
        }

        .dl-section-head {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 2.5rem;
        }

        .dl-section-head h2 {
            font-size: clamp(1.65rem, 3.2vw, 2.5rem);
            font-weight: 800;
            margin: 0.75rem 0 0.65rem;
            line-height: 1.25;
            color: var(--dl-text);
        }

        .dl-section-head p {
            color: var(--dl-muted);
            font-size: 1rem;
            line-height: 1.65;
            margin: 0;
        }

        .ltr-inline {
            direction: ltr;
            unicode-bidi: isolate;
            display: inline-block;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body>
    <x-landing.nav />

    <main>
        {{-- 1. Hero (Navy): Checkout scene + live interactive stamp card --}}
        <x-landing.hero />

        {{-- 2. How it works (Warm Cream): 3 coordinated café steps + bilingual HTML UI --}}
        <x-landing.how-it-works />

        {{-- 3. Merchant tools (Navy Split Layouts): Branded card setup + staff stamp/redeem + real screens --}}
        <x-landing.dashboard-showcase />

        {{-- 4. Industries (Soft Lavender): Image-led showcases for cafés, restaurants & salons --}}
        <x-landing.examples />

        {{-- 5. Pricing & launch offer (Navy) --}}
        <x-landing.pricing :plans="$plans" />

        {{-- 6. FAQs (Warm Cream) --}}
        <x-landing.faq />

        {{-- 7. Final activation CTA (Navy) --}}
        <x-landing.cta />
    </main>

    {{-- 10. Footer with contact and policy links --}}
    <x-landing.footer />

    <script>
        window.trackFunnelEvent = function (eventName, properties) {
            try {
                var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                fetch('{{ route('funnel.track') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ event: eventName, properties: properties || {} }),
                    keepalive: true
                }).catch(function () {});
            } catch (e) {}
        };
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>

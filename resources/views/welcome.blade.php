<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <title>ThreeDigits Loyalty | Luxury Digital Loyalty & Passbook SaaS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --dl-bg: #010A29;
            --dl-surface: #071033;
            --dl-surface-2: #0C1742;
            --dl-border: rgba(116, 62, 226, 0.2);
            --dl-border-gold: rgba(116, 62, 226, 0.55);
            --dl-text: #f8fafc;
            --dl-muted: #94a3b8;
            --dl-gold: #743EE2;
            --dl-gold-bright: #9F7AEA;
            --dl-emerald: #10b981;
        }

        *, *::before, *::after, body, button, input, h1, h2, h3, h4, h5, h6, .font-heading {
            box-sizing: border-box;
            font-family: 'Montserrat', 'Cairo', sans-serif !important;
        }

        body {
            margin: 0;
            background-color: var(--dl-bg);
            color: var(--dl-text);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .dl-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .dl-grid-bg {
            background-image:
                linear-gradient(to right, rgba(116, 62, 226, 0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(116, 62, 226, 0.06) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        .dl-gold-text {
            background: linear-gradient(135deg, #A78BFA 0%, #743EE2 50%, #3B42D5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dl-btn-gold {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #743EE2 0%, #6145E8 50%, #3B42D5 100%);
            color: #ffffff;
            font-weight: 700;
            padding: 0.85rem 1.75rem;
            border-radius: 999px;
            text-decoration: none;
            box-shadow: 0 10px 30px -8px rgba(116, 62, 226, 0.55);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dl-btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px -8px rgba(116, 62, 226, 0.75);
        }

        .dl-btn-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.04);
            color: var(--dl-text);
            border: 1px solid var(--dl-border);
            font-weight: 600;
            padding: 0.85rem 1.75rem;
            border-radius: 999px;
            text-decoration: none;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .dl-btn-ghost:hover {
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(255, 255, 255, 0.22);
        }

        .dl-card {
            background: var(--dl-surface);
            border: 1px solid var(--dl-border);
            border-radius: 20px;
            transition: border-color 0.25s ease, transform 0.25s ease;
        }

        .dl-card:hover {
            border-color: var(--dl-border-gold);
            transform: translateY(-3px);
        }

        .dl-section {
            padding: 5.5rem 0;
        }

        .dl-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.28);
            color: var(--dl-gold-bright);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .dl-section-head {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 3.5rem;
        }

        .dl-section-head h2 {
            font-size: clamp(1.9rem, 3.5vw, 2.9rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            margin: 0.75rem 0 0.75rem;
            line-height: 1.15;
        }

        .dl-section-head p {
            color: var(--dl-muted);
            font-size: 1.05rem;
            line-height: 1.65;
            margin: 0;
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .dl-float { animation: floatSlow 6s ease-in-out infinite; }
    </style>
</head>
<body>
    <x-landing.nav />

    <main>
        <x-landing.hero />
        <x-landing.trusted-by :clients="$clients" />
        <x-landing.how-it-works />
        <x-landing.features />
        <x-landing.dashboard-showcase />
        <x-landing.card-preview />
        <x-landing.comparison />
        <x-landing.benefits />
        <x-landing.pricing :plans="$plans" />
        <x-landing.faq />
        <x-landing.cta />
    </main>

    <x-landing.footer />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>

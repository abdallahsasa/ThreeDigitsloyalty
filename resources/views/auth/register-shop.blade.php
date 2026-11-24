<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    <title>{{ app()->getLocale() === 'ar' ? 'أنشئ بطاقة متجرك — ThreeDigits Loyalty' : 'Create Your Store Card — ThreeDigits Loyalty' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #010A29;
            --surface: #071033;
            --surface-2: #0C1742;
            --border: rgba(148, 163, 184, 0.22);
            --text: #f8fafc;
            --muted: #cbd5e1;
            --purple: #743EE2;
            --purple-dark: #5B21B6;
            --green: #10b981;
            --red: #f87171;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            font-family: 'Cairo', sans-serif !important;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            width: 100%;
            max-width: 1040px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding-bottom: 1.5rem;
            flex-wrap: nowrap;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text);
            text-decoration: none;
            font-weight: 800;
            font-size: 0.96rem;
            white-space: nowrap;
            min-height: 44px;
            flex-shrink: 0;
        }

        @media (min-width: 480px) {
            .brand {
                gap: 0.65rem;
                font-size: 1.1rem;
            }
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .topbar-login {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            padding: 0 0.45rem;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .lang-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 0.9rem;
            border-radius: 999px;
            border: 1px solid var(--border);
            color: var(--text);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .lang-pill:focus-visible, .btn-primary:focus-visible, input:focus-visible, select:focus-visible, button:focus-visible, a:focus-visible {
            outline: 2px solid #A78BFA;
            outline-offset: 2px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            align-items: start;
        }

        @media (min-width: 860px) {
            .grid {
                grid-template-columns: 1.15fr 0.85fr;
            }
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.75rem;
        }

        @media (min-width: 640px) {
            .card { padding: 2.25rem; }
        }

        .field {
            margin-bottom: 1.15rem;
        }

        label {
            display: block;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.45rem;
        }

        input, select {
            width: 100%;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--surface-2);
            color: var(--text);
            font-size: 0.95rem;
        }

        input::placeholder {
            color: #94A3B8;
            opacity: 1;
        }

        input[aria-invalid="true"], select[aria-invalid="true"] {
            border-color: var(--red);
        }

        input:disabled, select:disabled, .btn-primary:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .password-wrap {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            inset-inline-end: 0.75rem;
            background: none;
            border: none;
            color: var(--muted);
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            padding: 0.35rem 0.5rem;
        }

        .error-text {
            color: var(--red);
            font-size: 0.82rem;
            font-weight: 600;
            margin-top: 0.35rem;
        }

        .btn-primary {
            width: 100%;
            border: none;
            border-radius: 999px;
            padding: 0.95rem 1.5rem;
            background: var(--purple);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .btn-primary:hover {
            background: var(--purple-dark);
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            padding: 0.85rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .step-item:last-child {
            border-bottom: none;
        }

        .step-num {
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: rgba(116, 62, 226, 0.2);
            border: 1px solid rgba(167, 139, 250, 0.4);
            color: #c4b5fd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 800;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
@php $isAr = app()->getLocale() === 'ar'; @endphp

<div class="container">
    <header class="topbar">
        <a href="{{ route($isAr ? 'home.ar' : 'home.en') }}" class="brand">
            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 32px; width: auto;">
            <span>ThreeDigits Loyalty</span>
        </a>

        <div class="topbar-actions">
            <a href="{{ url('/shop/login') }}" class="topbar-login">
                {{ $isAr ? 'دخول المتاجر' : 'Merchant login' }}
            </a>
            <a href="{{ route('lang.switch', ['locale' => $isAr ? 'en' : 'ar', 'plan' => old('plan', $selectedPlanSlug)]) }}"
               id="register-lang-switch"
               onclick="var s = document.getElementById('plan'); if (s && s.value) { this.href = '{{ route('lang.switch', $isAr ? 'en' : 'ar') }}?plan=' + encodeURIComponent(s.value); }"
               class="lang-pill">
                {{ $isAr ? 'English' : 'عربي' }}
            </a>
        </div>
    </header>

    <main class="grid">
        <section class="card" style="border-color: rgba(196, 181, 253, 0.35);">
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.9rem; border-radius: 999px; background: rgba(16, 185, 129, 0.14); border: 1px solid rgba(16, 185, 129, 0.35); color: #6ee7b7; font-size: 0.875rem; font-weight: 700; margin-bottom: 1rem;">
                {{ $isAr ? 'مجاني حتى 31 ديسمبر 2026 — بدون بطاقة دفع' : 'Free until December 31, 2026 — no payment card required' }}
            </div>

            <h1 style="font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 800; margin: 0 0 0.5rem;">
                {{ $isAr ? 'أنشئ حساب متجرك' : 'Create your store account' }}
            </h1>
            <p style="margin: 0 0 1.65rem; color: var(--muted); font-size: 1rem; line-height: 1.65;">
                {{ $isAr ? 'أدخل البيانات الأساسية لفتح لوحة متجرك وإعداد أول بطاقة ولاء.' : 'Enter the essentials to open your merchant dashboard and set up your first loyalty card.' }}
            </p>

            <form method="POST" action="{{ route('merchant.register.store') }}" novalidate>
                @csrf

                <div class="field">
                    <label for="shop_name">{{ $isAr ? 'اسم المتجر أو المقهى' : 'Store or café name' }}</label>
                    <input id="shop_name" name="shop_name" type="text" value="{{ old('shop_name') }}" required autocomplete="organization" placeholder="{{ $isAr ? 'مثال: مقهى الياسمين' : 'e.g. Jasmine Café' }}" @error('shop_name') aria-invalid="true" aria-describedby="shop_name-error" @enderror>
                    @error('shop_name') <div id="shop_name-error" class="error-text" role="alert">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="name">{{ $isAr ? 'اسمك الكامل' : 'Your full name' }}</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" placeholder="{{ $isAr ? 'الاسم الكامل' : 'Full name' }}" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                    @error('name') <div id="name-error" class="error-text" role="alert">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="email">{{ $isAr ? 'البريد الإلكتروني' : 'Email address' }}</label>
                    <input id="email" name="email" type="email" dir="ltr" style="text-align: start;" value="{{ old('email') }}" required autocomplete="email" placeholder="owner@yourstore.com" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email') <div id="email-error" class="error-text" role="alert">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="password">{{ $isAr ? 'كلمة المرور (8 أحرف على الأقل)' : 'Password (minimum 8 characters)' }}</label>
                    <div class="password-wrap">
                        <input id="password" name="password" type="password" dir="ltr" required autocomplete="new-password" minlength="8" placeholder="{{ $isAr ? '8 أحرف على الأقل' : 'At least 8 characters' }}" style="padding-inline-end: 4.5rem;" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        <button type="button" class="password-toggle" id="toggle-pw" aria-label="{{ $isAr ? 'إظهار أو إخفاء كلمة المرور' : 'Show or hide password' }}">
                            {{ $isAr ? 'إظهار' : 'Show' }}
                        </button>
                    </div>
                    @error('password') <div id="password-error" class="error-text" role="alert">{{ $message }}</div> @enderror
                </div>

                @if($plans->isNotEmpty())
                    <div class="field">
                        <label for="plan">{{ $isAr ? 'الخطة المختارة' : 'Selected plan' }}</label>
                        <select id="plan" name="plan" @error('plan') aria-invalid="true" aria-describedby="plan-error" @enderror>
                            @foreach($plans as $plan)
                                @php
                                    $slug = \Illuminate\Support\Str::slug($plan->name);
                                    $intPrice = (int) round((float) $plan->price);
                                    $arPlanName = match (strtolower(trim($plan->name))) {
                                        'starter' => 'البداية',
                                        'business' => 'الأعمال',
                                        'enterprise' => 'الشركات',
                                        default => $plan->name,
                                    };
                                    $arAfterPrice = $intPrice <= 10 ? "{$intPrice} دولارات شهرياً" : "{$intPrice} دولاراً شهرياً";
                                @endphp
                                <option value="{{ $slug }}" {{ old('plan', $selectedPlanSlug) === $slug ? 'selected' : '' }}>
                                    @if($isAr)
                                        {{ $arPlanName }} — مجانية حتى 31 ديسمبر 2026 (بعد العرض: {{ $arAfterPrice }})
                                    @else
                                        {{ $plan->name }} — Free until Dec 31, 2026 (then ${{ $intPrice }}/month)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('plan') <div id="plan-error" class="error-text" role="alert">{{ $message }}</div> @enderror
                    </div>
                @endif

                <p style="margin: 0 0 1rem; font-size: 0.84rem; color: var(--muted); line-height: 1.65;">
                    @if($isAr)
                        قبل إنشاء الحساب، يمكنك مراجعة
                        <a href="{{ route('legal.privacy.ar') }}" target="_blank" rel="noopener noreferrer" style="color: #c4b5fd; font-weight: 700; text-decoration: underline;">سياسة الخصوصية</a>
                        و<a href="{{ route('legal.terms.ar') }}" target="_blank" rel="noopener noreferrer" style="color: #c4b5fd; font-weight: 700; text-decoration: underline;">شروط الاستخدام</a>.
                    @else
                        Before creating your account, review our
                        <a href="{{ route('legal.privacy.en') }}" target="_blank" rel="noopener noreferrer" style="color: #c4b5fd; font-weight: 700; text-decoration: underline;">Privacy Policy</a>
                        and
                        <a href="{{ route('legal.terms.en') }}" target="_blank" rel="noopener noreferrer" style="color: #c4b5fd; font-weight: 700; text-decoration: underline;">Terms of Service</a>.
                    @endif
                </p>

                <button type="submit" class="btn-primary">
                    {{ $isAr ? 'أنشئ حساب متجري' : 'Create my store account' }}
                </button>
            </form>

            <p style="margin: 1.25rem 0 0; text-align: center; font-size: 0.92rem; color: var(--muted);">
                {{ $isAr ? 'لديك حساب متجر بالفعل؟' : 'Already have a store account?' }}
                <a href="{{ url('/shop/login') }}" style="color: #c4b5fd; font-weight: 700; text-decoration: underline;">
                    {{ $isAr ? 'تسجيل الدخول' : 'Merchant login' }}
                </a>
            </p>
        </section>

        <aside class="card" style="background: #050C2A; border-color: rgba(148, 163, 184, 0.16); padding: 1.5rem;">
            <h2 style="font-size: 1.05rem; font-weight: 800; margin: 0 0 0.45rem; color: #e2e8f0;">
                {{ $isAr ? 'الخطوات التالية بعد التسجيل' : 'What happens after you register' }}
            </h2>
            <p style="margin: 0 0 1rem; color: var(--muted); font-size: 0.9rem; line-height: 1.65;">
                {{ $isAr ? 'ستنتقل مباشرة إلى دليل البدء السريع لإطلاق برنامجك:' : 'You will go straight to a guided setup checklist to launch:' }}
            </p>

            <div>
                <div class="step-item">
                    <span class="step-num">1</span>
                    <div>
                        <div style="font-weight: 700;">{{ $isAr ? 'تفاصيل المتجر والشعار' : 'Store details and logo' }}</div>
                        <div style="font-size: 0.85rem; color: var(--muted);">{{ $isAr ? 'أضف شعار متجرك وبيانات الفرع الأول.' : 'Upload your logo and set your first branch.' }}</div>
                    </div>
                </div>
                <div class="step-item">
                    <span class="step-num">2</span>
                    <div>
                        <div style="font-weight: 700;">{{ $isAr ? 'أول حملة ولاء' : 'First reward campaign' }}</div>
                        <div style="font-size: 0.85rem; color: var(--muted);">{{ $isAr ? 'حدد عدد الأختام المطلوبة والمكافأة.' : 'Choose required stamps and the customer reward.' }}</div>
                    </div>
                </div>
                <div class="step-item">
                    <span class="step-num">3</span>
                    <div>
                        <div style="font-weight: 700;">{{ $isAr ? 'رمز QR للتسجيل' : 'Registration QR' }}</div>
                        <div style="font-size: 0.85rem; color: var(--muted);">{{ $isAr ? 'اطبع ملصق الكاونتر ليفتح العملاء بطاقاتهم.' : 'Print your counter poster so customers can join.' }}</div>
                    </div>
                </div>
                <div class="step-item">
                    <span class="step-num">4</span>
                    <div>
                        <div style="font-weight: 700;">{{ $isAr ? 'أول بطاقة وختم' : 'First customer card & stamp' }}</div>
                        <div style="font-size: 0.85rem; color: var(--muted);">{{ $isAr ? 'امسح بطاقة العميل وأضف الختم من لوحة المتجر.' : 'Scan a customer card and add a stamp.' }}</div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 1.25rem; padding: 0.9rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.04); font-size: 0.85rem; color: var(--muted); line-height: 1.6;">
                {{ $isAr ? 'بيانات متجرك منفصلة عن بيانات المتاجر الأخرى.' : 'Your store’s data is kept separate from other stores.' }}
            </div>
        </aside>
    </main>
</div>

<script>
    (function () {
        var firstInvalid = document.querySelector('[aria-invalid="true"]');
        if (firstInvalid && typeof firstInvalid.focus === 'function') {
            firstInvalid.focus();
        }

        var btn = document.getElementById('toggle-pw');
        var input = document.getElementById('password');
        if (!btn || !input) return;
        btn.addEventListener('click', function () {
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.textContent = isHidden
                ? @json($isAr ? 'إخفاء' : 'Hide')
                : @json($isAr ? 'إظهار' : 'Show');
        });
    })();
</script>
</body>
</html>

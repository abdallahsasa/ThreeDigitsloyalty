<x-filament-panels::page>
    @php $isAr = app()->getLocale() === 'ar'; @endphp

    <style>
        .td-qr-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            align-items: start;
        }
        @media (min-width: 960px) {
            .td-qr-grid {
                grid-template-columns: 440px 1fr;
            }
        }
        .td-poster-card {
            background: linear-gradient(160deg, #010A29 0%, #09143E 100%);
            border: 1px solid rgba(116, 62, 226, 0.35);
            border-radius: 24px;
            overflow: hidden;
            color: #ffffff;
            text-align: center;
            box-shadow: 0 20px 45px -12px rgba(1, 10, 41, 0.35);
        }
        .td-poster-header {
            background: linear-gradient(135deg, #743EE2 0%, #6145E8 52%, #3B42D5 100%);
            padding: 1.5rem 1.25rem;
        }
        .td-poster-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        .td-poster-shop {
            font-size: 1.35rem;
            font-weight: 800;
            margin: 0;
        }
        .td-poster-sub {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 0.25rem;
        }
        .td-poster-body {
            padding: 1.75rem 1.5rem;
        }
        .td-qr-frame {
            display: inline-flex;
            padding: 1.15rem;
            background: #ffffff;
            border-radius: 22px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
            margin-bottom: 1.15rem;
        }
        .td-url-pill {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 0.55rem 0.85rem;
            font-size: 0.78rem;
            color: #cbd5e1;
            word-break: break-all;
            margin-bottom: 1.25rem;
        }
        .td-btn-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.65rem;
        }
        .td-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: linear-gradient(135deg, #743EE2 0%, #6145E8 50%, #3B42D5 100%);
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.84rem;
            padding: 0.65rem 1.15rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 20px -6px rgba(116, 62, 226, 0.6);
        }
        .td-btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #f8fafc;
            font-family: inherit;
            font-weight: 700;
            font-size: 0.84rem;
            padding: 0.65rem 1.15rem;
            border-radius: 12px;
            cursor: pointer;
        }
        .td-steps-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 1.75rem;
        }
        .dark .td-steps-card {
            background: #111827;
            border-color: rgba(255, 255, 255, 0.1);
        }
        .td-steps-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #010A29;
            margin-bottom: 0.35rem;
        }
        .dark .td-steps-title {
            color: #f8fafc;
        }
        .td-steps-desc {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 1.35rem;
        }
        .td-step-list {
            display: grid;
            gap: 0.85rem;
        }
        .td-step-item {
            display: flex;
            align-items: flex-start;
            gap: 0.9rem;
            padding: 1rem 1.1rem;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .dark .td-step-item {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.08);
        }
        .td-step-num {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #743EE2, #3B42D5);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
            flex-shrink: 0;
        }
        .td-step-text {
            font-size: 0.88rem;
            line-height: 1.6;
            color: #334155;
        }
        .dark .td-step-text {
            color: #cbd5e1;
        }
        @media print {
            .fi-sidebar, .fi-topbar, .fi-header, .td-steps-card, .td-btn-row {
                display: none !important;
            }
            .fi-main, .fi-page, body {
                padding: 0 !important;
                margin: 0 !important;
                background: #ffffff !important;
            }
            .td-qr-grid {
                display: block !important;
            }
            .td-poster-card {
                max-width: 480px !important;
                margin: 0 auto !important;
                box-shadow: none !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>

    @if($shop)
        <div class="td-qr-grid">
            {{-- Left: Branded Printable In-Store Counter Stand --}}
            <div class="td-poster-card">
                <div class="td-poster-header">
                    <span class="td-poster-badge">ThreeDigits Loyalty</span>
                    <h2 class="td-poster-shop">{{ $shop->name }}</h2>
                    <div class="td-poster-sub">
                        {{ $isAr ? 'امسح الرمز للانضمام والحصول على بطاقة الولاء' : 'Scan to Join & Get Your Digital Loyalty Pass' }}
                    </div>
                </div>

                <div class="td-poster-body">
                    <div class="td-qr-frame">
                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(210)->margin(1)->generate($joinUrl) !!}
                    </div>

                    <div class="td-url-pill">{{ $joinUrl }}</div>

                    <div class="td-btn-row">
                        <a href="{{ $joinUrl }}" target="_blank" class="td-btn-primary">
                            <span>↗</span>
                            <span>{{ $isAr ? 'فتح صفحة التسجيل' : 'Open Customer Join Page' }}</span>
                        </a>

                        <a href="{{ route('shop.join.poster', ['shop' => $shop->id, 'print' => 1]) }}" target="_blank" class="td-btn-ghost" style="text-decoration: none;">
                            <span>🖨️</span>
                            <span>{{ $isAr ? 'تحميل / طباعة PDF' : 'Print / Save PDF Poster' }}</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right: How It Works Steps --}}
            <div class="td-steps-card">
                <h3 class="td-steps-title">
                    {{ $isAr ? 'كيف يعمل رمز QR لعملائك؟' : 'How It Works for Your Customers' }}
                </h3>
                <p class="td-steps-desc">
                    {{ $isAr
                        ? 'اعرض هذا الرمز عند الكاشير أو اطبعه لتمكين عملائك من التسجيل في ثوانٍ.'
                        : 'Display or print this QR stand at your checkout counter so customers can enroll in 15 seconds.' }}
                </p>

                <div class="td-step-list">
                    <div class="td-step-item">
                        <div class="td-step-num">1</div>
                        <div class="td-step-text">
                            @if($isAr)
                                يمسح العميل رمز QR الخاص بمتجر <strong>{{ $shop->name }}</strong> بكاميرا هاتفه (iPhone أو Android).
                            @else
                                Customer scans your <strong>{{ $shop->name }}</strong> QR code using their iPhone or Android camera.
                            @endif
                        </div>
                    </div>

                    <div class="td-step-item">
                        <div class="td-step-num">2</div>
                        <div class="td-step-text">
                            @if($isAr)
                                يدخل العميل <strong>الاسم، البريد الإلكتروني، ورقم الجوال</strong> فقط في صفحة التسجيل السريع.
                            @else
                                They enter only <strong>Name, Email, and Phone Number</strong> on your branded registration page.
                            @endif
                        </div>
                    </div>

                    <div class="td-step-item">
                        <div class="td-step-num">3</div>
                        <div class="td-step-text">
                            @if($isAr)
                                تفتح بطاقة الولاء الرقمية فوراً ليتمكن من إضافتها إلى <strong>iPhone</strong> أو <strong>Android</strong> بضغطة زر.
                            @else
                                Their digital loyalty pass opens immediately so they can save it to <strong>iPhone</strong> or <strong>Android</strong>.
                            @endif
                        </div>
                    </div>

                    <div class="td-step-item">
                        <div class="td-step-num">4</div>
                        <div class="td-step-text">
                            @if($isAr)
                                تصله تلقائياً رسالة ترحيب عربية (بخط <strong>Cairo</strong>) من <strong>ThreeDigits Loyalty و {{ $shop->name }}</strong> تحتوي على رابط البطاقة وتعليمات الاستخدام.
                            @else
                                They automatically receive an Arabic welcome email (in <strong>Cairo</strong> font) from <strong>ThreeDigits Loyalty & {{ $shop->name }}</strong> with their pass link and instructions.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>

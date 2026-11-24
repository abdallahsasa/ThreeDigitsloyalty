@php
    $isAr = app()->getLocale() === 'ar';
    $sampleCustomers = $isAr ? [
        ['name' => 'سارة أحمد', 'stamps' => '٥ من ٦', 'status' => 'قيد التجميع'],
        ['name' => 'خالد محمود', 'stamps' => '٦ من ٦', 'status' => 'مكافأة جاهزة'],
        ['name' => 'نور الهدى', 'stamps' => '٢ من ٦', 'status' => 'قيد التجميع'],
    ] : [
        ['name' => 'Sara Ahmad', 'stamps' => '5 of 6', 'status' => 'Collecting'],
        ['name' => 'Khaled Mahmoud', 'stamps' => '6 of 6', 'status' => 'Reward ready'],
        ['name' => 'Nour Al-Huda', 'stamps' => '2 of 6', 'status' => 'Collecting'],
    ];

    $sampleTransactions = $isAr ? [
        ['customer' => 'خالد محمود', 'action' => 'فتح مكافأة (+1 ختم)', 'time' => 'اليوم'],
        ['customer' => 'سارة أحمد', 'action' => 'إضافة عملية شراء (+1 ختم)', 'time' => 'اليوم'],
    ] : [
        ['customer' => 'Khaled Mahmoud', 'action' => 'Reward unlocked (+1 stamp)', 'time' => 'Today'],
        ['customer' => 'Sara Ahmad', 'action' => 'Purchase added (+1 stamp)', 'time' => 'Today'],
    ];
@endphp

<section class="dl-section" style="background: #040D30; border-top: 1px solid rgba(148, 163, 184, 0.14);">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ $isAr ? 'شاشات حقيقية من النظام' : 'Real Product Screens' }}</span>
            <h2>{{ __('landing.dashboard_title') }}</h2>
            <p>{{ __('landing.dashboard_subtitle') }}</p>
        </div>

        {{-- Verified Product Screenshots Row --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.35rem; margin-bottom: 1.75rem; align-items: stretch;">
            <figure style="margin: 0; padding: 1.15rem; border-radius: 18px; background: #071033; border: 1px solid rgba(196, 181, 253, 0.25); display: flex; flex-direction: column; align-items: center; text-align: center;">
                <img src="{{ asset('images/landing/ui-qr-poster.webp') }}"
                     alt="{{ $isAr ? 'شاشة رمز QR وصفحة تسجيل العملاء داخل لوحة المتجر' : 'Store QR code and customer registration poster inside the merchant portal' }}"
                     width="273"
                     height="592"
                     loading="lazy"
                     style="width: auto; max-width: 100%; max-height: 360px; border-radius: 14px; border: 1px solid rgba(148, 163, 184, 0.2); box-shadow: 0 14px 32px rgba(0, 0, 0, 0.35);">
                <figcaption style="margin-top: 0.95rem;">
                    <div style="font-size: 0.98rem; font-weight: 800; color: #f8fafc;">
                        {{ $isAr ? '1. رمز QR الجاهز للطباعة' : '1. Ready-to-Print Store QR' }}
                    </div>
                    <div style="font-size: 0.86rem; color: #cbd5e1; margin-top: 0.2rem;">
                        {{ $isAr ? 'اعرضه عند الكاشير أو اطبعه بملف PDF بضغطة واحدة.' : 'Display at checkout or download as a print-ready PDF.' }}
                    </div>
                </figcaption>
            </figure>

            <figure style="margin: 0; padding: 1.15rem; border-radius: 18px; background: #071033; border: 1px solid rgba(196, 181, 253, 0.25); display: flex; flex-direction: column; align-items: center; text-align: center;">
                <img src="{{ asset('images/landing/ui-customer-join.webp') }}"
                     alt="{{ $isAr ? 'نموذج تسجيل العميل السريع خلال 15 ثانية عبر المتصفح' : '15-second customer quick signup screen in mobile browser' }}"
                     width="276"
                     height="522"
                     loading="lazy"
                     style="width: auto; max-width: 100%; max-height: 360px; border-radius: 14px; border: 1px solid rgba(148, 163, 184, 0.2); box-shadow: 0 14px 32px rgba(0, 0, 0, 0.35);">
                <figcaption style="margin-top: 0.95rem;">
                    <div style="font-size: 0.98rem; font-weight: 800; color: #f8fafc;">
                        {{ $isAr ? '2. تسجيل سريع في 15 ثانية' : '2. 15-Second Customer Join' }}
                    </div>
                    <div style="font-size: 0.86rem; color: #cbd5e1; margin-top: 0.2rem;">
                        {{ $isAr ? 'الاسم ورقم الجوال فقط — بدون تحميل تطبيق.' : 'Name and mobile number in the browser — no app needed.' }}
                    </div>
                </figcaption>
            </figure>

            <figure style="margin: 0; padding: 1.15rem; border-radius: 18px; background: #071033; border: 1px solid rgba(196, 181, 253, 0.25); display: flex; flex-direction: column; align-items: center; text-align: center; justify-content: space-between;">
                <img src="{{ asset('images/landing/ui-vip-card.webp') }}"
                     alt="{{ $isAr ? 'بطاقة الولاء الرقمية الحية للعميل مع الأختام ورمز QR الشخصي' : 'Customer live digital loyalty card with stamps and personal QR code' }}"
                     width="270"
                     height="362"
                     loading="lazy"
                     style="width: auto; max-width: 100%; max-height: 360px; border-radius: 14px; border: 1px solid rgba(148, 163, 184, 0.2); box-shadow: 0 14px 32px rgba(0, 0, 0, 0.35); margin-top: auto; margin-bottom: auto;">
                <figcaption style="margin-top: 0.95rem;">
                    <div style="font-size: 0.98rem; font-weight: 800; color: #f8fafc;">
                        {{ $isAr ? '3. بطاقة العميل الرقمية ورمز QR' : '3. Live Stamp Card & Personal QR' }}
                    </div>
                    <div style="font-size: 0.86rem; color: #cbd5e1; margin-top: 0.2rem;">
                        {{ $isAr ? 'يمسحها الكاشير لإضافة الأختام وصرف المكافآت فوراً.' : 'Scanned at checkout to add stamps and redeem rewards.' }}
                    </div>
                </figcaption>
            </figure>
        </div>

        <div class="dl-card" style="padding: 1.5rem; background: #071033; border-color: rgba(196, 181, 253, 0.25);">
            {{-- Portal Header + QR Scanner Entry Point --}}
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; padding-bottom: 1.15rem; margin-bottom: 1.25rem; border-bottom: 1px solid rgba(148, 163, 184, 0.16);">
                <div>
                    <div style="font-size: 1.05rem; font-weight: 800; color: #f8fafc;">
                        {{ $isAr ? 'معاينة توضيحية: البحث عن عميل وإضافة ختم — مقهى الياسمين' : 'Illustrative Preview: Find a Customer & Add a Stamp — Jasmine Café' }}
                    </div>
                    <div style="font-size: 0.875rem; color: #cbd5e1; margin-top: 0.2rem;">
                        {{ $isAr ? 'مثال توضيحي ببيانات تجريبية لمسار العمل اليومي للكاشير داخل لوحة المتجر.' : 'Illustrative demo-data preview showing how your cashier finds a customer and stamps a card.' }}
                    </div>
                </div>

                <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.55rem 1rem; border-radius: 12px; background: rgba(116, 62, 226, 0.2); border: 1px solid rgba(196, 181, 253, 0.35); color: #f8fafc; font-size: 0.88rem; font-weight: 700;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c4b5fd" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                        <path d="M14 14h3v3h-3z"></path>
                    </svg>
                    <span>{{ $isAr ? 'ماسح QR وبحث برقم الجوال' : 'QR Scanner & Phone Lookup' }}</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
                {{-- Campaign Controls --}}
                <div style="padding: 1.15rem; border-radius: 14px; background: #0C1742; border: 1px solid rgba(148, 163, 184, 0.18);">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; margin-bottom: 0.85rem;">
                        <h3 style="margin: 0; font-size: 0.98rem; font-weight: 800; color: #f8fafc;">
                            {{ $isAr ? 'إعدادات الحملة' : 'Campaign Controls' }}
                        </h3>
                        <span style="padding: 0.2rem 0.6rem; border-radius: 999px; background: rgba(16, 185, 129, 0.16); color: #6ee7b7; font-size: 0.78rem; font-weight: 700;">
                            {{ $isAr ? 'نشطة' : 'Active' }}
                        </span>
                    </div>
                    <div style="display: grid; gap: 0.55rem; font-size: 0.88rem; color: #cbd5e1;">
                        <div><strong style="color: #f8fafc;">{{ $isAr ? 'الحملة:' : 'Campaign:' }}</strong> {{ $isAr ? 'بطاقة أختام القهوة' : 'Coffee Stamp Card' }}</div>
                        <div><strong style="color: #f8fafc;">{{ $isAr ? 'الأختام المطلوبة:' : 'Required stamps:' }}</strong> {{ $isAr ? '٦ أختام' : '6 stamps' }}</div>
                        <div><strong style="color: #f8fafc;">{{ $isAr ? 'المكافأة:' : 'Reward:' }}</strong> {{ $isAr ? 'قهوة مجانية' : 'Free coffee' }}</div>
                    </div>
                </div>

                {{-- Customer List --}}
                <div style="padding: 1.15rem; border-radius: 14px; background: #0C1742; border: 1px solid rgba(148, 163, 184, 0.18);">
                    <h3 style="margin: 0 0 0.85rem; font-size: 0.98rem; font-weight: 800; color: #f8fafc;">
                        {{ $isAr ? 'قائمة العملاء (نموذج)' : 'Customer List (Demo)' }}
                    </h3>
                    <div style="display: grid; gap: 0.6rem;">
                        @foreach($sampleCustomers as $c)
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; font-size: 0.88rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(148, 163, 184, 0.12);">
                                <span style="color: #f8fafc; font-weight: 700;">{{ $c['name'] }}</span>
                                <span style="color: #c4b5fd; font-weight: 700;">{{ $c['stamps'] }}</span>
                                <span style="color: {{ str_contains($c['status'], 'جاهزة') || str_contains($c['status'], 'ready') ? '#6ee7b7' : '#cbd5e1' }}; font-size: 0.82rem;">{{ $c['status'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Stamp & Transaction History --}}
                <div style="padding: 1.15rem; border-radius: 14px; background: #0C1742; border: 1px solid rgba(148, 163, 184, 0.18);">
                    <h3 style="margin: 0 0 0.85rem; font-size: 0.98rem; font-weight: 800; color: #f8fafc;">
                        {{ $isAr ? 'سجل الأختام والمعاملات' : 'Stamp & Transaction History' }}
                    </h3>
                    <div style="display: grid; gap: 0.65rem;">
                        @foreach($sampleTransactions as $tx)
                            <div style="font-size: 0.88rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(148, 163, 184, 0.12);">
                                <div style="display: flex; justify-content: space-between; gap: 0.5rem;">
                                    <strong style="color: #f8fafc;">{{ $tx['customer'] }}</strong>
                                    <span style="color: #94a3b8; font-size: 0.82rem;">{{ $tx['time'] }}</span>
                                </div>
                                <div style="color: #6ee7b7; font-size: 0.84rem; margin-top: 0.15rem;">{{ $tx['action'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

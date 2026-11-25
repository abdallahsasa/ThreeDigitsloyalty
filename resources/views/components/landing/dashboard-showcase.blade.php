@php
    $isAr = app()->getLocale() === 'ar';
    $langSuffix = $isAr ? 'ar' : 'en';
@endphp

<style>
    .td-split-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.25rem;
        align-items: center;
    }
    @media (min-width: 920px) {
        .td-split-row {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr);
            gap: 3.25rem;
        }
        .td-split-row.td-reverse > :first-child {
            order: 2;
        }
        .td-split-row.td-reverse > :last-child {
            order: 1;
        }
    }
    .td-collage-img {
        width: 100%;
        max-width: 520px;
        height: auto;
        display: block;
        margin: 0 auto;
    }
</style>

<section id="merchant-tools" class="dl-section" style="background: #010A29;">
    <span id="features" aria-hidden="true"></span>
    <div class="dl-container">
        <div class="dl-section-head" style="margin-bottom: 3.5rem;">
            <span class="dl-eyebrow">{{ $isAr ? 'أدوات المتجر وتجربة العميل' : 'Merchant Tools & Platform' }}</span>
            <h2>{{ $isAr ? 'صمّم بطاقة متجرك، أضف الأختام في ثوانٍ، وتابع عودة عملائك' : 'Create your branded card, stamp visits in seconds, and track repeat guests' }}</h2>
            <p>{{ $isAr ? 'شاشات حقيقية ونماذج توضيحية من منصة ThreeDigits Loyalty لإدارة الولاء في فرع واحد أو عدة فروع.' : 'Real product screens and illustrative workflow previews built for busy cafés, restaurants, and salons.' }}</p>
        </div>

        {{-- Split Row 1: Branded Card, QR Poster & Customer Return (Collage 2 + Real QR/Join Strip) --}}
        <div class="td-split-row" style="margin-bottom: 4.5rem;">
            <div>
                <div style="font-size: 0.84rem; font-weight: 800; color: #c4b5fd; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.55rem;">
                    {{ $isAr ? '١ · انضمام فوري وبطاقة بهوية متجرك' : '01 · Instant QR Join & Branded Card' }}
                </div>
                <h3 style="font-size: clamp(1.4rem, 2.5vw, 1.95rem); font-weight: 800; color: #f8fafc; margin: 0 0 0.85rem; line-height: 1.3;">
                    {{ $isAr ? 'بطاقة رقمية يفتحها العميل في المتصفح دون تحميل تطبيق' : 'Customers scan your QR and open their card right in the browser' }}
                </h3>
                <p style="color: #cbd5e1; font-size: 0.98rem; line-height: 1.75; margin: 0 0 1.35rem;">
                    {{ $isAr
                        ? 'اضبط شعار متجرك وعدد الأختام المطلوبة والمكافأة، واطبع ملصق QR الجاهز بملف PDF لعرضه عند الكاشير. يسجّل العميل اسمه ورقم جواله خلال ثوانٍ وتظهر بطاقته فوراً.'
                        : 'Set your stamp goal and reward, then print your ready-made PDF QR poster. Guests register with their name and mobile number in seconds—no app store friction.' }}
                </p>

                {{-- Verified Real Product Screenshots Mini Strip --}}
                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.85rem; background: #071033; border: 1px solid rgba(148, 163, 184, 0.2); border-radius: 18px; padding: 0.95rem;">
                    <figure style="margin: 0; display: flex; align-items: center; gap: 0.7rem;">
                        <img src="{{ asset('images/landing/ui-qr-poster.webp') }}"
                             alt="{{ $isAr ? 'شاشة ملصق QR المتجر الجاهز للطباعة' : 'Store QR poster screen inside ThreeDigits' }}"
                             width="273"
                             height="592"
                             loading="lazy"
                             style="width: 54px; height: auto; border-radius: 8px; flex-shrink: 0; border: 1px solid rgba(196, 181, 253, 0.25);">
                        <figcaption style="font-size: 0.82rem; color: #e2e8f0; font-weight: 700; line-height: 1.4;">
                            {{ $isAr ? 'ملصق QR جاهز للطباعة بملف PDF' : 'Print-ready counter QR poster (PDF)' }}
                        </figcaption>
                    </figure>
                    <figure style="margin: 0; display: flex; align-items: center; gap: 0.7rem;">
                        <img src="{{ asset('images/landing/ui-customer-join.webp') }}"
                             alt="{{ $isAr ? 'شاشة تسجيل العميل في 15 ثانية' : '15-second customer browser signup screen' }}"
                             width="276"
                             height="522"
                             loading="lazy"
                             style="width: 54px; height: auto; border-radius: 8px; flex-shrink: 0; border: 1px solid rgba(196, 181, 253, 0.25);">
                        <figcaption style="font-size: 0.82rem; color: #e2e8f0; font-weight: 700; line-height: 1.4;">
                            {{ $isAr ? 'تسجيل سريع في المتصفح بالعربية والإنجليزية' : '15-second bilingual browser signup' }}
                        </figcaption>
                    </figure>
                </div>
            </div>

            <div>
                <img src="{{ asset("images/landing/collage-engagement-{$langSuffix}.webp") }}"
                     alt="{{ $isAr ? 'تصميم يوضّح إشعار التقدم وبطاقة العميل الرقمية ومعدل عودة الزبائن (بيانات توضيحية)' : 'ThreeDigits customer engagement collage showing QR scan, card progress notification, and live digital stamp card (illustrative metrics)' }}"
                     width="840"
                     height="840"
                     loading="lazy"
                     class="td-collage-img">
            </div>
        </div>

        {{-- Split Row 2 (Reversed): Store Growth, Phone Lookup & Multi-Branch Management (Collage 1) --}}
        <div class="td-split-row td-reverse">
            <div>
                <div style="font-size: 0.84rem; font-weight: 800; color: #c4b5fd; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.55rem;">
                    {{ $isAr ? '٢ · إدارة الفروع ونمو المتجر' : '02 · Multi-Branch Visibility & Control' }}
                </div>
                <h3 style="font-size: clamp(1.4rem, 2.5vw, 1.95rem); font-weight: 800; color: #f8fafc; margin: 0 0 0.85rem; line-height: 1.3;">
                    {{ $isAr ? 'تابع العملاء المسجلين والأختام اليومية عبر جميع فروعك' : 'See enrolled customers, issued stamps, and branch activity at a glance' }}
                </h3>
                <p style="color: #cbd5e1; font-size: 0.98rem; line-height: 1.75; margin: 0 0 1.35rem;">
                    {{ $isAr
                        ? 'سواء كنت تدير مقهى واحداً أو سلسلة فروع، يبحث الكاشير برقم الجوال أو يمسح QR العميل لإضافة الختم وصرف المكافأة مع صلاحيات مستقلة وعزل تام لبيانات متجرك.'
                        : 'Whether you run a single neighborhood café or multiple locations, staff can scan a QR or look up a phone number to stamp and redeem—with dedicated Branch Manager and Cashier roles and isolated store data.' }}
                </p>

                <div style="display: inline-flex; align-items: center; gap: 0.6rem; padding: 0.5rem 0.95rem; border-radius: 999px; background: rgba(148, 163, 184, 0.12); border: 1px solid rgba(148, 163, 184, 0.24); color: #cbd5e1; font-size: 0.82rem; font-weight: 700;">
                    <span>{{ $isAr ? 'الأرقام المعروضة في البطاقات التوضيحية هي بيانات نموذجية للمعاينة.' : 'Numbers shown in preview graphics are illustrative sample data.' }}</span>
                </div>
            </div>

            <div>
                <img src="{{ asset("images/landing/collage-growth-{$langSuffix}.webp") }}"
                     alt="{{ $isAr ? 'صورة صاحب متجر مع بطاقات توضيحية لعدد العملاء المسجلين والأختام المصدرة' : 'Store owner portrait with illustrative ThreeDigits cards for enrolled customers and stamps issued' }}"
                     width="840"
                     height="840"
                     loading="lazy"
                     class="td-collage-img">
            </div>
        </div>
    </div>
</section>

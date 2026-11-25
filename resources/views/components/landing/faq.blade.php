@php
    $isAr = app()->getLocale() === 'ar';
    $faqs = $isAr ? [
        ['q' => 'هل يحتاج العميل إلى تحميل تطبيق؟', 'a' => 'لا. يمسح العميل رمز QR بكاميرا هاتفه فتفتح بطاقته الرقمية مباشرة في المتصفح، ويمكنه أيضاً حفظها اختيارياً في Apple Wallet أو Google Wallet.'],
        ['q' => 'هل يلزم إدخال بطاقة دفع للاستفادة من العرض المجاني؟', 'a' => 'لا، التسجيل مجاني بالكامل حتى 31 ديسمبر 2026 بدون بطاقة دفع، ولا يتم أي خصم تلقائي بعد انتهاء الفترة المجانية.'],
        ['q' => 'ماذا يحدث في 1 يناير 2027 بعد انتهاء العرض المجاني؟', 'a' => 'لا يتم خصم أي رسوم تلقائياً لعدم وجود بطاقة دفع مسجلة، ولا يوجد إيقاف تلقائي للحسابات. تبقى بيانات متجرك وبطاقات العملاء محفوظة، ويمكن للعملاء فتح بطاقاتهم وللمتاجر تسجيل الدخول وإضافة الأختام وصرف المكافآت القائمة، على أن يتم إشعارك مسبقاً بتفاصيل التجديد الاختياري قبل تفعيل أي فوترة.'],
        ['q' => 'كيف يضيف الكاشير الأختام ويصرف المكافأة؟', 'a' => 'من خلال صفحة ماسح QR داخل لوحة المتجر، أو عبر البحث برقم جوال العميل لإضافة الختم أو صرف المكافأة المستحقة.'],
        ['q' => 'هل يمكنني إدارة أكثر من فرع وموظف؟', 'a' => 'نعم، تدعم المنصة تعدد الفروع وتعيين مدراء فروع وكاشير بصلاحيات محددة، مع فصل كامل لبيانات متجرك عن المتاجر الأخرى.'],
    ] : [
        ['q' => 'Do customers need to download an app?', 'a' => 'No. Customers scan your counter QR code to open their digital stamp card in their mobile browser, and they can also optionally save it to Apple Wallet or Google Wallet.'],
        ['q' => 'Is a payment card required for the free launch offer?', 'a' => 'No. All plans are free until December 31, 2026 with no payment card required, and billing does not start automatically afterward.'],
        ['q' => 'What happens on January 1, 2027 after the launch offer ends?', 'a' => 'Because no payment card is collected, you will never be charged automatically and accounts are not automatically suspended. Store data and customer cards remain intact—customers can still open their cards, and merchants can still sign in, add stamps, and redeem existing rewards while optional renewal terms are shared ahead of time before any paid billing starts.'],
        ['q' => 'How does my staff add stamps or redeem a reward?', 'a' => 'Using the built-in QR Scanner inside your merchant dashboard, or by looking up the customer’s mobile number at checkout.'],
        ['q' => 'Can I manage multiple branches and staff roles?', 'a' => 'Yes. You can add multiple branches, assign Branch Manager and Cashier permissions, and keep your store’s data isolated from other businesses.'],
    ];
@endphp

<section id="faq" class="dl-section" style="background: #FAF8F5; color: #0F172A; border-top: 1px solid #E7E2DA; border-bottom: 1px solid #E7E2DA;">
    <div class="dl-container" style="max-width: 820px;">
        <div class="dl-section-head">
            <span class="dl-eyebrow" style="background: #EFEAFE; border-color: rgba(116, 62, 226, 0.25); color: #5B21B6;">FAQ</span>
            <h2 style="color: #0F172A;">{{ __('landing.faq_title') }}</h2>
        </div>

        <div style="display: grid; gap: 0.85rem;">
            @foreach($faqs as $idx => $faq)
                <div x-data="{ open: {{ $idx === 0 ? 'true' : 'false' }} }"
                     style="background: #FFFFFF; border: 1px solid #E5E0D8; border-radius: 18px; padding: 1.15rem 1.35rem; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);">
                    <button type="button"
                            @click="open = !open"
                            :aria-expanded="open.toString()"
                            style="width: 100%; background: none; border: none; color: #0F172A; font-family: inherit; font-size: 1rem; font-weight: 800; display: flex; justify-content: space-between; align-items: center; gap: 1rem; cursor: pointer; text-align: start;">
                        <span>{{ $faq['q'] }}</span>
                        <span x-text="open ? '−' : '+'" style="color: #5B21B6; font-size: 1.35rem; font-weight: 800; flex-shrink: 0;"></span>
                    </button>
                    <p x-show="open" x-transition style="margin: 0.75rem 0 0; color: #334155; font-size: 0.94rem; line-height: 1.7;">
                        {{ $faq['a'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

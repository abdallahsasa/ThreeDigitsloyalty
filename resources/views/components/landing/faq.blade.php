@php
    $isAr = app()->getLocale() === 'ar';
    $faqs = $isAr ? [
        ['q' => 'هل يحتاج العميل إلى تحميل تطبيق؟', 'a' => 'لا. يفتح العميل بطاقته الرقمية ورمز QR الخاص به مباشرة في متصفح الهاتف بدون تحميل أي تطبيق.'],
        ['q' => 'هل يلزم إدخال بطاقة دفع للاستفادة من العرض المجاني؟', 'a' => 'لا، التسجيل مجاني بالكامل حتى 31 ديسمبر 2026 بدون بطاقة دفع، ولا يتم أي خصم تلقائي بعد انتهاء الفترة المجانية.'],
        ['q' => 'ماذا يحدث بعد 31 ديسمبر 2026؟', 'a' => 'لا يتم خصم أي رسوم تلقائياً لعدم وجود بطاقة دفع مسجلة. سيتم إشعارك مسبقاً بتفاصيل وآلية التجديد الاختياري عند تفعيل الفوترة، مع الحفاظ على بيانات متجرك وبطاقات العملاء دون إلغاء مفاجئ.'],
        ['q' => 'هل يمكنني إدارة أكثر من فرع وموظف؟', 'a' => 'نعم، يدعم النظام تعدد الفروع وتعيين مدراء فروع وكاشير بصلاحيات محددة لكل فرع.'],
        ['q' => 'كيف يضيف الكاشير الأختام للعميل؟', 'a' => 'من خلال صفحة ماسح QR داخل لوحة المتجر، أو عبر البحث برقم هاتف العميل.'],
        ['q' => 'هل بيانات متجري مفصولة عن المتاجر الأخرى؟', 'a' => 'نعم، بيانات متجرك منفصلة عن بيانات المتاجر الأخرى، ولا يطّلع أي متجر على عملاء أو معاملات متجر آخر.'],
    ] : [
        ['q' => 'Do customers need to download an app?', 'a' => 'No. Customers open their digital stamp card and QR code directly in their phone browser with no app download required.'],
        ['q' => 'Is a payment card required for the free launch offer?', 'a' => 'No. All plans are free until December 31, 2026 with no payment card required, and billing does not start automatically afterward.'],
        ['q' => 'What happens after December 31, 2026?', 'a' => 'Because no payment card is collected, you will never be charged automatically on January 1, 2027. Optional renewal and continuation terms will be shared with merchants ahead of time, without automatic cancellation or surprise billing.'],
        ['q' => 'Can I manage multiple branches and staff?', 'a' => 'Yes. You can create multiple branches and assign Branch Manager and Cashier permissions per store.'],
        ['q' => 'How does a cashier stamp or redeem a card?', 'a' => 'Using the built-in QR Scanner inside your merchant dashboard, or by searching the customer’s phone number.'],
        ['q' => 'Is my store’s data kept private?', 'a' => 'Yes. Your store’s data is kept separate from other stores and cannot be viewed by other merchants.'],
    ];
@endphp

<section id="faq" class="dl-section">
    <div class="dl-container" style="max-width: 820px;">
        <div class="dl-section-head">
            <span class="dl-eyebrow">FAQ</span>
            <h2>{{ __('landing.faq_title') }}</h2>
        </div>

        <div style="display: grid; gap: 0.85rem;">
            @foreach($faqs as $idx => $faq)
                <div x-data="{ open: {{ $idx === 0 ? 'true' : 'false' }} }" class="dl-card" style="padding: 1.15rem 1.35rem;">
                    <button type="button"
                            @click="open = !open"
                            :aria-expanded="open.toString()"
                            style="width: 100%; background: none; border: none; color: #f8fafc; font-family: inherit; font-size: 1rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center; gap: 1rem; cursor: pointer; text-align: start;">
                        <span>{{ $faq['q'] }}</span>
                        <span x-text="open ? '−' : '+'" style="color: #c4b5fd; font-size: 1.25rem; font-weight: 800; flex-shrink: 0;"></span>
                    </button>
                    <p x-show="open" x-transition style="margin: 0.75rem 0 0; color: #cbd5e1; font-size: 0.92rem; line-height: 1.65;">
                        {{ $faq['a'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

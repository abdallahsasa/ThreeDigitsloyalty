<section class="dl-section" style="background: #040D30;">
    <div class="dl-container" style="max-width: 820px;">
        <div class="dl-section-head">
            <span class="dl-eyebrow">FAQ</span>
            <h2>{{ __('landing.faq_title') }}</h2>
        </div>

        @php
            $faqs = app()->getLocale() === 'ar' ? [
                ['q' => 'هل يحتاج العميل إلى تحميل تطبيق؟', 'a' => 'لا، يحصل العميل على رابط بطاقة رقمية يعمل مباشرة عبر المتصفح مع رمز QR.'],
                ['q' => 'هل يمكنني إدارة أكثر من فرع؟', 'a' => 'نعم، يدعم النظام تعدد الفروع وتعيين مدراء وكاشير لكل فرع بشكل مستقل.'],
                ['q' => 'كيف يضيف الكاشير الأختام للعميل؟', 'a' => 'من خلال صفحة ماسح QR في لوحة المتجر، أو عبر البحث برقم هاتف العميل.'],
            ] : [
                ['q' => 'Do customers need to download an app?', 'a' => 'No. Each customer gets an instant web pass with a unique QR code that works on any smartphone.'],
                ['q' => 'Can I manage multiple store branches?', 'a' => 'Yes. You can create multiple branches, assign branch managers, and scope cashiers per location.'],
                ['q' => 'How does a cashier stamp or redeem a card?', 'a' => 'Using the built-in QR Scanner page in the Merchant Portal, or by looking up the customer’s phone number.'],
            ];
        @endphp

        <div style="display: grid; gap: 0.9rem;">
            @foreach($faqs as $idx => $faq)
                <div x-data="{ open: {{ $idx === 0 ? 'true' : 'false' }} }" class="dl-card" style="padding: 1.25rem 1.5rem;">
                    <button type="button" @click="open = !open" style="width: 100%; background: none; border: none; color: #f8fafc; font-family: inherit; font-size: 1rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center; cursor: pointer; text-align: start;">
                        <span>{{ $faq['q'] }}</span>
                        <span x-text="open ? '−' : '+'" style="color: #743EE2; font-size: 1.25rem; font-weight: 700;"></span>
                    </button>
                    <p x-show="open" x-transition style="margin: 0.85rem 0 0; color: #94a3b8; font-size: 0.92rem; line-height: 1.65;">
                        {{ $faq['a'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

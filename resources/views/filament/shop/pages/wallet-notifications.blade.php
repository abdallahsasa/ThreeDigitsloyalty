<x-filament-panels::page>
    @php $isAr = app()->getLocale() === 'ar'; @endphp

    <div style="display: grid; gap: 1.5rem; max-width: 820px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1rem;">
            <x-filament::section>
                <div style="font-size: 0.84rem; color: #94a3b8;">{{ $isAr ? 'إجمالي بطاقات العملاء' : 'Active Customer Cards' }}</div>
                <div style="font-size: 1.75rem; font-weight: 800; margin-top: 0.25rem;">{{ $totalCards }}</div>
            </x-filament::section>

            <x-filament::section>
                <div style="font-size: 0.84rem; color: #94a3b8;">{{ $isAr ? 'أجهزة iPhone المسجلة' : 'Apple Wallet Devices' }}</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #a78bfa; margin-top: 0.25rem;">{{ $appleDevices }}</div>
            </x-filament::section>

            <x-filament::section>
                <div style="font-size: 0.84rem; color: #94a3b8;">{{ $isAr ? 'فروع بموقع GPS (تنبيه القرب)' : 'Geo-Fenced Branches' }}</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #10b981; margin-top: 0.25rem;">{{ $geoBranches }}</div>
            </x-filament::section>
        </div>

        {{-- One-Click Stamp Completion Reminders --}}
        <x-filament::section>
            <x-slot name="heading">
                {{ $isAr ? 'تذكير إكمال الأختام (تلقائي لكل عميل)' : 'Stamp Completion Reminders (Personalized)' }}
            </x-slot>
            <x-slot name="description">
                {{ $isAr
                    ? 'يرسل لكل عميل لديه بطاقة غير مكتملة رسالة بعدد الأختام المتبقية له (مثلاً: متبقي لك 3 أختام للحصول على قهوتك المجانية).'
                    : 'Sends each customer with an incomplete card a personalized reminder with their remaining stamps (e.g. 3 left for your next free coffee).' }}
            </x-slot>

            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem;">
                <div style="font-size: 0.9rem; color: #cbd5e1;">
                    {{ $isAr ? "عدد البطاقات قيد التجميع حالياً: {$incompleteCards}" : "Cards currently collecting stamps: {$incompleteCards}" }}
                </div>

                <button type="button"
                        wire:click="sendStampReminders"
                        wire:loading.attr="disabled"
                        style="padding: 0.7rem 1.35rem; border-radius: 10px; border: none; background: #10b981; color: #ffffff; font-weight: 700; cursor: pointer;">
                    <span wire:loading.remove wire:target="sendStampReminders">
                        {{ $isAr ? 'إرسال تذكير إكمال الأختام الآن' : 'Send Stamp Reminders Now' }}
                    </span>
                    <span wire:loading wire:target="sendStampReminders">
                        {{ $isAr ? 'جاري الإرسال...' : 'Sending...' }}
                    </span>
                </button>
            </div>
        </x-filament::section>

        {{-- Nearby Lock-Screen Geo-Notifications Info --}}
        <x-filament::section>
            <x-slot name="heading">
                {{ $isAr ? 'إشعارات القرب من المتجر (Geo-Location)' : 'Nearby Store Lock-Screen Alerts (Geo-Location)' }}
            </x-slot>
            <p style="margin: 0 0 0.85rem; font-size: 0.9rem; color: #cbd5e1; line-height: 1.7;">
                {{ $isAr
                    ? 'عند إضافة إحداثيات الموقع (Latitude / Longitude) ورسالة القرب في صفحة الفروع، يظهر إشعار تلقائي على شاشة قفل العميل فور اقترابه من فرعك (ضمن 150 متر تقريباً).'
                    : 'When you add GPS coordinates (Latitude / Longitude) and a nearby message to your branches, Apple Wallet and Google Wallet automatically show a lock-screen alert when customers walk near your store.' }}
            </p>
            @if($shop)
                <a href="{{ url("/shop/{$shop->id}/branches") }}"
                   style="display: inline-block; padding: 0.55rem 1.15rem; border-radius: 10px; border: 1px solid rgba(167,139,250,0.4); color: #c4b5fd; text-decoration: none; font-size: 0.86rem; font-weight: 700;">
                    {{ $isAr ? 'تعديل مواقع الفروع ورسائل القرب' : 'Configure Branch Locations & Nearby Messages' }}
                </a>
            @endif
        </x-filament::section>

        {{-- Custom Broadcast Notification --}}
        <x-filament::section>
            <x-slot name="heading">
                {{ $isAr ? 'إرسال إشعار مخصص لمحفظة العملاء' : 'Send Custom Wallet Notification' }}
            </x-slot>

            <form wire:submit.prevent="sendBroadcast" style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.88rem; font-weight: 700; margin-bottom: 0.4rem;">
                        {{ $isAr ? 'عنوان الإشعار' : 'Notification Title' }}
                    </label>
                    <input type="text"
                           wire:model="titleText"
                           required
                           maxlength="80"
                           style="width: 100%; padding: 0.7rem 0.9rem; border-radius: 10px; border: 1px solid rgba(148,163,184,0.3); background: transparent;">
                    @error('titleText') <div style="color: #f87171; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label style="display: block; font-size: 0.88rem; font-weight: 700; margin-bottom: 0.4rem;">
                        {{ $isAr ? 'نص الرسالة' : 'Message' }}
                    </label>
                    <textarea wire:model="messageText"
                              rows="3"
                              required
                              maxlength="240"
                              placeholder="{{ $isAr ? 'مثال: عرض اليوم! خصم 20% لحاملي بطاقة الولاء.' : 'e.g. Today only: 20% off for loyalty card holders!' }}"
                              style="width: 100%; padding: 0.7rem 0.9rem; border-radius: 10px; border: 1px solid rgba(148,163,184,0.3); background: transparent;"></textarea>
                    @error('messageText') <div style="color: #f87171; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                </div>

                <div>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            style="padding: 0.75rem 1.5rem; border-radius: 10px; border: none; background: #743EE2; color: #ffffff; font-weight: 700; cursor: pointer;">
                        <span wire:loading.remove wire:target="sendBroadcast">
                            {{ $isAr ? 'إرسال الإشعار الآن' : 'Send Wallet Notification' }}
                        </span>
                        <span wire:loading wire:target="sendBroadcast">
                            {{ $isAr ? 'جاري الإرسال...' : 'Sending...' }}
                        </span>
                    </button>
                </div>
            </form>
        </x-filament::section>
    </div>
</x-filament-panels::page>

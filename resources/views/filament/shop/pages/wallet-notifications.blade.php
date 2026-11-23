<x-filament-panels::page>
    @php $isAr = app()->getLocale() === 'ar'; @endphp

    <div style="display: grid; gap: 1.5rem; max-width: 760px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1rem;">
            <x-filament::section>
                <div style="font-size: 0.84rem; color: #94a3b8;">{{ $isAr ? 'إجمالي بطاقات العملاء' : 'Active Customer Cards' }}</div>
                <div style="font-size: 1.75rem; font-weight: 800; margin-top: 0.25rem;">{{ $totalCards }}</div>
            </x-filament::section>

            <x-filament::section>
                <div style="font-size: 0.84rem; color: #94a3b8;">{{ $isAr ? 'أجهزة iPhone المسجلة (Apple Wallet)' : 'Registered Apple Wallet Devices' }}</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #a78bfa; margin-top: 0.25rem;">{{ $appleDevices }}</div>
            </x-filament::section>
        </div>

        <x-filament::section>
            <x-slot name="heading">
                {{ $isAr ? 'إرسال إشعار لمحفظة العملاء (iPhone & Android)' : 'Send Notification to Customer Wallets (iPhone & Android)' }}
            </x-slot>
            <x-slot name="description">
                {{ $isAr
                    ? 'يصل الإشعار مباشرة إلى شاشة قفل العملاء الذين أضافوا بطاقتك في Apple Wallet أو Google Wallet.'
                    : 'Sends a push notification directly to customers who saved your card in Apple Wallet or Google Wallet.' }}
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
                              placeholder="{{ $isAr ? 'مثال: عرض اليوم! احصل على ختم إضافي عند طلبك اليوم.' : 'e.g. Today only: earn a bonus stamp with your order!' }}"
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

<x-filament-widgets::widget>
    @php $isAr = app()->getLocale() === 'ar'; @endphp

    <x-filament::section>
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.25rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(148, 163, 184, 0.2);">
            <div>
                <div style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #743EE2;">
                    {{ $isAr ? 'نقطة بيع الكاشير (POS)' : 'Cashier Terminal' }}
                </div>
                <h2 style="font-size: 1.45rem; font-weight: 800; margin: 0.25rem 0;">
                    {{ $isAr ? 'مرحباً، ' . auth()->user()->name : 'Welcome back, ' . auth()->user()->name }}
                </h2>
                <p style="font-size: 0.88rem; color: #64748b; margin: 0;">
                    {{ $isAr ? 'امسح بطاقات العملاء لإضافة الأختام أو استبدال المكافآت الفورية.' : 'Scan customer QR passes to issue stamps or redeem unlocked rewards.' }}
                </p>
            </div>

            <x-filament::button
                size="xl"
                tag="a"
                href="{{ \App\Filament\Shop\Pages\QRScanner::getUrl() }}"
                icon="heroicon-o-qr-code"
                color="primary"
            >
                {{ $isAr ? 'فتح ماسح رمز QR' : 'Launch QR Scanner' }}
            </x-filament::button>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; padding-top: 1.25rem;">
            <div style="padding: 1.15rem; border-radius: 16px; border: 1px solid rgba(148, 163, 184, 0.2); background: rgba(116, 62, 226, 0.04);">
                <div style="font-size: 0.78rem; font-weight: 700; color: #64748b;">
                    {{ $isAr ? 'بطاقات تم مسحها اليوم' : 'Cards Scanned Today' }}
                </div>
                <div style="font-size: 1.9rem; font-weight: 800; margin-top: 0.35rem;">
                    {{ $scanned }}
                </div>
            </div>

            <div style="padding: 1.15rem; border-radius: 16px; border: 1px solid rgba(148, 163, 184, 0.2); background: rgba(116, 62, 226, 0.04);">
                <div style="font-size: 0.78rem; font-weight: 700; color: #64748b;">
                    {{ $isAr ? 'أختام أضيفت اليوم' : 'Stamps Issued Today' }}
                </div>
                <div style="font-size: 1.9rem; font-weight: 800; color: #743EE2; margin-top: 0.35rem;">
                    {{ $purchases }}
                </div>
            </div>

            <div style="padding: 1.15rem; border-radius: 16px; border: 1px solid rgba(148, 163, 184, 0.2); background: rgba(16, 185, 129, 0.05);">
                <div style="font-size: 0.78rem; font-weight: 700; color: #64748b;">
                    {{ $isAr ? 'مكافآت تم استبدالها اليوم' : 'Rewards Redeemed Today' }}
                </div>
                <div style="font-size: 1.9rem; font-weight: 800; color: #10b981; margin-top: 0.35rem;">
                    {{ $rewards }}
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

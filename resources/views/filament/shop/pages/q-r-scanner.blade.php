<x-filament-panels::page>
    @php $isAr = app()->getLocale() === 'ar'; @endphp

    <style>
        .td-pos-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            align-items: start;
        }
        @media (min-width: 960px) {
            .td-pos-grid {
                grid-template-columns: 420px 1fr;
            }
        }
        .td-pass-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-bottom: 1.15rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }
        .td-customer-row {
            display: flex;
            align-items: center;
            gap: 0.9rem;
        }
        .td-avatar {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #743EE2, #3B42D5);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.15rem;
            flex-shrink: 0;
        }
        .td-stamp-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.85rem;
        }
        .td-stamp-box {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 800;
        }
        .td-stamp-box.filled {
            background: linear-gradient(135deg, #743EE2, #3B42D5);
            color: #ffffff;
        }
        .td-stamp-box.ready {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
        }
        .td-stamp-box.empty {
            border: 1.5px dashed rgba(148, 163, 184, 0.45);
            color: #94a3b8;
        }
        .td-progress-bar {
            width: 100%;
            height: 9px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.2);
            overflow: hidden;
            margin-top: 0.75rem;
        }
        .td-progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #743EE2, #3B42D5);
            transition: width 0.3s ease;
        }
    </style>

    <div class="td-pos-grid">
        {{-- Left Column: POS Lookup Controls --}}
        <div style="display: grid; gap: 1.25rem;">
            <x-filament::section>
                <x-slot name="heading">
                    {{ $isAr ? 'البحث عن بطاقة العميل (POS Customer Lookup)' : 'POS Customer Lookup' }}
                </x-slot>
                <x-slot name="description">
                    {{ $isAr ? 'امسح رمز QR الخاص بالعميل أو ابحث برقم الجوال لإضافة ختم أو استبدال مكافأة.' : 'Scan a customer QR token or enter their phone number to stamp or redeem.' }}
                </x-slot>

                <form wire:submit.prevent="searchCard" style="display: grid; gap: 1rem;">
                    {{ $this->form }}
                </form>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    {{ $isAr ? 'إرشادات سريعة للكاشير' : 'Quick Tips' }}
                </x-slot>
                <ul style="margin: 0; padding-inline-start: 1.2rem; font-size: 0.86rem; line-height: 1.8; color: #64748b;">
                    <li>{{ $isAr ? 'ضع المؤشر في حقل مسح QR عند استخدام قارئ الباركود.' : 'Keep the Scan QR Code field focused when using a barcode scanner.' }}</li>
                    <li>{{ $isAr ? 'يمكنك البحث برقم جوال العميل في حال عدم توفر الرمز.' : 'If a customer forgot their pass, search by their phone number above.' }}</li>
                    <li>{{ $isAr ? 'يجب استبدال المكافأة الجاهزة قبل بدء تجميع أختام جديدة.' : 'Unlocked rewards must be redeemed before new stamps are added.' }}</li>
                </ul>
            </x-filament::section>
        </div>

        {{-- Right Column: Active Loyalty Pass & Transactions --}}
        <div style="display: grid; gap: 1.25rem;">
            @if($scannedCard)
                @php
                    $requiredStamps = max(1, (int) ($scannedCard->campaign->required_purchases_count ?? 1));
                    $progress = min(100, ($scannedCard->current_stamps_count / $requiredStamps) * 100);
                    $isReady = $scannedCard->reward_status === 'ready';
                @endphp

                <x-filament::section>
                    <div class="td-pass-header">
                        <div class="td-customer-row">
                            <div class="td-avatar">
                                {{ mb_substr($scannedCard->customer->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-size: 1.1rem; font-weight: 800;">
                                    {{ $scannedCard->customer->name }}
                                </div>
                                <div style="font-size: 0.84rem; color: #64748b;">
                                    {{ $scannedCard->customer->phone }} · {{ $scannedCard->campaign->name }}
                                </div>
                            </div>
                        </div>

                        <x-filament::badge size="lg" :color="$isReady ? 'success' : 'warning'">
                            {{ $isReady ? ($isAr ? 'مكافأة جاهزة' : 'Reward Ready') : ($isAr ? 'قيد التجميع' : 'Collecting') }}
                        </x-filament::badge>
                    </div>

                    <div style="padding-top: 1.15rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.88rem;">
                            <span>
                                {{ $isAr ? 'المكافأة:' : 'Reward:' }} <strong>{{ $scannedCard->campaign->reward_name }}</strong>
                            </span>
                            <strong style="color: #743EE2;">
                                {{ $scannedCard->current_stamps_count }} / {{ $requiredStamps }} {{ $isAr ? 'أختام' : 'Stamps' }}
                            </strong>
                        </div>

                        <div class="td-progress-bar">
                            <div class="td-progress-fill" style="width: {{ $progress }}%; {{ $isReady ? 'background: linear-gradient(90deg, #10b981, #059669);' : '' }}"></div>
                        </div>

                        <div class="td-stamp-row">
                            @for($i = 1; $i <= min($requiredStamps, 20); $i++)
                                @php $filled = $i <= $scannedCard->current_stamps_count; @endphp
                                <span class="td-stamp-box {{ $filled ? ($isReady ? 'ready' : 'filled') : 'empty' }}">
                                    {{ $filled ? '✓' : $i }}
                                </span>
                            @endfor
                        </div>
                    </div>

                    <x-slot name="footerActions">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                            {{ $this->addPurchaseAction }}
                            {{ $this->redeemRewardAction }}
                            {{ $this->clearAction }}
                        </div>
                    </x-slot>
                </x-filament::section>

                @if($scannedCard->transactions && $scannedCard->transactions->count() > 0)
                    <x-filament::section>
                        <x-slot name="heading">
                            {{ $isAr ? 'آخر الحركات على البطاقة' : 'Recent Card Activity' }}
                        </x-slot>

                        <div style="display: grid; gap: 0.65rem;">
                            @foreach($scannedCard->transactions as $transaction)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid rgba(148, 163, 184, 0.15);">
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.88rem;">
                                            {{ str($transaction->type)->replace('_', ' ')->title() }}
                                        </div>
                                        <div style="font-size: 0.78rem; color: #64748b;">
                                            {{ $transaction->created_at->format('M d, Y h:i A') }}
                                            @if($transaction->branch)
                                                · {{ $transaction->branch->name }}
                                            @endif
                                        </div>
                                    </div>
                                    <x-filament::badge :color="$transaction->type === 'reward_redeemed' ? 'success' : 'primary'">
                                        {{ $transaction->type === 'reward_redeemed' ? ($isAr ? 'استبدال مكافأة' : 'Redeemed') : '+1' }}
                                    </x-filament::badge>
                                </div>
                            @endforeach
                        </div>
                    </x-filament::section>
                @endif
            @else
                <x-filament::section>
                    <div style="padding: 3.5rem 1.5rem; text-align: center;">
                        <div style="width: 56px; height: 56px; margin: 0 auto 1rem; border-radius: 16px; background: rgba(116, 62, 226, 0.12); color: #743EE2; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            📷
                        </div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; margin: 0 0 0.4rem;">
                            {{ $isAr ? 'لم يتم اختيار بطاقة بعد' : 'No Card Loaded' }}
                        </h3>
                        <p style="font-size: 0.88rem; color: #64748b; max-width: 380px; margin: 0 auto;">
                            {{ $isAr
                                ? 'امسح رمز QR الخاص بالعميل أو ابحث برقم جواله من القائمة الجانبية لعرض الأختام والمكافآت.'
                                : 'Scan a customer QR code or search by phone number to view their stamp progress and issue rewards.' }}
                        </p>
                    </div>
                </x-filament::section>
            @endif
        </div>
    </div>
</x-filament-panels::page>

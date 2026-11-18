<x-filament-panels::page>
    @php $isAr = app()->getLocale() === 'ar'; @endphp

    <style>
        .td-pos-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1.5rem;
            align-items: start;
            width: 100%;
        }
        @media (min-width: 1080px) {
            .td-pos-grid {
                grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
            }
        }
        .td-pos-col {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            min-width: 0;
            width: 100%;
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
            min-width: 0;
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
            gap: 0.55rem;
            margin-top: 1rem;
        }
        .td-stamp-box {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 800;
        }
        .td-stamp-box.filled {
            background: #743EE2;
            color: #ffffff;
        }
        .td-stamp-box.ready {
            background: #10b981;
            color: #ffffff;
        }
        .td-stamp-box.empty {
            border: 1.5px dashed rgba(148, 163, 184, 0.5);
            color: #94a3b8;
        }
        .td-progress-bar {
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.22);
            overflow: hidden;
            margin-top: 0.75rem;
        }
        .td-progress-fill {
            height: 100%;
            border-radius: 999px;
            background: #743EE2;
            transition: width 0.3s ease;
        }
        .td-actions-bar {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(148, 163, 184, 0.2);
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
        }
        .td-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.35rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            border: 1px solid transparent;
            transition: opacity 0.15s ease, transform 0.15s ease;
        }
        .td-btn:hover { opacity: 0.92; }
        .td-btn:disabled { opacity: 0.55; cursor: not-allowed; }
        .td-btn-primary {
            background: #743EE2;
            color: #ffffff;
        }
        .td-btn-success {
            background: #10b981;
            color: #ffffff;
        }
        .td-btn-secondary {
            background: rgba(148, 163, 184, 0.14);
            border-color: rgba(148, 163, 184, 0.3);
            color: inherit;
        }
    </style>

    <div class="td-pos-grid">
        {{-- Left Column: POS Lookup Controls --}}
        <div class="td-pos-col">
            <x-filament::section>
                <x-slot name="heading">
                    {{ $isAr ? 'البحث عن بطاقة العميل' : 'POS Customer Lookup' }}
                </x-slot>
                <x-slot name="description">
                    {{ $isAr ? 'امسح رمز QR الخاص بالعميل أو ابحث برقم الجوال لإضافة ختم أو استبدال مكافأة.' : 'Scan a customer QR token or enter their phone number to stamp or redeem.' }}
                </x-slot>

                <form wire:submit.prevent="lookup" style="display: flex; flex-direction: column; gap: 1rem;">
                    {{ $this->form }}

                    <button type="submit" wire:loading.attr="disabled" class="td-btn td-btn-primary" style="width: 100%;">
                        <span wire:loading.remove wire:target="lookup,searchCard,searchCardByPhone">
                            {{ $isAr ? 'بحث عن البطاقة' : 'Find Customer Card' }}
                        </span>
                        <span wire:loading wire:target="lookup,searchCard,searchCardByPhone">
                            {{ $isAr ? 'جاري البحث...' : 'Searching...' }}
                        </span>
                    </button>
                </form>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    {{ $isAr ? 'إرشادات سريعة للكاشير' : 'Quick Tips' }}
                </x-slot>
                <ul style="margin: 0; padding-inline-start: 1.2rem; font-size: 0.88rem; line-height: 1.85; color: #94a3b8;">
                    <li>{{ $isAr ? 'ضع المؤشر في حقل مسح QR عند استخدام قارئ الباركود.' : 'Keep the Scan QR Code field focused when using a barcode scanner.' }}</li>
                    <li>{{ $isAr ? 'يمكنك البحث برقم جوال العميل في حال عدم توفر الرمز.' : 'If a customer forgot their pass, search by their phone number above.' }}</li>
                    <li>{{ $isAr ? 'يجب استبدال المكافأة الجاهزة قبل بدء تجميع أختام جديدة.' : 'Unlocked rewards must be redeemed before new stamps are added.' }}</li>
                </ul>
            </x-filament::section>
        </div>

        {{-- Right Column: Active Loyalty Pass & Action Buttons --}}
        <div class="td-pos-col">
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
                            <div style="min-width: 0;">
                                <div style="font-size: 1.15rem; font-weight: 800; word-break: break-word;">
                                    {{ $scannedCard->customer->name }}
                                </div>
                                <div style="font-size: 0.86rem; color: #94a3b8;">
                                    <span dir="ltr" style="unicode-bidi: isolate;">{{ $scannedCard->customer->phone }}</span>
                                    · {{ $scannedCard->campaign->name }}
                                </div>
                            </div>
                        </div>

                        <x-filament::badge size="lg" :color="$isReady ? 'success' : 'warning'">
                            {{ $isReady ? ($isAr ? 'مكافأة جاهزة' : 'Reward Ready') : ($isAr ? 'قيد التجميع' : 'Collecting') }}
                        </x-filament::badge>
                    </div>

                    <div style="padding-top: 1.25rem;">
                        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 0.5rem; font-size: 0.92rem;">
                            <span>
                                {{ $isAr ? 'المكافأة:' : 'Reward:' }} <strong>{{ $scannedCard->campaign->reward_name }}</strong>
                            </span>
                            <strong style="color: #a78bfa;">
                                {{ $scannedCard->current_stamps_count }} / {{ $requiredStamps }} {{ $isAr ? 'أختام' : 'Stamps' }}
                            </strong>
                        </div>

                        <div class="td-progress-bar">
                            <div class="td-progress-fill" style="width: {{ $progress }}%; {{ $isReady ? 'background: #10b981;' : '' }}"></div>
                        </div>

                        <div class="td-stamp-row">
                            @for($i = 1; $i <= min($requiredStamps, 20); $i++)
                                @php $filled = $i <= $scannedCard->current_stamps_count; @endphp
                                <span class="td-stamp-box {{ $filled ? ($isReady ? 'ready' : 'filled') : 'empty' }}">
                                    {{ $filled ? '✓' : $i }}
                                </span>
                            @endfor
                        </div>

                        {{-- Prominent Cashier Action Buttons --}}
                        <div class="td-actions-bar">
                            @if(!$isReady)
                                <button type="button"
                                        wire:click="addPurchase"
                                        wire:loading.attr="disabled"
                                        class="td-btn td-btn-primary"
                                        style="flex: 1; min-width: 190px;">
                                    <span wire:loading.remove wire:target="addPurchase">
                                        + {{ $isAr ? 'إضافة عملية شراء (+1 ختم)' : 'Add Purchase (+1 Stamp)' }}
                                    </span>
                                    <span wire:loading wire:target="addPurchase">
                                        {{ $isAr ? 'جاري الإضافة...' : 'Adding...' }}
                                    </span>
                                </button>
                            @else
                                <button type="button"
                                        wire:click="redeemReward"
                                        wire:loading.attr="disabled"
                                        class="td-btn td-btn-success"
                                        style="flex: 1; min-width: 190px;">
                                    <span wire:loading.remove wire:target="redeemReward">
                                        ✓ {{ $isAr ? 'استبدال المكافأة الآن' : 'Redeem Reward Now' }}
                                    </span>
                                    <span wire:loading wire:target="redeemReward">
                                        {{ $isAr ? 'جاري الاستبدال...' : 'Redeeming...' }}
                                    </span>
                                </button>
                            @endif

                            <button type="button"
                                    wire:click="clearCard"
                                    wire:loading.attr="disabled"
                                    class="td-btn td-btn-secondary">
                                {{ $isAr ? 'مسح بطاقة أخرى' : 'Scan Another' }}
                            </button>
                        </div>
                    </div>
                </x-filament::section>

                @if($scannedCard->transactions && $scannedCard->transactions->count() > 0)
                    <x-filament::section>
                        <x-slot name="heading">
                            {{ $isAr ? 'آخر الحركات على البطاقة' : 'Recent Card Activity' }}
                        </x-slot>

                        <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                            @foreach($scannedCard->transactions as $transaction)
                                <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; padding: 0.65rem 0; border-bottom: 1px solid rgba(148, 163, 184, 0.15);">
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.9rem;">
                                            {{ str($transaction->type)->replace('_', ' ')->title() }}
                                        </div>
                                        <div style="font-size: 0.8rem; color: #94a3b8;">
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
                    <div style="padding: 3rem 1.5rem; text-align: center;">
                        <div style="width: 56px; height: 56px; margin: 0 auto 1rem; border-radius: 16px; background: rgba(116, 62, 226, 0.14); color: #a78bfa; display: flex; align-items: center; justify-content: center;">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                                <path d="M14 14h3v3h-3z"></path>
                                <path d="M18 18h3v3h-3z"></path>
                            </svg>
                        </div>
                        <h3 style="font-size: 1.05rem; font-weight: 800; margin: 0 0 0.45rem;">
                            {{ $isAr ? 'لم يتم اختيار بطاقة بعد' : 'No Card Loaded' }}
                        </h3>
                        <p style="font-size: 0.9rem; color: #94a3b8; max-width: 380px; margin: 0 auto; line-height: 1.65;">
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

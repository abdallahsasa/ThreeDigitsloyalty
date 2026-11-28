@php
    $isAr = app()->getLocale() === 'ar';
    $shop = \Filament\Facades\Filament::getTenant();
    $bg = \App\Models\Shop::sanitizeHexColor($data['card_bg_color'] ?? null, $shop?->resolvedCardBgColor() ?? '#743EE2');
    $accent = \App\Models\Shop::sanitizeHexColor($data['card_accent_color'] ?? null, $shop?->resolvedCardAccentColor() ?? '#3B42D5');
    $text = \App\Models\Shop::sanitizeHexColor($data['card_text_color'] ?? null, $shop?->resolvedCardTextColor() ?? '#FFFFFF');
    $logoUrl = $shop?->logo_url;
    $presets = [
        ['name' => $isAr ? 'بنفسجي ThreeDigits' : 'ThreeDigits Purple', 'bg' => '#743EE2', 'accent' => '#3B42D5', 'text' => '#FFFFFF'],
        ['name' => $isAr ? 'كحلي ليلي' : 'Midnight Navy', 'bg' => '#0A0F2C', 'accent' => '#3B82F6', 'text' => '#FFFFFF'],
        ['name' => $isAr ? 'إسبريسو دافئ' : 'Warm Espresso', 'bg' => '#4A2C2A', 'accent' => '#B45309', 'text' => '#FFFFFF'],
        ['name' => $isAr ? 'زمردي للمقاهي' : 'Emerald Café', 'bg' => '#065F46', 'accent' => '#10B981', 'text' => '#FFFFFF'],
        ['name' => $isAr ? 'وردي للصالونات' : 'Salon Rose', 'bg' => '#9D174D', 'accent' => '#EC4899', 'text' => '#FFFFFF'],
    ];
@endphp

<x-filament-panels::page>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(310px, 1fr)); gap: 1.75rem; align-items: start;">
        {{-- Left: Branding Form --}}
        <div style="background: rgba(15, 23, 42, 0.03); border: 1px solid rgba(148, 163, 184, 0.25); border-radius: 18px; padding: 1.5rem;">
            <div style="margin-bottom: 1rem;">
                <div style="font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem;">
                    {{ $isAr ? 'قوالب ألوان سريعة:' : 'Quick color presets:' }}
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    @foreach($presets as $preset)
                        <button type="button"
                                wire:click="applyPreset('{{ $preset['bg'] }}', '{{ $preset['accent'] }}', '{{ $preset['text'] }}')"
                                style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.7rem; border-radius: 999px; border: 1px solid rgba(148, 163, 184, 0.35); background: transparent; font-size: 0.78rem; font-weight: 700; cursor: pointer;">
                            <span style="width: 14px; height: 14px; border-radius: 999px; background: linear-gradient(135deg, {{ $preset['bg'] }}, {{ $preset['accent'] }}); display: inline-block;"></span>
                            <span>{{ $preset['name'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <form wire:submit="save" style="display: grid; gap: 1.25rem;">
                {{ $this->form }}

                <div>
                    <x-filament::button type="submit" color="primary" size="lg">
                        {{ $isAr ? 'حفظ التصميم والشعار' : 'Save Card Design & Logo' }}
                    </x-filament::button>
                </div>
            </form>
        </div>

        {{-- Right: Live Loyalty Card Preview --}}
        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.85rem;">
            <div style="font-size: 0.88rem; font-weight: 800; color: #94a3b8;">
                {{ $isAr ? 'معاينة فورية لبطاقة العميل' : 'Live Customer Card Preview' }}
            </div>

            <div style="width: 100%; max-width: 370px; background: #ffffff; border-radius: 26px; overflow: hidden; box-shadow: 0 22px 45px rgba(1, 10, 41, 0.25); border: 1px solid rgba(148, 163, 184, 0.25); color: #010A29;">
                {{-- Header using chosen colors + Shop Logo --}}
                <div style="background: linear-gradient(135deg, {{ $bg }} 0%, {{ $accent }} 100%); color: {{ $text }}; padding: 1.35rem 1.4rem; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $shop?->name }}" style="width: 46px; height: 46px; border-radius: 13px; object-fit: cover; background: rgba(255,255,255,0.2); border: 1.5px solid rgba(255,255,255,0.4); flex-shrink: 0;">
                        @else
                            <div style="width: 46px; height: 46px; border-radius: 13px; background: rgba(255,255,255,0.2); border: 1.5px solid rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.15rem; flex-shrink: 0;">
                                {{ mb_substr($shop?->name ?? 'T', 0, 1) }}
                            </div>
                        @endif
                        <div style="min-width: 0;">
                            <div style="font-weight: 800; font-size: 1.05rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $shop?->name ?? 'Your Store' }}</div>
                            <div style="font-size: 0.78rem; opacity: 0.88;">{{ $isAr ? 'أحمد محمد · بطاقة الولاء' : 'Sara Ahmad · Loyalty Pass' }}</div>
                        </div>
                    </div>
                    <span style="padding: 0.25rem 0.65rem; border-radius: 999px; background: rgba(255,255,255,0.2); font-size: 0.7rem; font-weight: 800; white-space: nowrap;">
                        {{ $isAr ? 'قيد التجميع' : 'COLLECTING' }}
                    </span>
                </div>

                {{-- Body with custom stamp color --}}
                <div style="padding: 1.35rem;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.65rem;">
                            <span style="font-size: 0.9rem; font-weight: 800;">{{ $isAr ? 'بطاقة الأختام' : 'Stamp Card' }}</span>
                            <span style="font-size: 0.84rem; font-weight: 800; color: {{ $bg }};">4 / 6</span>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 0.4rem; margin-bottom: 0.85rem;">
                            @for($i = 1; $i <= 6; $i++)
                                <div style="aspect-ratio: 1; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 800; {{ $i <= 4 ? "background: linear-gradient(135deg, {$bg}, {$accent}); color: {$text};" : 'background: #ffffff; border: 1.5px dashed #cbd5e1; color: #94a3b8;' }}">
                                    {{ $i <= 4 ? '✓' : $i }}
                                </div>
                            @endfor
                        </div>
                        <div style="height: 6px; border-radius: 999px; background: #e2e8f0; overflow: hidden;">
                            <div style="width: 66%; height: 100%; background: linear-gradient(90deg, {{ $bg }}, {{ $accent }});"></div>
                        </div>
                    </div>

                    {{-- Co-branded Footer Inside Card: ThreeDigits Logo + Shop Logo --}}
                    <div style="margin-top: 1.1rem; padding-top: 0.85rem; border-top: 1px dashed #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                        <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.74rem; color: #64748b; font-weight: 700;">
                            <img src="{{ asset('images/logo-icon.png') }}" alt="ThreeDigits Loyalty" style="height: 18px; width: auto; display: block;">
                            <span>ThreeDigits Loyalty</span>
                        </div>
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $shop?->name }}" style="height: 20px; width: 20px; border-radius: 5px; object-fit: cover;">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

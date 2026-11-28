@php
    $isAr = app()->getLocale() === 'ar';
    $allDone = $completedCount >= $totalSteps;
@endphp

<x-filament-panels::page>
    <div style="display: grid; gap: 1.25rem;">
        @if($allDone)
            <div style="background: linear-gradient(135deg, #064E3B 0%, #059669 100%); border: 1px solid rgba(110, 231, 183, 0.45); border-radius: 18px; padding: 1.5rem; color: #ffffff;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                    <div>
                        <h2 style="margin: 0 0 0.35rem; font-size: 1.3rem; font-weight: 800;">
                            🎉 {{ $isAr ? 'اكتمل إعداد متجرك بنجاح!' : 'Your store setup is complete!' }}
                        </h2>
                        <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 0.92rem;">
                            {{ $isAr
                                ? 'جميع خطوات الإعداد مكتملة. يمكنك الآن متابعة نشاط عملائك وإدارة الأختام من لوحة التحكم.'
                                : 'All setup steps are done. Head to your dashboard to track customer visits and stamps.' }}
                        </p>
                    </div>
                    <a href="{{ $shop ? url('/shop/' . $shop->id) : url('/shop') }}"
                       style="padding: 0.65rem 1.35rem; border-radius: 999px; background: #ffffff; color: #064E3B; text-decoration: none; font-size: 0.9rem; font-weight: 800;">
                        {{ $isAr ? 'الانتقال إلى لوحة التحكم ←' : 'Go to Dashboard →' }}
                    </a>
                </div>
            </div>
        @else
            <div style="background: linear-gradient(135deg, #071033 0%, #0C1742 100%); border: 1px solid rgba(116, 62, 226, 0.35); border-radius: 18px; padding: 1.5rem; color: #f8fafc;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                    <div>
                        <h2 style="margin: 0 0 0.35rem; font-size: 1.25rem; font-weight: 800;">
                            {{ $isAr ? 'دليل إعداد متجرك' : 'Store Setup Checklist' }}
                        </h2>
                        <p style="margin: 0; color: #cbd5e1; font-size: 0.92rem;">
                            {{ $isAr
                                ? 'أكمل الخطوات التالية بالترتيب — بعد حفظ كل خطوة سيتم إعادتك تلقائياً إلى هنا لإكمال الخطوة التالية.'
                                : 'Complete each step below — after saving a step, you will automatically return here for the next one.' }}
                        </p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span style="padding: 0.4rem 0.9rem; border-radius: 999px; background: rgba(16, 185, 129, 0.16); border: 1px solid rgba(16, 185, 129, 0.35); color: #6ee7b7; font-weight: 800; font-size: 0.85rem;">
                            {{ $completedCount }} / {{ $totalSteps }}
                        </span>
                        <a href="{{ $shop ? url('/shop/' . $shop->id) : url('/shop') }}"
                           style="padding: 0.5rem 1rem; border-radius: 999px; border: 1px solid rgba(255,255,255,0.2); color: #f8fafc; text-decoration: none; font-size: 0.85rem; font-weight: 700;">
                            {{ $isAr ? 'تخطي إلى لوحة التحكم' : 'Skip to Dashboard' }}
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div style="display: grid; gap: 0.9rem;">
            @foreach($steps as $index => $step)
                @php
                    $isNext = ($nextStepIndex === $index);
                @endphp
                <div style="background: #ffffff; border: {{ $isNext ? '2px solid #743EE2' : '1px solid #e2e8f0' }}; border-radius: 16px; padding: 1.25rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; box-shadow: {{ $isNext ? '0 12px 28px -10px rgba(116, 62, 226, 0.25)' : 'none' }};">
                    <div style="display: flex; align-items: flex-start; gap: 0.9rem;">
                        <span style="width: 34px; height: 34px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0; {{ $step['done'] ? 'background: #10b981; color: #ffffff;' : ($isNext ? 'background: #743EE2; color: #ffffff;' : 'background: rgba(116, 62, 226, 0.12); color: #743EE2;') }}">
                            {{ $step['done'] ? '✓' : ($index + 1) }}
                        </span>
                        <div>
                            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;">
                                <span style="font-weight: 800; font-size: 1rem; color: #0f172a;">{{ $step['title'] }}</span>
                                @if($isNext)
                                    <span style="padding: 0.15rem 0.6rem; border-radius: 999px; background: rgba(116, 62, 226, 0.12); color: #743EE2; font-size: 0.72rem; font-weight: 800;">
                                        {{ $isAr ? 'الخطوة التالية' : 'Next Step' }}
                                    </span>
                                @elseif($step['done'])
                                    <span style="padding: 0.15rem 0.6rem; border-radius: 999px; background: rgba(16, 185, 129, 0.12); color: #059669; font-size: 0.72rem; font-weight: 800;">
                                        {{ $isAr ? 'مكتملة' : 'Completed' }}
                                    </span>
                                @endif
                            </div>
                            <div style="font-size: 0.88rem; color: #475569; margin-top: 0.22rem;">{{ $step['desc'] }}</div>
                        </div>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.55rem;">
                        @if(!empty($step['secondary_url']))
                            <a href="{{ $step['secondary_url'] }}"
                               style="padding: 0.52rem 1rem; border-radius: 999px; border: 1px solid #cbd5e1; background: #f8fafc; color: #334155; text-decoration: none; font-size: 0.82rem; font-weight: 700;">
                                {{ $step['secondary_cta'] }}
                            </a>
                        @endif

                        <a href="{{ $step['url'] }}"
                           style="padding: 0.55rem 1.2rem; border-radius: 999px; background: {{ $step['done'] ? '#f1f5f9' : '#743EE2' }}; color: {{ $step['done'] ? '#334155' : '#ffffff' }}; text-decoration: none; font-size: 0.85rem; font-weight: 700;">
                            {{ $step['cta'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>

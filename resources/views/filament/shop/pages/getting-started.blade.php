<x-filament-panels::page>
    <div style="display: grid; gap: 1.25rem;">
        <div style="background: linear-gradient(135deg, #071033 0%, #0C1742 100%); border: 1px solid rgba(116, 62, 226, 0.35); border-radius: 18px; padding: 1.5rem; color: #f8fafc;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                <div>
                    <h2 style="margin: 0 0 0.35rem; font-size: 1.25rem; font-weight: 800;">
                        {{ app()->getLocale() === 'ar' ? 'دليل إعداد متجرك' : 'Store Setup Checklist' }}
                    </h2>
                    <p style="margin: 0; color: #cbd5e1; font-size: 0.92rem;">
                        {{ app()->getLocale() === 'ar'
                            ? 'أكمل الخطوات التالية لإطلاق بطاقة الولاء الخاصة بمتجرك، أو تخطَّ هذه الصفحة وانتقل مباشرة إلى لوحة التحكم.'
                            : 'Complete these steps to launch your loyalty card, or skip straight to your dashboard anytime.' }}
                    </p>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="padding: 0.4rem 0.9rem; border-radius: 999px; background: rgba(16, 185, 129, 0.16); border: 1px solid rgba(16, 185, 129, 0.35); color: #6ee7b7; font-weight: 800; font-size: 0.85rem;">
                        {{ $completedCount }} / {{ $totalSteps }}
                    </span>
                    <a href="{{ $shop ? url('/shop/' . $shop->id) : url('/shop') }}"
                       style="padding: 0.5rem 1rem; border-radius: 999px; border: 1px solid rgba(255,255,255,0.2); color: #f8fafc; text-decoration: none; font-size: 0.85rem; font-weight: 700;">
                        {{ app()->getLocale() === 'ar' ? 'تخطي إلى لوحة التحكم' : 'Skip to Dashboard' }}
                    </a>
                </div>
            </div>
        </div>

        <div style="display: grid; gap: 0.9rem;">
            @foreach($steps as $index => $step)
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.25rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem;">
                    <div style="display: flex; align-items: flex-start; gap: 0.9rem;">
                        <span style="width: 32px; height: 32px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0; {{ $step['done'] ? 'background: #10b981; color: #ffffff;' : 'background: rgba(116, 62, 226, 0.12); color: #743EE2;' }}">
                            {{ $step['done'] ? '✓' : ($index + 1) }}
                        </span>
                        <div>
                            <div style="font-weight: 800; font-size: 1rem; color: #0f172a;">{{ $step['title'] }}</div>
                            <div style="font-size: 0.88rem; color: #475569; margin-top: 0.2rem;">{{ $step['desc'] }}</div>
                        </div>
                    </div>

                    <a href="{{ $step['url'] }}"
                       style="padding: 0.55rem 1.15rem; border-radius: 999px; background: #743EE2; color: #ffffff; text-decoration: none; font-size: 0.85rem; font-weight: 700;">
                        {{ $step['cta'] }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>

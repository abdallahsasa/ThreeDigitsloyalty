<x-filament-panels::page>
    @php $isAr = app()->getLocale() === 'ar'; @endphp

    <div style="display: grid; gap: 1.25rem;">
        {{-- Controls Bar --}}
        <x-filament::section>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 0.9rem; align-items: end;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 0.35rem;">
                        {{ $isAr ? 'ملف السجل (حسب اليوم)' : 'Log File (Day by Day)' }}
                    </label>
                    <select wire:model.live="selectedFile"
                            style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 10px; border: 1px solid rgba(148,163,184,0.35); background: transparent;">
                        @forelse($files as $file)
                            <option value="{{ $file['name'] }}">
                                {{ $file['name'] }} ({{ $file['size'] }})
                            </option>
                        @empty
                            <option value="">{{ $isAr ? 'لا توجد ملفات سجل' : 'No log files found' }}</option>
                        @endforelse
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 0.35rem;">
                        {{ $isAr ? 'مستوى السجل' : 'Level' }}
                    </label>
                    <select wire:model.live="levelFilter"
                            style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 10px; border: 1px solid rgba(148,163,184,0.35); background: transparent;">
                        <option value="all">{{ $isAr ? 'الكل' : 'All Levels' }}</option>
                        <option value="errors">{{ $isAr ? 'الأخطاء فقط (ERROR / CRITICAL)' : 'Errors Only (ERROR / CRITICAL)' }}</option>
                        <option value="warnings">{{ $isAr ? 'التحذيرات (WARNING)' : 'Warnings (WARNING)' }}</option>
                        <option value="info">{{ $isAr ? 'معلومات (INFO / DEBUG)' : 'Info / Debug' }}</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 0.35rem;">
                        {{ $isAr ? 'بحث في السجل' : 'Search' }}
                    </label>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="{{ $isAr ? 'ابحث عن خطأ أو كلمة...' : 'Search error message...' }}"
                           style="width: 100%; padding: 0.6rem 0.75rem; border-radius: 10px; border: 1px solid rgba(148,163,184,0.35); background: transparent;">
                </div>

                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button type="button"
                            wire:click="downloadSelected"
                            style="padding: 0.6rem 1rem; border-radius: 10px; border: none; background: #743EE2; color: #ffffff; font-weight: 700; font-size: 0.84rem; cursor: pointer;">
                        {{ $isAr ? 'تحميل الملف' : 'Download' }}
                    </button>

                    <button type="button"
                            wire:click="clearSelected"
                            wire:confirm="{{ $isAr ? 'هل أنت متأكد من مسح محتوى هذا الملف؟' : 'Are you sure you want to clear this log file?' }}"
                            style="padding: 0.6rem 1rem; border-radius: 10px; border: 1px solid rgba(248,113,113,0.45); background: rgba(248,113,113,0.12); color: #f87171; font-weight: 700; font-size: 0.84rem; cursor: pointer;">
                        {{ $isAr ? 'مسح السجل' : 'Clear' }}
                    </button>
                </div>
            </div>
        </x-filament::section>

        {{-- Log Entries --}}
        <x-filament::section>
            <x-slot name="heading">
                {{ $isAr ? 'أحدث السجلات (آخر 100 سجل)' : 'Recent Entries (Latest 100)' }}
            </x-slot>

            @if(empty($entries))
                <div style="padding: 2.5rem 1rem; text-align: center; color: #94a3b8;">
                    {{ $isAr ? 'لا توجد أخطاء أو سجلات مطابقة في هذا الملف.' : 'No matching log entries in this file.' }}
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                    @foreach($entries as $entry)
                        @php
                            $isError = in_array($entry['level'], ['ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY'], true);
                            $isWarn = $entry['level'] === 'WARNING';
                            $badgeBg = $isError ? 'rgba(239, 68, 68, 0.16)' : ($isWarn ? 'rgba(245, 158, 11, 0.16)' : 'rgba(59, 130, 246, 0.16)');
                            $badgeColor = $isError ? '#f87171' : ($isWarn ? '#fbbf24' : '#60a5fa');
                        @endphp
                        <div style="padding: 0.95rem 1.1rem; border-radius: 12px; border: 1px solid rgba(148,163,184,0.2); direction: ltr; text-align: left;">
                            <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.45rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="padding: 0.2rem 0.55rem; border-radius: 999px; font-size: 0.72rem; font-weight: 800; background: {{ $badgeBg }}; color: {{ $badgeColor }};">
                                        {{ $entry['level'] }}
                                    </span>
                                    <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 600;">
                                        {{ $entry['timestamp'] }} · {{ $entry['env'] }}
                                    </span>
                                </div>
                            </div>

                            <div style="font-size: 0.88rem; font-weight: 600; word-break: break-word;">
                                {{ $entry['message'] }}
                            </div>

                            @if($entry['stack'] !== '')
                                <details style="margin-top: 0.55rem;">
                                    <summary style="cursor: pointer; font-size: 0.78rem; color: #a78bfa; font-weight: 700;">
                                        Stack Trace / Context
                                    </summary>
                                    <pre style="margin: 0.5rem 0 0; padding: 0.75rem; border-radius: 8px; background: rgba(15, 23, 42, 0.75); color: #cbd5e1; font-size: 0.75rem; overflow-x: auto; white-space: pre-wrap; word-break: break-word;">{{ $entry['stack'] }}</pre>
                                </details>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>

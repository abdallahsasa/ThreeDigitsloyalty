<section class="dl-section">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ app()->getLocale() === 'ar' ? 'كيف يعمل' : 'Workflow' }}</span>
            <h2>{{ __('landing.how_it_works_title') }}</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
            @foreach([
                ['num' => '01', 'title' => __('landing.how_step_1')],
                ['num' => '02', 'title' => __('landing.how_step_2')],
                ['num' => '03', 'title' => __('landing.how_step_3')],
                ['num' => '04', 'title' => __('landing.how_step_4')],
            ] as $step)
                <div class="dl-card" style="padding: 1.75rem;">
                    <div class="dl-gold-text" style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.85rem;">{{ $step['num'] }}</div>
                    <h3 style="font-size: 1.08rem; font-weight: 700; line-height: 1.45; margin: 0; color: #f8fafc;">{{ $step['title'] }}</h3>
                </div>
            @endforeach
        </div>
    </div>
</section>

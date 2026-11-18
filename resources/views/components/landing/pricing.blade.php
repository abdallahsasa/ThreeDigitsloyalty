@props(['plans' => collect()])

<section id="pricing" class="dl-section">
    <div class="dl-container">
        <div class="dl-section-head">
            <span class="dl-eyebrow">{{ app()->getLocale() === 'ar' ? 'الخطط والأسعار' : 'Pricing' }}</span>
            <h2>{{ __('landing.pricing_title') }}</h2>
            <div style="margin: 1rem auto 0; max-width: 680px; display: inline-flex; align-items: center; gap: 0.6rem; padding: 0.7rem 1.25rem; border-radius: 999px; background: rgba(16, 185, 129, 0.14); border: 1px solid rgba(16, 185, 129, 0.38); color: #6ee7b7; font-size: 0.9rem; font-weight: 700;">
                <span>🎁</span>
                <span>{{ __('landing.pricing_free_banner') }}</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(270px, 1fr)); gap: 1.5rem; align-items: stretch;">
            @foreach($plans as $index => $plan)
                @php $featured = $index === 1; @endphp
                <div class="dl-card" style="padding: 2rem; display: flex; flex-direction: column; justify-content: space-between; {{ $featured ? 'border-color: rgba(116,62,226,0.55); background: linear-gradient(160deg, #0C1742 0%, #050C2A 100%); box-shadow: 0 20px 50px -20px rgba(116,62,226,0.28);' : '' }}">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <h3 style="font-size: 1.2rem; font-weight: 700; margin: 0; color: #f8fafc;">{{ $plan->name }}</h3>
                            @if($featured)
                                <span style="padding: 0.25rem 0.65rem; border-radius: 999px; background: rgba(116,62,226,0.16); color: #A78BFA; font-size: 0.72rem; font-weight: 700;">POPULAR</span>
                            @endif
                        </div>

                        <div style="display: flex; align-items: baseline; gap: 0.55rem; flex-wrap: wrap; margin-bottom: 0.45rem;">
                            <span style="font-size: 2.5rem; font-weight: 800; color: #10b981;">{{ __('landing.pricing_free_label') }}</span>
                            @if((float) $plan->price > 0)
                                <span style="color: #64748b; font-size: 1.05rem; font-weight: 600; text-decoration: line-through;">${{ number_format((float) $plan->price, 0) }}/{{ app()->getLocale() === 'ar' ? 'شهر' : 'mo' }}</span>
                            @endif
                        </div>

                        <div style="margin-bottom: 1.4rem;">
                            <span style="display: inline-block; padding: 0.25rem 0.65rem; border-radius: 999px; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.28); color: #6ee7b7; font-size: 0.75rem; font-weight: 700;">
                                {{ __('landing.pricing_free_until') }}
                            </span>
                        </div>

                        @if(is_array($plan->features))
                            <ul style="list-style: none; padding: 0; margin: 0 0 2rem; display: grid; gap: 0.75rem;">
                                @foreach($plan->features as $feature)
                                    <li style="display: flex; align-items: center; gap: 0.55rem; font-size: 0.92rem; color: #cbd5e1;">
                                        <span style="color: #10b981; font-weight: 800;">✓</span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <a href="{{ url('/shop/login') }}" class="{{ $featured ? 'dl-btn-gold' : 'dl-btn-ghost' }}" style="width: 100%;">
                        {{ __('landing.pricing_cta_free') }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

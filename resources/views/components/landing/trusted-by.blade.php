@props(['clients' => collect()])

<section id="clients" style="padding: 3.5rem 0; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); background: #040D30;">
    <div class="dl-container" style="text-align: center;">
        <p style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 2rem;">
            {{ __('landing.trusted_title') }}
        </p>

        @if($clients->isNotEmpty())
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(185px, 1fr)); gap: 1rem;">
                @foreach($clients as $client)
                    <a href="{{ route('shop.join', $client->slug ?: $client->id) }}" class="dl-card" style="padding: 0.9rem 1rem; display: flex; align-items: center; gap: 0.85rem; text-align: start; text-decoration: none;">
                        @if($client->logo_url)
                            <img src="{{ $client->logo_url }}" alt="{{ $client->name }}" style="width: 38px; height: 38px; border-radius: 10px; object-fit: cover; flex-shrink: 0; border: 1px solid rgba(255,255,255,0.12);">
                        @else
                            <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(116,62,226,0.14); border: 1px solid rgba(116,62,226,0.3); color: #A78BFA; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.95rem; flex-shrink: 0;">
                                {{ mb_substr($client->name, 0, 1) }}
                            </div>
                        @endif
                        <div style="min-width: 0;">
                            <div style="font-weight: 700; font-size: 0.88rem; color: #f8fafc; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $client->name }}</div>
                            <div style="font-size: 0.72rem; color: #743EE2;">
                                {{ $client->city ? $client->city . ' · ' : '' }}{{ app()->getLocale() === 'ar' ? 'سجّل الآن' : 'Join Pass →' }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.75rem;">
                @foreach(['Coffee Shops', 'Restaurants', 'Salons', 'Gyms', 'Pharmacies', 'Bakeries'] as $type)
                    <span style="padding: 0.5rem 1rem; border-radius: 999px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); color: #94a3b8; font-size: 0.85rem; font-weight: 600;">
                        {{ $type }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>
</section>

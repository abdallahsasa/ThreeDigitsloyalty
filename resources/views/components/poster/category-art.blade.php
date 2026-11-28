@props([
    'category' => 'cafe',
    'mode' => 'icon', // 'icon' | 'medallion'
    'accent' => '#8E3B20',
    'bg' => '#2C1B18',
])

@php
    $cat = strtolower(trim((string) $category));
    if (! in_array($cat, \App\Models\Shop::CATEGORIES, true)) {
        $cat = 'cafe';
    }
    $uid = 'cat-' . $cat . '-' . substr(md5($cat . $mode . uniqid('', true)), 0, 6);
@endphp

@if($mode === 'icon')
    @switch($cat)
        @case('restaurant')
            {{-- Fine Dining Cloche & Platter --}}
            <svg data-category-icon="restaurant" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <path d="M27 14C27 10.5 31 9 30 5.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                <path d="M36.5 15C36.5 11.8 40 10.5 39 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.82"/>
                <circle cx="32" cy="20" r="3.2" stroke="currentColor" stroke-width="2.3"/>
                <path d="M12 42C12 30.954 20.954 22 32 22C43.046 22 52 30.954 52 42H12Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M19 37C21.2 31.4 26.2 27.6 32 27.2" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.7"/>
                <path d="M7 42.5H57" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"/>
                <path d="M14 47.5C19 50.5 45 50.5 50 47.5" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
            </svg>
            @break

        @case('bakery')
            {{-- Artisan Croissant & Warm Aroma --}}
            <svg data-category-icon="bakery" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <path d="M28 15C27 11.2 31.2 9.5 30 5.5" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/>
                <path d="M36.5 16.5C36 13 39.5 11.5 38.5 8" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" opacity="0.8"/>
                <path d="M22 37C22 28.5 26.5 23 32 23C37.5 23 42 28.5 42 37C38.5 39.5 25.5 39.5 22 37Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M22.5 35.5C16.5 34.5 12.5 38 11 43.5C14.5 45.5 19.5 43.5 24 38.5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M41.5 35.5C47.5 34.5 51.5 38 53 43.5C49.5 45.5 44.5 43.5 40 38.5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M26.5 26.5C28 31 28 34.5 26.5 38" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M37.5 26.5C36 31 36 34.5 37.5 38" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M12 50C20 53.5 44 53.5 52 50" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
            </svg>
            @break

        @case('salon')
            {{-- Stylist Shears, Comb & Sparkles --}}
            <svg data-category-icon="salon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <circle cx="20" cy="46" r="6.5" stroke="currentColor" stroke-width="2.5"/>
                <circle cx="38" cy="46" r="6.5" stroke="currentColor" stroke-width="2.5"/>
                <path d="M24.5 41.2L44 14" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/>
                <path d="M33.5 41.2L14 14" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"/>
                <circle cx="29" cy="31" r="2.2" fill="currentColor"/>
                <path d="M49 14L50.6 18.4L55 20L50.6 21.6L49 26L47.4 21.6L43 20L47.4 18.4L49 14Z" fill="currentColor"/>
                <path d="M14 27L15 29.8L17.8 30.8L15 31.8L14 34.6L13 31.8L10.2 30.8L13 29.8L14 27Z" fill="currentColor" opacity="0.85"/>
            </svg>
            @break

        @case('fitness')
            {{-- Pro Dumbbell & Athletic Pulse --}}
            <svg data-category-icon="fitness" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <g transform="rotate(-28 32 34)">
                    <rect x="9" y="25" width="6" height="18" rx="3" stroke="currentColor" stroke-width="2.4"/>
                    <rect x="15" y="21" width="7" height="26" rx="3.2" stroke="currentColor" stroke-width="2.5"/>
                    <line x1="22" y1="34" x2="42" y2="34" stroke="currentColor" stroke-width="3.2" stroke-linecap="round"/>
                    <rect x="42" y="21" width="7" height="26" rx="3.2" stroke="currentColor" stroke-width="2.5"/>
                    <rect x="49" y="25" width="6" height="18" rx="3" stroke="currentColor" stroke-width="2.4"/>
                </g>
                <path d="M46 11L47.4 14.8L51.2 16.2L47.4 17.6L46 21.4L44.6 17.6L40.8 16.2L44.6 14.8L46 11Z" fill="currentColor"/>
            </svg>
            @break

        @case('retail')
            {{-- Luxury Boutique Bag & Sparkle --}}
            <svg data-category-icon="retail" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <path d="M16 24H48L51 52C51.2 53.7 49.9 55 48.2 55H15.8C14.1 55 12.8 53.7 13 52L16 24Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M24 28V19C24 14.582 27.582 11 32 11C36.418 11 40 14.582 40 19V28" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                <path d="M32 34L33.6 38.2L37.8 39.8L33.6 41.4L32 45.6L30.4 41.4L26.2 39.8L30.4 38.2L32 34Z" fill="currentColor"/>
            </svg>
            @break

        @case('wellness')
            {{-- Botanical Leaf & Care Heart --}}
            <svg data-category-icon="wellness" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <path d="M32 52C32 52 13 41 13 25.5C13 18.6 18.4 13.5 24.8 13.5C28.5 13.5 31.6 15.4 32 18C32.4 15.4 35.5 13.5 39.2 13.5C45.6 13.5 51 18.6 51 25.5C51 41 32 52 32 52Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                <path d="M22 31H27.5L30.5 24.5L34.2 36.5L37 31H42" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            @break

        @case('auto')
            {{-- Sleek Auto Care & Detailing Shine --}}
            <svg data-category-icon="auto" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <path d="M11 39L16.5 26.5C17.6 24 20.1 22.5 22.8 22.5H41.2C43.9 22.5 46.4 24 47.5 26.5L53 39" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                <rect x="8" y="38" width="48" height="11" rx="5" stroke="currentColor" stroke-width="2.5"/>
                <circle cx="19" cy="49" r="4.5" fill="currentColor"/>
                <circle cx="45" cy="49" r="4.5" fill="currentColor"/>
                <path d="M47 10L48.5 14L52.5 15.5L48.5 17L47 21L45.5 17L41.5 15.5L45.5 14L47 10Z" fill="currentColor"/>
                <path d="M20 12L21 14.6L23.6 15.6L21 16.6L20 19.2L19 16.6L16.4 15.6L19 14.6L20 12Z" fill="currentColor" opacity="0.82"/>
            </svg>
            @break

        @default
            {{-- Café & Specialty Coffee Cup on Saucer with Rising Aroma (Matches Reference Photo) --}}
            <svg data-category-icon="cafe" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                {{-- Rising espresso steam ribbons --}}
                <path d="M29.5 23C27 18.5 34.5 14.5 31.5 7.5" stroke="currentColor" stroke-width="2.3" stroke-linecap="round"/>
                <path d="M36.5 21.5C35 17.8 40 15 38 10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity="0.78"/>
                {{-- Saucer oval --}}
                <ellipse cx="31" cy="50.5" rx="22" ry="5.5" stroke="currentColor" stroke-width="2.5"/>
                <path d="M16 50.5C21 52.8 41 52.8 46 50.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity="0.75"/>
                {{-- Cup body --}}
                <path d="M17 28.5H45L42.2 42.5C41.2 47.2 36.9 50 31 50C25.1 50 20.8 47.2 19.8 42.5L17 28.5Z" fill="currentColor" fill-opacity="0.14" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                {{-- Coffee surface ellipse --}}
                <ellipse cx="31" cy="28.5" rx="14" ry="3.6" stroke="currentColor" stroke-width="2.3"/>
                <path d="M22 28.5C25.5 29.8 36.5 29.8 40 28.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                {{-- Cup handle --}}
                <path d="M44.2 31.5H47.5C50.8 31.5 53 33.8 53 36.8C53 40.2 50.2 42.8 46.2 42.8H42.2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
    @endswitch
@else
    {{-- MEDALLION MODE: Overhead Porcelain Saucer / Editorial Centerpiece Straddling the Poster Arch --}}
    @switch($cat)
        @case('restaurant')
            <svg data-category-medallion="restaurant" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <defs>
                    <radialGradient id="{{ $uid }}-plate" cx="45%" cy="38%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF"/>
                        <stop offset="82%" stop-color="#F5EFE6"/>
                        <stop offset="100%" stop-color="#E4D9CA"/>
                    </radialGradient>
                    <radialGradient id="{{ $uid }}-sauce" cx="42%" cy="40%" r="60%">
                        <stop offset="0%" stop-color="{{ $accent }}"/>
                        <stop offset="100%" stop-color="{{ $bg }}"/>
                    </radialGradient>
                </defs>
                <circle cx="90" cy="90" r="84" fill="url(#{{ $uid }}-plate)"/>
                <circle cx="90" cy="90" r="64" stroke="#DDD0C0" stroke-width="2"/>
                <circle cx="90" cy="90" r="54" fill="url(#{{ $uid }}-sauce)"/>
                {{-- Culinary cloche & garnish in warm ivory --}}
                <circle cx="90" cy="66" r="4.5" stroke="#FFF9F0" stroke-width="2.8"/>
                <path d="M58 98C58 80.327 72.327 66 90 66C107.673 66 122 80.327 122 98H58Z" fill="#FFF9F0" fill-opacity="0.16" stroke="#FFF9F0" stroke-width="3.2" stroke-linejoin="round"/>
                <path d="M50 100H130" stroke="#FFF9F0" stroke-width="3.5" stroke-linecap="round"/>
                <path d="M64 108C74 113 106 113 116 108" stroke="#FDE68A" stroke-width="2.8" stroke-linecap="round"/>
            </svg>
            @break

        @case('bakery')
            <svg data-category-medallion="bakery" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <defs>
                    <radialGradient id="{{ $uid }}-plate" cx="45%" cy="38%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF"/>
                        <stop offset="84%" stop-color="#F6EFE6"/>
                        <stop offset="100%" stop-color="#E5DACB"/>
                    </radialGradient>
                    <radialGradient id="{{ $uid }}-glaze" cx="42%" cy="38%" r="62%">
                        <stop offset="0%" stop-color="#D97736"/>
                        <stop offset="100%" stop-color="#7C3416"/>
                    </radialGradient>
                </defs>
                <circle cx="90" cy="90" r="84" fill="url(#{{ $uid }}-plate)"/>
                <circle cx="90" cy="90" r="64" stroke="#E0D3C3" stroke-width="2"/>
                <circle cx="90" cy="90" r="54" fill="url(#{{ $uid }}-glaze)"/>
                {{-- Layered Golden Croissant Emblem --}}
                <path d="M72 95C72 78 80 68 90 68C100 68 108 78 108 95C101 100 79 100 72 95Z" fill="#FDE68A" fill-opacity="0.22" stroke="#FFF9F0" stroke-width="3.2" stroke-linejoin="round"/>
                <path d="M73 92C62 90 54 96 52 106C58 110 67 106 75 97" stroke="#FFF9F0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M107 92C118 90 126 96 128 106C122 110 113 106 105 97" stroke="#FFF9F0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M80 74C83 83 83 90 80 97" stroke="#FFF9F0" stroke-width="2.6" stroke-linecap="round"/>
                <path d="M100 74C97 83 97 90 100 97" stroke="#FFF9F0" stroke-width="2.6" stroke-linecap="round"/>
            </svg>
            @break

        @case('salon')
            <svg data-category-medallion="salon" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <defs>
                    <radialGradient id="{{ $uid }}-vanity" cx="45%" cy="38%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF"/>
                        <stop offset="84%" stop-color="#F7F0EC"/>
                        <stop offset="100%" stop-color="#E7DAD4"/>
                    </radialGradient>
                    <radialGradient id="{{ $uid }}-inner" cx="40%" cy="35%" r="65%">
                        <stop offset="0%" stop-color="{{ $accent }}"/>
                        <stop offset="100%" stop-color="{{ $bg }}"/>
                    </radialGradient>
                </defs>
                <circle cx="90" cy="90" r="84" fill="url(#{{ $uid }}-vanity)"/>
                <circle cx="90" cy="90" r="65" stroke="#E2D3CC" stroke-width="2"/>
                <circle cx="90" cy="90" r="55" fill="url(#{{ $uid }}-inner)"/>
                <circle cx="74" cy="109" r="9.5" stroke="#FFF9F0" stroke-width="3.2"/>
                <circle cx="104" cy="109" r="9.5" stroke="#FFF9F0" stroke-width="3.2"/>
                <path d="M81 102L112 61" stroke="#FFF9F0" stroke-width="3.5" stroke-linecap="round"/>
                <path d="M97 102L66 61" stroke="#FFF9F0" stroke-width="3.5" stroke-linecap="round"/>
                <path d="M115 74L117.5 80L123.5 82.5L117.5 85L115 91L112.5 85L106.5 82.5L112.5 80L115 74Z" fill="#FDE68A"/>
            </svg>
            @break

        @case('fitness')
            <svg data-category-medallion="fitness" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <defs>
                    <radialGradient id="{{ $uid }}-disc" cx="45%" cy="38%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF"/>
                        <stop offset="84%" stop-color="#F2F4F7"/>
                        <stop offset="100%" stop-color="#DCE1E8"/>
                    </radialGradient>
                    <radialGradient id="{{ $uid }}-core" cx="40%" cy="35%" r="65%">
                        <stop offset="0%" stop-color="{{ $accent }}"/>
                        <stop offset="100%" stop-color="{{ $bg }}"/>
                    </radialGradient>
                </defs>
                <circle cx="90" cy="90" r="84" fill="url(#{{ $uid }}-disc)"/>
                <circle cx="90" cy="90" r="65" stroke="#D5DCE4" stroke-width="2"/>
                <circle cx="90" cy="90" r="55" fill="url(#{{ $uid }}-core)"/>
                <g transform="rotate(-26 90 90)">
                    <rect x="55" y="79" width="8" height="22" rx="4" stroke="#FFF9F0" stroke-width="3"/>
                    <rect x="63" y="73" width="10" height="34" rx="4" stroke="#FFF9F0" stroke-width="3.2"/>
                    <line x1="73" y1="90" x2="107" y2="90" stroke="#FFF9F0" stroke-width="4.5" stroke-linecap="round"/>
                    <rect x="107" y="73" width="10" height="34" rx="4" stroke="#FFF9F0" stroke-width="3.2"/>
                    <rect x="117" y="79" width="8" height="22" rx="4" stroke="#FFF9F0" stroke-width="3"/>
                </g>
            </svg>
            @break

        @case('retail')
            <svg data-category-medallion="retail" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <defs>
                    <radialGradient id="{{ $uid }}-ring" cx="45%" cy="38%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF"/>
                        <stop offset="84%" stop-color="#F6F1EA"/>
                        <stop offset="100%" stop-color="#E4DBD0"/>
                    </radialGradient>
                    <radialGradient id="{{ $uid }}-box" cx="40%" cy="35%" r="65%">
                        <stop offset="0%" stop-color="{{ $accent }}"/>
                        <stop offset="100%" stop-color="{{ $bg }}"/>
                    </radialGradient>
                </defs>
                <circle cx="90" cy="90" r="84" fill="url(#{{ $uid }}-ring)"/>
                <circle cx="90" cy="90" r="65" stroke="#DFD4C7" stroke-width="2"/>
                <circle cx="90" cy="90" r="55" fill="url(#{{ $uid }}-box)"/>
                <path d="M67 79H113L117 118C117.2 120.2 115.5 122 113.3 122H66.7C64.5 122 62.8 120.2 63 118L67 79Z" fill="#FFF9F0" fill-opacity="0.14" stroke="#FFF9F0" stroke-width="3.2" stroke-linejoin="round"/>
                <path d="M78 84V71C78 64.373 83.373 59 90 59C96.627 59 102 64.373 102 71V84" stroke="#FFF9F0" stroke-width="3.2" stroke-linecap="round"/>
                <path d="M90 92L92.5 98.5L99 101L92.5 103.5L90 110L87.5 103.5L81 101L87.5 98.5L90 92Z" fill="#FDE68A"/>
            </svg>
            @break

        @case('wellness')
            <svg data-category-medallion="wellness" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <defs>
                    <radialGradient id="{{ $uid }}-ceramic" cx="45%" cy="38%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF"/>
                        <stop offset="84%" stop-color="#F2F6F4"/>
                        <stop offset="100%" stop-color="#DCE5E0"/>
                    </radialGradient>
                    <radialGradient id="{{ $uid }}-herb" cx="40%" cy="35%" r="65%">
                        <stop offset="0%" stop-color="{{ $accent }}"/>
                        <stop offset="100%" stop-color="{{ $bg }}"/>
                    </radialGradient>
                </defs>
                <circle cx="90" cy="90" r="84" fill="url(#{{ $uid }}-ceramic)"/>
                <circle cx="90" cy="90" r="65" stroke="#D3DFD8" stroke-width="2"/>
                <circle cx="90" cy="90" r="55" fill="url(#{{ $uid }}-herb)"/>
                <path d="M90 118C90 118 62 102 62 79.5C62 69.5 69.8 62 79.2 62C84.6 62 89.2 64.8 90 68.5C90.8 64.8 95.4 62 100.8 62C110.2 62 118 69.5 118 79.5C118 102 90 118 90 118Z" fill="#FFF9F0" fill-opacity="0.14" stroke="#FFF9F0" stroke-width="3.2" stroke-linejoin="round"/>
                <path d="M74 88H82.5L87 78L92.5 96L97 88H106" stroke="#FDE68A" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            @break

        @case('auto')
            <svg data-category-medallion="auto" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <defs>
                    <radialGradient id="{{ $uid }}-rim" cx="45%" cy="38%" r="65%">
                        <stop offset="0%" stop-color="#FFFFFF"/>
                        <stop offset="84%" stop-color="#F1F5F9"/>
                        <stop offset="100%" stop-color="#CBD5E1"/>
                    </radialGradient>
                    <radialGradient id="{{ $uid }}-gloss" cx="40%" cy="35%" r="65%">
                        <stop offset="0%" stop-color="{{ $accent }}"/>
                        <stop offset="100%" stop-color="{{ $bg }}"/>
                    </radialGradient>
                </defs>
                <circle cx="90" cy="90" r="84" fill="url(#{{ $uid }}-rim)"/>
                <circle cx="90" cy="90" r="65" stroke="#CBD5E1" stroke-width="2"/>
                <circle cx="90" cy="90" r="55" fill="url(#{{ $uid }}-gloss)"/>
                <path d="M60 95L68 78C69.6 74.5 73.1 72.5 77 72.5H103C106.9 72.5 110.4 74.5 112 78L120 95" stroke="#FFF9F0" stroke-width="3.2" stroke-linecap="round"/>
                <rect x="56" y="94" width="68" height="15" rx="7" fill="#FFF9F0" fill-opacity="0.16" stroke="#FFF9F0" stroke-width="3.2"/>
                <circle cx="72" cy="109" r="6" fill="#FDE68A"/>
                <circle cx="108" cy="109" r="6" fill="#FDE68A"/>
                <path d="M111 56L113 61.5L118.5 63.5L113 65.5L111 71L109 65.5L103.5 63.5L109 61.5L111 56Z" fill="#FDE68A"/>
            </svg>
            @break

        @default
            {{-- Overhead Ceramic Saucer & Rosetta Latte Art Cup (Matches Reference Photo) --}}
            <svg data-category-medallion="cafe" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="width: 100%; height: 100%; display: block;">
                <defs>
                    <radialGradient id="{{ $uid }}-saucer" cx="42%" cy="35%" r="68%">
                        <stop offset="0%" stop-color="#FFFFFF"/>
                        <stop offset="78%" stop-color="#F4EEE6"/>
                        <stop offset="100%" stop-color="#DDD2C4"/>
                    </radialGradient>
                    <radialGradient id="{{ $uid }}-crema" cx="45%" cy="40%" r="58%">
                        <stop offset="0%" stop-color="#C97D42"/>
                        <stop offset="68%" stop-color="#9E5524"/>
                        <stop offset="100%" stop-color="#6B3312"/>
                    </radialGradient>
                </defs>
                {{-- Porcelain saucer --}}
                <circle cx="90" cy="90" r="84" fill="url(#{{ $uid }}-saucer)"/>
                <circle cx="90" cy="90" r="66" stroke="#E2D8CB" stroke-width="1.8"/>
                {{-- Cup handle jutting right --}}
                <rect x="136" y="83" width="23" height="14" rx="7" transform="rotate(8 136 83)" fill="#FFFFFF" stroke="#E0D5C7" stroke-width="2"/>
                {{-- Porcelain cup rim --}}
                <circle cx="90" cy="90" r="56" fill="#FFFFFF" stroke="#ECE4DA" stroke-width="3"/>
                {{-- Rich espresso crema --}}
                <circle cx="90" cy="90" r="49" fill="url(#{{ $uid }}-crema)"/>
                <circle cx="90" cy="90" r="45.5" stroke="#F8EAD8" stroke-opacity="0.38" stroke-width="2"/>
                {{-- Symmetrical Rosetta Latte Art --}}
                <path d="M90 54C84 61 84 68 90 73C96 68 96 61 90 54Z" fill="#FCF6EE"/>
                <path d="M90 73C78 68 68 73 70 82C77 83 84 80 90 75C96 80 103 83 110 82C112 73 102 68 90 73Z" fill="#FCF6EE" opacity="0.95"/>
                <path d="M90 85C75 81 64 87 67 96C75 97 83 93 90 87C97 93 105 97 113 96C116 87 105 81 90 85Z" fill="#FCF6EE" opacity="0.92"/>
                <path d="M90 98C78 95 70 101 73 109C79 110 85 106 90 100C95 106 101 110 107 109C110 101 102 95 90 98Z" fill="#FCF6EE" opacity="0.9"/>
                <path d="M90 110C82 108 77 113 80 119C84 120 88 117 90 113C92 117 96 120 100 119C103 113 98 108 90 110Z" fill="#FCF6EE" opacity="0.88"/>
                <path d="M90 54V125" stroke="#9E5524" stroke-width="1.6" stroke-linecap="round" opacity="0.55"/>
            </svg>
    @endswitch
@endif

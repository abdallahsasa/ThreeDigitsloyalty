<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public const CATEGORIES = [
        'cafe',
        'restaurant',
        'bakery',
        'salon',
        'fitness',
        'retail',
        'wellness',
        'auto',
    ];

    public static function categoryOptions(?string $locale = null): array
    {
        $isAr = ($locale ?? app()->getLocale()) === 'ar';

        return $isAr ? [
            'cafe' => 'مقهى وقهوة مختصة',
            'restaurant' => 'مطعم ومأكولات',
            'bakery' => 'مخبز وحلويات وعصائر',
            'salon' => 'صالون وحلاقة وعناية',
            'fitness' => 'نادي رياضي ولياقة',
            'retail' => 'متجر وأزياء وعطور',
            'wellness' => 'صيدلية وعيادة صحية',
            'auto' => 'مغسلة وعناية بالسيارات',
        ] : [
            'cafe' => 'Café & Coffee Shop',
            'restaurant' => 'Restaurant & Dining',
            'bakery' => 'Bakery, Desserts & Juice',
            'salon' => 'Salon, Barber & Spa',
            'fitness' => 'Gym, Fitness & Sports',
            'retail' => 'Retail, Boutique & Perfumes',
            'wellness' => 'Pharmacy & Wellness Clinic',
            'auto' => 'Car Wash & Auto Care',
        ];
    }

    public function resolvedCategory(): string
    {
        $explicit = strtolower(trim((string) $this->category));
        if ($explicit !== '' && in_array($explicit, self::CATEGORIES, true)) {
            return $explicit;
        }

        $haystack = mb_strtolower(trim($this->name . ' ' . $this->slug));

        if (preg_match('/(coffee|cafe|café|roast|espresso|brew|latte|barista|tea|matcha|قهوة|قهوه|مقهى|كافيه|محمصة|شاي)/u', $haystack)) {
            return 'cafe';
        }
        if (preg_match('/(restaurant|burger|pizza|grill|kitchen|sushi|shawarma|taco|bistro|diner|food|مطعم|برجر|شاورما|بيتزا|مشاوي|مطبخ|مأكولات)/u', $haystack)) {
            return 'restaurant';
        }
        if (preg_match('/(bakery|bake|sweet|cake|pastry|donut|cookie|choc|juice|gelato|ice\s*cream|مخبز|حلويات|كيك|عصير|عصائر|آيس|شوكولا)/u', $haystack)) {
            return 'bakery';
        }
        if (preg_match('/(salon|barber|spa|beauty|nail|hair|lash|groom|صالون|حلاقة|تجميل|سبا|عناية)/u', $haystack)) {
            return 'salon';
        }
        if (preg_match('/(gym|fitness|sport|yoga|pilates|crossfit|athletic|نادي|جيم|لياقة|رياضة)/u', $haystack)) {
            return 'fitness';
        }
        if (preg_match('/(pharmacy|clinic|dental|med|health|optical|صيدلية|عيادة|طبي|أسنان|نظارات)/u', $haystack)) {
            return 'wellness';
        }
        if (preg_match('/(car|wash|auto|detail|lube|tire|سيارات|مغسلة|تلميع)/u', $haystack)) {
            return 'auto';
        }
        if (preg_match('/(boutique|fashion|perfume|oud|flower|gift|market|بوتيك|عطور|زهور|هدايا|أزياء)/u', $haystack)) {
            return 'retail';
        }

        return 'cafe';
    }

    public function categoryPosterSubtitle(?string $locale = null): string
    {
        $isAr = ($locale ?? app()->getLocale()) === 'ar';
        $hasArabicName = (bool) preg_match('/\p{Arabic}/u', (string) $this->name);
        $cat = $this->resolvedCategory();

        // Keep clean spaced English caps under Latin brand names (like "Roast & Co / COFFEE") and Arabic under Arabic brand names or when explicitly requested
        if (! $isAr || ! $hasArabicName) {
            return match ($cat) {
                'cafe' => 'COFFEE',
                'restaurant' => 'RESTAURANT',
                'bakery' => 'BAKERY & SWEETS',
                'salon' => 'SALON & SPA',
                'fitness' => 'FITNESS & GYM',
                'retail' => 'BOUTIQUE',
                'wellness' => 'WELLNESS & CARE',
                'auto' => 'AUTO CARE',
                default => 'LOYALTY CLUB',
            };
        }

        return match ($cat) {
            'cafe' => 'مقهى وقهوة مختصة',
            'restaurant' => 'مطعم ومأكولات',
            'bakery' => 'مخبز وحلويات',
            'salon' => 'صالون وعناية',
            'fitness' => 'نادي ولياقة',
            'retail' => 'متجر وتسوق',
            'wellness' => 'صحة وعناية',
            'auto' => 'عناية بالسيارات',
            default => 'برنامج الولاء',
        };
    }

    protected static function booted(): void
    {
        static::saving(function (Shop $shop): void {
            if (empty($shop->slug)) {
                $shop->slug = static::generateUniqueSlug($shop->name, $shop->id);
            } else {
                $shop->slug = Str::slug($shop->slug) ?: static::generateUniqueSlug($shop->name, $shop->id);
            }
        });

        static::saved(function (Shop $shop): void {
            if (app()->runningUnitTests()) {
                return;
            }

            if ($shop->wasChanged(['logo', 'card_bg_color', 'card_accent_color', 'card_text_color', 'name'])) {
                try {
                    $walletService = app(\App\Services\WalletPassService::class);
                    $walletService->syncShopBrandingToGoogleClass($shop);

                    LoyaltyCard::withoutGlobalScopes()
                        ->with(['customer', 'shop', 'campaign' => fn ($q) => $q->withoutGlobalScopes()])
                        ->where('shop_id', $shop->id)
                        ->each(fn (LoyaltyCard $card) => $walletService->sendPassNotification($card));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Failed to sync Wallet passes after shop branding update', [
                        'shop_id' => $shop->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });
    }

    public function resolvedCardBgColor(): string
    {
        return self::sanitizeHexColor($this->card_bg_color, '#743EE2');
    }

    public function resolvedCardAccentColor(): string
    {
        return self::sanitizeHexColor($this->card_accent_color, '#3B42D5');
    }

    public function resolvedCardTextColor(): string
    {
        return self::sanitizeHexColor($this->card_text_color, '#FFFFFF');
    }

    public static function sanitizeHexColor(?string $color, string $fallback): string
    {
        $color = trim((string) $color);
        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
            return strtoupper($color);
        }

        return $fallback;
    }

    public static function generateUniqueSlug(?string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug((string) $name) ?: 'shop';
        $slug = $baseSlug;
        $counter = 2;

        while (
            static::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function loyaltyCampaigns(): HasMany
    {
        return $this->hasMany(LoyaltyCampaign::class);
    }

    public function loyaltyCards(): HasMany
    {
        return $this->hasMany(LoyaltyCard::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (empty($this->logo)) {
            return null;
        }

        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }

        $cleanPath = ltrim($this->logo, '/');

        if (str_starts_with($cleanPath, 'uploads/') || file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        return asset('uploads/' . $cleanPath);
    }
}
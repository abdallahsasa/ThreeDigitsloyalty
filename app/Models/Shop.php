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
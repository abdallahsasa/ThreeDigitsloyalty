<?php

namespace App\Models;

use App\Models\Scopes\ShopIsolationScope;
use App\Services\WalletPassService;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

#[ScopedBy([ShopIsolationScope::class])]
class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::saved(function (Branch $branch): void {
            if (app()->runningUnitTests()) {
                return;
            }

            if ($branch->wasChanged(['latitude', 'longitude', 'nearby_message', 'status'])) {
                try {
                    $walletService = app(WalletPassService::class);
                    LoyaltyCard::withoutGlobalScopes()
                        ->with(['customer', 'shop', 'campaign' => fn ($q) => $q->withoutGlobalScopes()])
                        ->where('shop_id', $branch->shop_id)
                        ->each(fn (LoyaltyCard $card) => $walletService->sendPassNotification($card));
                } catch (\Throwable $e) {
                    Log::warning('Failed to sync Wallet passes after branch location update', ['error' => $e->getMessage()]);
                }
            }
        });
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\ShopIsolationScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([ShopIsolationScope::class])]
class LoyaltyCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(LoyaltyCampaign::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }
}
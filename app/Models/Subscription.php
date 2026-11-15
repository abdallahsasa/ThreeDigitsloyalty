<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ShopIsolationScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ScopedBy([ShopIsolationScope::class])]
class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
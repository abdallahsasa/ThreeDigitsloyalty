<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ShopIsolationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->hasUser()) {
            $user = auth()->user();
            
            // If the user has a shop_id and is not a super admin, scope by shop_id
            // Note: Super Admins typically won't have a shop_id or we just bypass if they have the role.
            if ($user->shop_id && !$user->hasRole('Super Admin')) {
                $builder->where($model->getTable() . '.shop_id', $user->shop_id);
            }
        }
    }
}

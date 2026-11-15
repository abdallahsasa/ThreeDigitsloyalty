<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{
    public function viewAny(User $user): bool { return false; }
    public function view(User $user, Shop $shop): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $shop->id;
    }
    public function create(User $user): bool { return false; }
    public function update(User $user, Shop $shop): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $shop->id;
    }
    public function delete(User $user, Shop $shop): bool { return false; }
}
<?php

namespace App\Policies;

use App\Models\LoyaltyCard;
use App\Models\User;

class LoyaltyCardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function view(User $user, LoyaltyCard $loyaltyCard): bool
    {
        return ($user->hasRole('Shop Owner') || $user->hasRole('Cashier')) && $user->shop_id === $loyaltyCard->shop_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function update(User $user, LoyaltyCard $loyaltyCard): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $loyaltyCard->shop_id;
    }

    public function delete(User $user, LoyaltyCard $loyaltyCard): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $loyaltyCard->shop_id;
    }
}
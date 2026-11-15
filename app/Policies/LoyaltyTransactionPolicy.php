<?php

namespace App\Policies;

use App\Models\LoyaltyTransaction;
use App\Models\User;

class LoyaltyTransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function view(User $user, LoyaltyTransaction $loyaltyTransaction): bool
    {
        return ($user->hasRole('Shop Owner') || $user->hasRole('Cashier')) && $user->shop_id === $loyaltyTransaction->shop_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function update(User $user, LoyaltyTransaction $loyaltyTransaction): bool
    {
        return false; // Transactions are immutable
    }

    public function delete(User $user, LoyaltyTransaction $loyaltyTransaction): bool
    {
        return false; // Transactions are immutable
    }
}
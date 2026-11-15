<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function viewAny(User $user): bool { return false; }
    public function view(User $user, Subscription $subscription): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $subscription->shop_id;
    }
    public function create(User $user): bool { return false; }
    public function update(User $user, Subscription $subscription): bool { return false; }
    public function delete(User $user, Subscription $subscription): bool { return false; }
}
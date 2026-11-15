<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function view(User $user, Customer $customer): bool
    {
        // Actually Customer is global, but relation via LoyaltyCards defines if shop has access.
        // For simplicity, if they can viewAny, they can view.
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->hasRole('Shop Owner');
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasRole('Shop Owner');
    }
}
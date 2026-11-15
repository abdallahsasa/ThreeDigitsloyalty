<?php

namespace App\Policies;

use App\Models\LoyaltyCampaign;
use App\Models\User;

class LoyaltyCampaignPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Shop Owner');
    }

    public function view(User $user, LoyaltyCampaign $loyaltyCampaign): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $loyaltyCampaign->shop_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Shop Owner');
    }

    public function update(User $user, LoyaltyCampaign $loyaltyCampaign): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $loyaltyCampaign->shop_id;
    }

    public function delete(User $user, LoyaltyCampaign $loyaltyCampaign): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $loyaltyCampaign->shop_id;
    }
}
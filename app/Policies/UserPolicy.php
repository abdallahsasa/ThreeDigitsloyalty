<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Shop Owner');
    }

    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole('Shop Owner')
            && $user->shop_id !== null
            && $user->shop_id === $model->shop_id
            && !$model->hasRole('Super Admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Shop Owner');
    }

    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole('Shop Owner')
            && $user->shop_id !== null
            && $user->shop_id === $model->shop_id
            && !$model->hasRole('Super Admin');
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->hasRole('Shop Owner')
            && $user->shop_id !== null
            && $user->shop_id === $model->shop_id
            && !$model->hasRole('Super Admin');
    }
}

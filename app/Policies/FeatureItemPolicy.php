<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\Role;
use App\Models\User;

class FeatureItemPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAnyItemFeature(User $user): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewItemFeature(User $user, Item $item): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can create models.
     */
    public function createItemFeature(User $user): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can update the model.
    */
    public function updateItemFeature(User $user, Item $item): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteItemFeature(User $user): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restoreItemFeature(User $user, Item $item): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDeleteItemFeature(User $user, Item $item): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }
}

<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\Role;
use App\Models\User;

class ItemPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAnyItem(User $user): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewItem(User $user, Item $item): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can create models.
     */
    public function createItem(User $user): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateItem(User $user, Item $item): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteItem(User $user): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restoreItem(User $user, Item $item): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDeleteItem(User $user, Item $item): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }
}

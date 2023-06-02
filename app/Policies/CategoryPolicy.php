<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyCategory(User $user): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewCategory(User $user, Category $category): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can create models.
     */
    public function createCategory(User $user): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateCategory(User $user, Category $category): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteCategory(User $user, Category $category): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restoreCategory(User $user, Category $category): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDeleteCategory(User $user, Category $category): bool
    {
        return $user->role_id == Role::getAdminRoleId();
    }
}

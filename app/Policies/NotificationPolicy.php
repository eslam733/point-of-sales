<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class NotificationPolicy
{
    public function viewNotifications(User $user): bool
    {
        return $user->role_id == Role::getAdminRoleId() || $user->role_id == Role::getSubAdminRoleId();
    }
}

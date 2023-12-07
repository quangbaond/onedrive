<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $permission = $user->permissions()->where('permission', 'view user')->first();
        if ($user->is_admin || $permission) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        $permission = $user->permissions()->where('permission', 'view user')->first();
        if ($user->is_admin || $permission) {
            return true;
        }
        return false;

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $permission = $user->permissions()->where('permission', 'create user')->first();
        if ($user->is_admin || $permission) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        $permission = $user->permissions()->where('permission', 'update user')->first();
        if ($user->is_admin || $permission) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        $permission = $user->permissions()->where('permission', 'delete user')->first();
        if ($user->is_admin || $permission) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        $permission = $user->permissions()->where('permission', 'delete user')->first();
        if ($user->is_admin || $permission) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        $permission = $user->permissions()->where('permission', 'delete user')->first();
        if ($user->is_admin || $permission) {
            return true;
        }
        return false;
    }
}

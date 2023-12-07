<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $permission = $user->permissions()->where('permission', 'view group')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Group $group): bool
    {
        $permission = $user->permissions()->where('permission', 'view group')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $permission = $user->permissions()->where('permission', 'create group')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group): bool
    {
        $permission = $user->permissions()->where('permission', 'update group')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group): bool
    {
        $permission = $user->permissions()->where('permission', 'delete group')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Group $group): bool
    {
        $permission = $user->permissions()->where('permission', 'delete group')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Group $group): bool
    {
        $permission = $user->permissions()->where('permission', 'delete group')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }
}
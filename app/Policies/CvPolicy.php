<?php

namespace App\Policies;

use App\Models\Cv;
use App\Models\User;
use Illuminate\View\View;

class CvPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $permission = $user->permissions()->where('permission', 'view cv')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cv $cv): bool
    {

        $permission = $user->permissions()->where('permission', 'view cv')->first();
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
        $permission = $user->permissions()->where('permission', 'create cv')->first();
        $isMemory = auth()->user()->memory_limit > auth()->user()->memory_used;
        if($user->is_admin || ($permission && $isMemory)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cv $cv): bool
    {
        $permission = $user->permissions()->where('permission', 'update cv')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cv $cv): bool
    {
        $permission = $user->permissions()->where('permission', 'delete cv')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cv $cv): bool
    {
        $permission = $user->permissions()->where('permission', 'delete cv')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cv $cv): bool
    {
        $permission = $user->permissions()->where('permission', 'delete cv')->first();
        if($user->is_admin || $permission) {
            return true;
        }

        return false;
    }
}

<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can view the model.
    //  */
    // public function view(User $user, Assignment $assignment): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can create models.
    //  */
    // public function create(User $user): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can update the model.
     */
    public function changeStatus(User $user, Assignment $assignment): bool
    {
        return $assignment->user()->is($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Assignment $assignment): bool
    {
        return $this->changeStatus($user, $assignment);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Assignment $assignment): bool
    {
        return $this->changeStatus($user, $assignment);
    }

    // /**
    //  * Determine whether the user can restore the model.
    //  */
    // public function restore(User $user, Assignment $assignment): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, Assignment $assignment): bool
    // {
    //     //
    // }
}
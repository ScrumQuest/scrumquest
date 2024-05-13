<?php

namespace App\Policies;

use App\Models\BacklogItem;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BacklogItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BacklogItem $backlogItem): bool
    {
        return $backlogItem->project->users->contains($user->id) || $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, $projectId): bool
    {
        return $user->projects->contains($projectId) || $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BacklogItem $backlogItem): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BacklogItem $backlogItem): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BacklogItem $backlogItem): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BacklogItem $backlogItem): bool
    {
        //
    }
}

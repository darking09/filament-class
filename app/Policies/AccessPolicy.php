<?php

namespace App\Policies;

use App\Models\User;

class AccessPolicy
{
    protected $modelClass = '';

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->checkPermission($user, 'is_viewer');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, mixed $model): bool
    {
        return $this->checkPermission($user, 'is_viewer');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->checkPermission($user, 'is_creator');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, mixed $model): bool
    {
        return $this->checkPermission($user, 'is_updater');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, mixed $model): bool
    {
        return $this->checkPermission($user, 'is_eraser');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, mixed $model): bool
    {
        return $this->checkPermission($user, 'is_eraser');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, mixed $model): bool
    {
        return $this->checkPermission($user, 'is_eraser');
    }

    public function checkPermission(User $user, string $method): bool
    {
        $permissions = $user->role->checkIfHasPermission($this->modelClass);

        foreach ($permissions as $permission) {
            if (!$permission->$method) {
                continue;
            }

            return true;
        }

        return false;
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Subdivision;

class SubdivisionPolicy
{
    /**
     * Determine whether the user can view any subdivisions.
     */
    public function viewAny(User $user)
    {
        // Adminok és superadminok láthatják az összes alosztályt
        return $user->isAdmin || $user->is_superadmin;
    }

    /**
     * Determine whether the user can view a specific subdivision.
     */
    public function view(User $user, Subdivision $subdivision)
    {
        // Adminok és superadminok megnézhetik az alosztályokat
        return $user->isAdmin || $user->is_superadmin;
    }

    /**
     * Determine whether the user can create a subdivision.
     */
    public function create(User $user)
    {
        // Csak superadminok hozhatnak létre alosztályt
        return $user->is_superadmin || $user->role === 'superadmin';
    }

    /**
     * Determine whether the user can update a specific subdivision.
     */
    public function update(User $user, Subdivision $subdivision = null)
    {
        // Adminok és superadminok frissíthetnek alosztályokat
        return $user->isAdmin || $user->is_superadmin;
    }

    /**
     * Determine whether the user can delete a specific subdivision.
     */
    public function delete(User $user, Subdivision $subdivision)
    {
        // Csak superadminok törölhetnek alosztályokat
        return $user->is_superadmin || $user->role === 'superadmin';
    }
}
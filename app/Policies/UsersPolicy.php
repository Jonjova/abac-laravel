<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('viewAny users') ?: abort(403, 'No tienes permiso para ver el modulo usuario.');
    }

    /**
     * Determine whether the user can view the users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo('view users') ?: abort(403, 'No tienes permiso para ver usuario.');
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create users') ?: abort(403, 'No tienes permiso para crear usuario.');
    }

    /**
     * Determine whether the user can update the users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->hasPermissionTo('edit users') ?: abort(403, 'No tienes permiso para editar usuario.');
    }

    /**
     * Determine whether the user can delete the users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->hasPermissionTo('delete users') ?: abort(403, 'No tienes permiso para eliminar usuario.');
    }

    /**
     * Determine whether the user can restore the users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }

    /**
     * Determine whether the user can assign permissions to the users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function assignPermissions(User $user)
    {
        return $user->hasPermissionTo('assignPermissions users') ?: abort(403, 'No tienes permiso para asignar permisos a usuario.');
    }

    /**
     * Determine whether the user can revoke permissions from the users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function revokePermissions(User $user)
    {
        return $user->hasPermissionTo('revokePermission users') ?: abort(403, 'No tienes permiso para revocar permisos a usuario.');
    }


    /**
     * Determine whether the user can update role permissions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function updateRolePermissions(User $user)
    {
        return $user->hasPermissionTo('updateRolePermissions users') ?: abort(403, 'No tienes permiso para actualizar permisos de rol.');
    }

    /**
     * Determine whether the user can edit role permissions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function editRolePermissions(User $user)
    {
        return $user->hasPermissionTo('editRolePermissions users') ?: abort(403, 'No tienes permiso para editar permisos de rol.');
    }

}

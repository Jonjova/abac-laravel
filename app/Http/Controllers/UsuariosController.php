<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny users');
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view users');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('edit users');
        $roles = Role::all(); // Assuming you have a Role model
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('edit users');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ],[
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'roles.required' => 'Seleccione al menos un rol.',
            'roles.*.exists' => 'Uno o más roles seleccionados no son válidos.',
        ]);

        $user->update($request->only('name', 'email'));

        // Sync the roles with the user
        $user->roles()->sync($request->input('roles'));

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete users');
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Show the form for assigning permissions to the user.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function permissions(User $user)
    {
        $this->authorize('assignPermissions users');
        $permissions = Permission::all()->sortBy('name');
    
        return view('users.permissions', compact('user', 'permissions'));
    }

    /**
     * Assign permissions to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function assignPermissions(Request $request, User $user)
    {
        $this->authorize('assignPermissions users');

        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'permissions.required' => 'Seleccione al menos un permiso.',
            'permissions.*.exists' => 'Uno o más permisos seleccionados no son válidos.',
        ]);

        // Sync the permissions with the user
        $user->syncPermissions($request->input('permissions'));

        return redirect()->route('users.permissions', $user->id)->with('success', 'Permisos asignados correctamente.');
    }

    /**
     * Determine whether the user can revoke permissions from the users.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function revokePermissions(User $user, Permission $permission)
    {
        $user->revokePermissionTo($permission);
        
        return back()->with('success', 'Permiso revocado correctamente.');
    }
}

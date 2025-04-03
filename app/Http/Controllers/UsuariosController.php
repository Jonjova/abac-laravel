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

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'email.unique' => 'El correo electrónico ya está en uso.',
                'roles.required' => 'Seleccione al menos un rol.',
                'roles.*.exists' => 'Uno o más roles seleccionados no son válidos.',
            ],
        );

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

    // ... otros métodos existentes ...

    public function permissions(User $user)
    {
        $this->authorize('assignPermissions', $user);

        return view('users.permissions', $this->getPermissionsData($user));
    }

    public function assignPermissions(Request $request, User $user)
    {
        $this->authorize('assignPermissions', $user);

        $validated = $request->validate([
            'direct_permissions' => 'nullable|array', // Permisos directos (manuales)
            'direct_permissions.*' => 'exists:permissions,id',
            'inherited_permissions' => 'nullable|array', // Permisos heredados (de roles)
            'inherited_permissions.*' => 'exists:permissions,id',
        ]);

        // Combina ambos tipos de permisos (directos + heredados seleccionados)
        $allPermissions = array_unique(array_merge($validated['direct_permissions'] ?? [], $validated['inherited_permissions'] ?? []));

        // Sincroniza TODOS los permisos (directos + heredados seleccionados)
        $user->syncPermissions($allPermissions);

        return redirect()->route('users.permissions', $user)->with('success', 'Permisos actualizados correctamente.')->with($this->getPermissionsData($user));
    }

    private function getPermissionsData(User $user)
    {
        return [
            'user' => $user,
            'permissions' => Permission::orderBy('name')->get(), // Eliminado with('module') y orderBy('module_id')
            'roles' => Role::withCount('permissions')->orderBy('name')->get(),
            'userDirectPermissions' => $user->getDirectPermissions()->pluck('id')->toArray(),
            'userRoles' => $user->roles->pluck('id')->toArray(),
            'inheritedPermissions' => $user->getPermissionsViaRoles()->pluck('id')->toArray(),
            'totalPermissionsCount' => $user->getAllPermissions()->count(),
        ];
    }

    public function assignRoles(Request $request, User $user)
    {
        $this->authorize('assignPermissions', $user);

        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->syncRoles($validated['roles'] ?? []);

        return redirect()->route('users.index')->with('success', 'Roles actualizados correctamente.');
    }
    /**
     * Revoca un permiso específico (tanto directo como heredado)
     */
    public function revokePermissions(User $user, Permission $permission)
    {
        $this->authorize('revokePermission', $user);

        // Verificar si el permiso es heredado
        $isInherited = $user->hasPermissionViaRole($permission->id);

        if ($isInherited) {
            // Para permisos heredados, solo podemos revocarlos específicamente
            $user->revokePermissionTo($permission);
            $message = 'Permiso heredado revocado temporalmente (se recuperará al actualizar roles)';
        } else {
            // Para permisos directos, eliminación normal
            $user->revokePermissionTo($permission);
            $message = 'Permiso directo revocado correctamente';
        }

        return back()->with('success', $message)->with($this->getPermissionsData($user));
    }

    public function editRolePermissions(User $user, Role $role)
{
    $this->authorize('editRolePermissions', $user);
    
    return view('users.permissions', [ // Esta es tu vista principal
        'user' => $user,
        'role' => $role,
        'permissions' => Permission::orderBy('name')->get(),
        'userDirectPermissions' => $user->getDirectPermissions()->pluck('id')->toArray(),
        'inheritedPermissions' => $user->getPermissionsViaRoles()->pluck('id')->toArray(),
    ]);
}

public function editRolePermissionsModal(User $user, Role $role)
{
    $this->authorize('editRolePermissions', $user);
    
    if(request()->ajax()) {
        return view('users.partials.role_permissions_modal', [
            'user' => $user,
            'role' => $role,
            'permissions' => Permission::orderBy('name')->get(),
            'rolePermissions' => $role->permissions->pluck('id')->toArray()
        ]);
    }
    
    abort(403);
}

public function updateRolePermissions(Request $request, User $user, Role $role)
{
    $this->authorize('updateRolePermissions', $user);

    $validated = $request->validate([
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,id',
    ]);

    // Sincroniza los permisos del rol
    $role->syncPermissions($validated['permissions'] ?? []);

    // Redirección correcta
    return redirect()->route('users.permissions', $user->id)
        ->with('success', 'Permisos de rol actualizados correctamente.');
}
   
}

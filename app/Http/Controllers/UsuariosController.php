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
        ]);

        // Solo sincroniza los permisos directos proporcionados
        $allPermissions = $validated['direct_permissions'] ?? [];

        try {
            $user->syncPermissions($allPermissions);

            return back()->with([
                'type' => 'success',
                'message' => 'Permisos actualizados correctamente'
            ])->with($this->getPermissionsData($user));
        } catch (\Exception $e) {
            return back()->with([
                'type' => 'error',
                'message' => 'Error al actualizar los permisos'
            ])->with($this->getPermissionsData($user));
        }
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

        try {
            $user->syncRoles($validated['roles'] ?? []);

            return back()->with([
            'type' => 'success',
            'message' => 'Roles actualizados correctamente'
            ]);
        } catch (\Exception $e) {
            return back()->with([
            'type' => 'error',
            'message' => 'Error al actualizar los roles'
            ]);
        }
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
        try {
            // Verificar autorización
            $this->authorize('editRolePermissions', $user);
            
            // Cargar datos con eager loading
            $role->load('permissions');
            $permissions = Permission::all();
            
            // Verificar que los datos existen
            if ($permissions->isEmpty()) {
                throw new \Exception("No hay permisos definidos en el sistema");
            }
            
            return view('partials.role_permissions_modal', [
                'user' => $user,
                'role' => $role,
                'permissions' => $permissions,
                'rolePermissions' => $role->permissions->pluck('id')->toArray()
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error loading permissions: " . $e->getMessage());
            
            return response()->view('partials.error_loading_permissions', [
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editRolePermissionsModal(User $user, Role $role)
    {
        $this->authorize('editRolePermissions', $user);

        try {
           
            $permissions = Permission::all()->map(function($permission) {
                if ($permission->details) {
                    // Decodificar el JSON si es una cadena
                    $permission->details = is_string($permission->details) 
                        ? json_decode($permission->details, true) 
                        : $permission->details;
                }
                return $permission;
            });
            $rolePermissions = $role->permissions->pluck('id')->toArray();

            // Verifica que los datos existen
            if ($permissions->isEmpty()) {
                throw new \Exception('No se encontraron permisos en el sistema');
            }

            return view('partials.role_permissions_modal', [
                'user' => $user,
                'role' => $role,
                'permissions' => $permissions,
                'rolePermissions' => $rolePermissions,
            ]);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->view(
                    'partials.error_loading_permissions',
                    [
                        'error' => $e->getMessage(),
                    ],
                    500,
                );
            }

            throw $e;
        }
    }

    public function updateRolePermissions(Request $request, User $user, Role $role)
    {
        $this->authorize('updateRolePermissions', $user);

        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('users.permissions', $user->id)->with('success', 'Permisos de rol actualizados correctamente.');
    }
}

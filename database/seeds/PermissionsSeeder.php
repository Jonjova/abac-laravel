<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permisos para Posts
        Permission::insert([
            [
                'name' => 'view posts',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Posts',
                        'icon' => 'file-alt',
                        'color' => 'text-dark',
                    ],
                    'permission'=> [
                        'icon' => 'fa-eye',
                        'code' => 'POST-VW',
                        'color' => 'text-dark',
                    ],
                    'descriptions' => [
                        'es' => 'Permite ver posts',
                        'en' => 'Allows viewing posts',
                    ],
                ]),
            ],
            [
                'name' => 'create posts',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Posts',
                        'icon' => 'file-alt',
                        'color' => 'text-dark',
                    ],
                    'permission' => [
                        'icon' => 'fa-plus-circle',
                        'code' => 'POST-CR',
                        'color' => 'text-dark',
                    ],
                    'descriptions' => [
                        'es' => 'Permite crear posts',
                        'en' => 'Allows creating posts',
                    ],
                ]),
            ],
            [
                'name' => 'delete posts',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Posts',
                        'icon' => 'file-alt',
                        'color' => 'text-dark',
                    ],
                    'permission' => [
                        'icon' => 'fa-trash',
                        'code' => 'POST-DL',
                        'color' => 'text-dark',
                    ],
                    'descriptions' => [
                        'es' => 'Permite eliminar posts',
                        'en' => 'Allows deleting posts',
                    ],
                ]),
            ],
            [
                'name' => 'edit posts',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Posts',
                        'icon' => 'file-alt',
                        'color' => 'text-dark',
                    ],
                    'permission' => [
                        'icon' => 'fa-pencil-alt',
                        'code' => 'POST-ED',
                        'color' => 'text-dark',
                    ],
                    'descriptions' => [
                        'es' => 'Permite editar posts',
                        'en' => 'Allows editing posts',
                    ],
                ]),
            ],
            [
                'name' => 'viewAny posts',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Posts',
                        'icon' => 'file-alt',
                        'color' => 'text-dark',
                    ],
                    'permission' => [
                        'icon' => 'fa-cog',
                        'code' => 'POST-VWA',
                        'color' => 'text-dark',
                    ],
                    'descriptions' => [
                        'es' => 'Permite ver módulo post',
                        'en' => 'Allows viewing the post module',
                    ],
                ]),
            ],
        ]);

        // Permisos para Usuarios
        Permission::insert([
            // Permisos para ver usuarios
            [
                'name' => 'view users',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Users',
                        'icon' => 'users',
                        'color' => 'text-primary',
                    ],
                    'permission' => [
                        'icon' => 'fa-eye',
                        'code' => 'USER-VW',
                        'color' => 'text-primary',
                    ],
                    'descriptions' => [
                        'es' => 'Permite ver usuarios',
                        'en' => 'Allows viewing users',
                    ],
                ]),
            ],
            // Permisos para crear usuarios
            [
                'name' => 'create users',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Users',
                        'icon' => 'users',
                        'color' => 'text-primary',
                    ],
                    'permission' => [
                        'icon' => 'fa-plus-circle',
                        'code' => 'USER-CR',
                        'color' => 'text-primary',
                    ],
                    'descriptions' => [
                        'es' => 'Permite crear usuarios',
                        'en' => 'Allows creating users',
                    ],
                ]),
            ],
            // Permisos para eliminar usuarios
            [
                'name' => 'delete users',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Users',
                        'icon' => 'users',
                        'color' => 'text-primary',
                    ],
                    'permission' => [
                        'icon' => 'fa-trash',
                        'code' => 'USER-DL',
                        'color' => 'text-primary',
                    ],
                    'descriptions' => [
                        'es' => 'Permite eliminar usuarios',
                        'en' => 'Allows deleting users',
                    ],
                ]),
            ],
            // Permisos para editar usuarios
            [
                'name' => 'edit users',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Users',
                        'icon' => 'users',
                        'color' => 'text-primary',
                    ],
                    'permission' => [
                        'icon' => 'fa-pencil-alt',
                        'code' => 'USER-ED',
                        'color' => 'text-primary',
                    ],
                    'descriptions' => [
                        'es' => 'Permite editar usuarios',
                        'en' => 'Allows editing users',
                    ],
                ]),
            ],
            // Permisos para ver el módulo de usuarios
            [
                'name' => 'viewAny users',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Users',
                        'icon' => 'users',
                        'color' => 'text-primary',
                    ],
                    'permission' => [
                        'icon' => 'fa-cog',
                        'code' => 'USER-VWA',
                        'color' => 'text-primary',
                    ],
                    'descriptions' => [
                        'es' => 'Permite ver módulo usuario',
                        'en' => 'Allows viewing the user module',
                    ],
                ]),
            ],
            // Permisos para asignar permisos a usuarios
            [
                'name' => 'assignPermissions users',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Users',
                        'icon' => 'users',
                        'color' => 'text-primary',
                    ],
                    'permission' => [
                        'icon' => 'fa-user-check',
                        'code' => 'USER-ASGN',
                        'color' => 'text-primary',
                    ],
                    'descriptions' => [
                        'es' => 'Permite asignar permisos a usuarios',
                        'en' => 'Allows assigning permissions to users',
                    ],
                ]),
            ],
            // Permisos para revocar permisos de usuarios
            [
                'name' => 'revokePermission users',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Users',
                        'icon' => 'users',
                        'color' => 'text-primary',
                    ],
                    'permission' => [
                        'icon' => 'fa-user-times',
                        'code' => 'USER-RVK',
                        'color' => 'text-primary',
                    ],
                    'descriptions' => [
                        'es' => 'Permite revocar permisos a usuarios',
                        'en' => 'Allows revoking permissions from users',
                    ],
                ]),
            ],

            // Permisos para actualizar permisos de roles
            [
                'name' => 'updateRolePermissions users',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Users',
                        'icon' => 'users',
                        'color' => 'text-primary',
                    ],
                    'permission' => [
                        'icon' => 'fa-user-shield',
                        'code' => 'USER-URP',
                        'color' => 'text-primary',
                    ],
                    'descriptions' => [
                        'es' => 'Permite actualizar permisos de roles',
                        'en' => 'Allows updating role permissions',
                    ],
                ]),
            ],
        ]);
        // editRolePermissions
        Permission::insert([
            [
                'name' => 'editRolePermissions users',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Users',
                        'icon' => 'users',
                        'color' => 'text-primary',
                    ],
                    'permission' => [
                        'icon' => 'fa-user-edit',
                        'code' => 'USER-ERP',
                        'color' => 'text-primary',
                    ],
                    'descriptions' => [
                        'es' => 'Permite editar permisos de roles',
                        'en' => 'Allows editing role permissions',
                    ],
                ]),
            ],
        ]);
    }
}
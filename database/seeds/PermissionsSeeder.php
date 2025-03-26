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
            [
                'name' => 'view users',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => [
                        'name' => 'Users',
                        'icon' => 'users',
                        'color' => 'text-primary',
                    ],
                    'permission'=> [
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
        ]);
    }
}
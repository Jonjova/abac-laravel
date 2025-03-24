<?php

use Illuminate\Database\Seeder;
use App\User; // Asegúrate de que el namespace del modelo User sea correcto
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear el super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'), // Cambia 'password' por una contraseña segura
        ]);

        // Crear permisos
        Permission::insert([
            [
                'name' => 'view posts',
                'guard_name' => 'web',
                'details' => json_encode([
                    'module' => 'Posts',
                    'permission_code' => 'POST-VW',
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
                    'module' => 'Posts',
                    'permission_code' => 'POST-CR',
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
                    'module' => 'Posts',
                    'permission_code' => 'POST-DL',
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
                    'module' => 'Posts',
                    'permission_code' => 'POST-ED',
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
                    'module' => 'Posts',
                    'permission_code' => 'POST-VWA',
                    'descriptions' => [
                        'es' => 'Permite ver módulo post',
                        'en' => 'Allows viewing the post module',
                    ],
                ]),
            ],
        ]);
        // Crear el rol de super admin
        $superAdminRole = Role::create(['name' => 'super admin']);

        // Asignar todos los permisos al rol de super admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Asignar el rol de super admin al usuario
        $superAdmin->assignRole($superAdminRole);
    }
}

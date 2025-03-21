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
            ['name' => 'view posts', 'guard_name' => 'web'],
            ['name' => 'create posts', 'guard_name' => 'web'],
            ['name' => 'delete posts', 'guard_name' => 'web'],
            ['name' => 'viewAny posts', 'guard_name' => 'web'],
        ]);
        // Crear el rol de super admin
        $superAdminRole = Role::create(['name' => 'super admin']);
    
        // Asignar todos los permisos al rol de super admin
        $superAdminRole->givePermissionTo(Permission::all());
    
        // Asignar el rol de super admin al usuario
        $superAdmin->assignRole($superAdminRole);
    
    }
}

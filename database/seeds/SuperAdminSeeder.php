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
        $viewPosts = Permission::create(['name' => 'view posts']);
        $viewPosts = Permission::create(['name' => 'create posts']);
        $deletePosts = Permission::create(['name' => 'delete posts']);
    
        // Crear el rol de super admin
        $superAdminRole = Role::create(['name' => 'super admin']);
    
        // Asignar todos los permisos al rol de super admin
        $superAdminRole->givePermissionTo(Permission::all());
    
        // Asignar el rol de super admin al usuario
        $superAdmin->assignRole($superAdminRole);
    
        // Crear el rol de manager
        $managerRole = Role::create(['name' => 'manager']);
    
        // Asignar permisos al rol de manager
        $managerRole->givePermissionTo([$viewPosts, $deletePosts]);
    
        // Crear un usuario manager
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('managerpassword'), // Cambia 'managerpassword' por una contraseña segura
        ]);
    
        // Asignar el rol de manager al usuario
        $manager->assignRole($managerRole);
    
    }
}

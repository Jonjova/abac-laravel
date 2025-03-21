<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User; 

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $viewPosts = Permission::firstOrCreate(['name' => 'view posts']);
        $deletePosts = Permission::firstOrCreate(['name' => 'delete posts']);
        $viewAnyPosts = Permission::firstOrCreate(['name' => 'viewAny posts']);
    
        // Crear el rol de manager
        $managerRole = Role::create(['name' => 'manager']);
    
        // Asignar permisos al rol de manager
        $managerRole->givePermissionTo([$viewPosts, $deletePosts, $viewAnyPosts]);
    
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

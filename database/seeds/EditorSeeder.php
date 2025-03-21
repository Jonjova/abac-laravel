<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User; 

class EditorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear permisos si no existen
        $viewPosts = Permission::firstOrCreate(['name' => 'view posts']);
        $createPosts = Permission::firstOrCreate(['name' => 'create posts']);
        // $viewAnyPosts = Permission::firstOrCreate(['name' => 'viewAny posts']);
    
        // Crear el rol de editor
        $editorRole = Role::create(['name' => 'editor']);
    
        // Asignar permisos al rol de editor
        if (!empty($viewAnyPosts)) {
            $editorRole->givePermissionTo([$viewPosts, $createPosts]);
        }
    
        // Crear un usuario editor
        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => bcrypt('editorpassword'), // Cambia 'editorpassword' por una contraseña segura
        ]);
    
        // Asignar el rol de editor al usuario
        $editor->assignRole($editorRole);
    }
}

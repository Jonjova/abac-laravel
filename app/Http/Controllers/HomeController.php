<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        if (!$user) return redirect()->route('login');

        $permissions = $user->getAllPermissions();
        $permissionsCount = $permissions->count();

        // Agrupar permisos por módulo
        $modules = [];
        
        foreach ($permissions as $permission) {
            $details = is_array($permission->details) 
                ? $permission->details 
                : json_decode($permission->details, true);
            
            $moduleName = $details['module'] ?? 'general';
            $moduleSlug = Str::slug($moduleName);
            
            if (!isset($modules[$moduleSlug])) {
                $modules[$moduleSlug] = [
                    'name' => $moduleName,
                    'permissions' => [],
                    'icon' => $this->getModuleIcon($moduleName),
                    'color' => $this->getModuleColor($moduleName),
                    'view_permission' => 'viewAny '.strtolower($moduleName)
                ];
            }
            $modules[$moduleSlug]['permissions'][] = $permission;
        }

        return view('home', [
            'permissionsCount' => $permissionsCount,
            'modules' => $modules
        ]);
    }

    private function getModuleIcon($module)
    {
        $icons = [
            'posts' => 'file-alt',
            'users' => 'users',
            'settings' => 'cog'
        ];
        return $icons[strtolower($module)] ?? 'folder';
    }

    private function getModuleColor($module)
    {
        $colors = [
            'posts' => 'primary',
            'users' => 'success',
            'settings' => 'warning'
        ];
        return $colors[strtolower($module)] ?? 'info';
    }
}
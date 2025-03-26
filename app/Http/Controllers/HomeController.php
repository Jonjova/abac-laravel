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
            
            $moduleName = $details['module']['name'] ?? 'general';
            $moduleSlug = Str::slug($moduleName);
            $moduleIcon = $details['module']['icon'] ?? 'folder';
            $moduleColor = $details['module']['color'] ?? 'info';
            
            if (!isset($modules[$moduleSlug])) {
                $modules[$moduleSlug] = [
                    'name' => $moduleName,
                    'permissions' => [],
                    'icon' => $moduleIcon,
                    'color' => $moduleColor,
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

}
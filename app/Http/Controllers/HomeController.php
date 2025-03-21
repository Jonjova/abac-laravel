<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Permission;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login'); // Redirige si el usuario no está autenticado
        }

        $permissions = $user->getAllPermissions();
        $permissionsCount = $permissions->count();

        return view('home', compact('permissionsCount', 'permissions'));
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAbac
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission, $attribute)
    {
        $user = $request->user();

        // Verificar si el usuario tiene el permiso
        if (!$user->hasPermissionTo($permission)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        // Lógica ABAC: Evaluar atributos adicionales
        $context = [
            'time' => now()->format('H:i'), // Hora actual
            'ip' => $request->ip(), // IP del usuario
        ];

        // Ejemplo de regla ABAC: Denegar acceso fuera del horario permitido
        if ($attribute === 'time_based' && $context['time'] > '18:00') {
            abort(403, 'Acceso denegado fuera del horario permitido.');
        }

        return $next($request);
    }
}

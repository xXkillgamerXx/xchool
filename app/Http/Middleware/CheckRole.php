<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // SOLUCIÓN TEMPORAL: Usar role_id directamente sin consultar la base de datos
        // Mapeo de role_id a nombres de roles (basado en el seeder)
        $roleMapping = [
            1 => 'colegio',
            2 => 'profesor', 
            3 => 'padre',
            4 => 'estudiante',
            5 => 'suplidor',
        ];

        $userRoleId = $request->user()->role_id;
        $userRole = $roleMapping[$userRoleId] ?? null;

        // Convertir string de roles separados por comas en array
        $allowedRoles = array_map('trim', explode(',', $roles));

        // Verificar si el usuario tiene alguno de los roles permitidos
        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}

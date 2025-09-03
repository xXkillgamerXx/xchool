<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        
        // Debug: Ver qué contiene el usuario
        \Log::info('User data:', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'role_relation' => $user->role,
        ]);
        
        // Cargar la relación del rol
        $user->load('role');
        
        // Verificar que el usuario tenga un rol
        if (!$user->role) {
            \Log::error('User without role:', ['user_id' => $user->id, 'role_id' => $user->role_id]);
            return redirect()->route('profile.edit')->with('error', 'Usuario sin rol asignado');
        }
        
        $dashboardData = [
            'user' => [
                'name' => $user->name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'display_name' => $user->display_name,
                'email' => $user->email,
                'role' => $user->role->name,
                'role_display' => $user->role->display_name,
            ],
        ];

        // Agregar datos específicos según el rol
        switch ($user->role->name) {
            case 'colegio':
                $dashboardData['stats'] = [
                    'total_profesores' => User::whereHas('role', fn($q) => $q->where('name', 'profesor'))->count(),
                    'total_padres' => User::whereHas('role', fn($q) => $q->where('name', 'padre'))->count(),
                    'total_estudiantes' => User::whereHas('role', fn($q) => $q->where('name', 'estudiante'))->count(),
                    'total_usuarios' => User::count(),
                ];
                $dashboardData['recent_activities'] = [
                    'Últimos usuarios creados',
                    'Gestión de estudiantes',
                    'Asignaciones padre-estudiante',
                ];
                break;
            
            case 'profesor':
                $dashboardData['stats'] = [
                    'cursos_asignados' => 0, // TODO: Implementar cuando tengamos cursos
                    'total_estudiantes' => 0,
                ];
                $dashboardData['recent_activities'] = [
                    'Ver cursos asignados',
                    'Gestionar estudiantes',
                ];
                break;
            
            case 'padre':
                $students = $user->students;
                $dashboardData['stats'] = [
                    'hijos_asignados' => $students->count(),
                    'total_estudiantes' => $students->count(),
                ];
                $dashboardData['recent_activities'] = [
                    'Ver información de hijos',
                    'Actualizar perfil',
                ];
                break;
            
            case 'estudiante':
                $dashboardData['stats'] = [
                    'cursos_inscritos' => 0, // TODO: Implementar cuando tengamos cursos
                    'notas_promedio' => 'N/A',
                ];
                $dashboardData['recent_activities'] = [
                    'Ver mis cursos',
                    'Ver mis notas',
                ];
                break;
            
            case 'suplidor':
                $dashboardData['stats'] = [
                    'servicios_activos' => 0,
                    'contratos_vigentes' => 0,
                ];
                $dashboardData['recent_activities'] = [
                    'Gestionar servicios',
                    'Ver contratos',
                ];
                break;
            
            default:
                $dashboardData['stats'] = [];
                $dashboardData['recent_activities'] = ['Rol no configurado'];
                break;
        }

        return Inertia::render('Dashboard', $dashboardData);
    }
}

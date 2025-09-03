<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

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
                    'total_profesores' => 25,
                    'total_estudiantes' => 450,
                    'total_padres' => 380,
                    'total_cursos' => 15,
                ];
                $dashboardData['recent_activities'] = [
                    'Nuevo profesor registrado',
                    'Reporte de asistencia generado',
                    'Pago de matrícula recibido',
                ];
                break;

            case 'profesor':
                $dashboardData['stats'] = [
                    'total_profesores' => 25,
                    'total_estudiantes' => 450,
                    'total_padres' => 380,
                    'total_cursos' => 15,
                ];
                $dashboardData['recent_activities'] = [
                    'Evaluación de matemáticas calificada',
                    'Asistencia del día registrada',
                    'Comunicado enviado a padres',
                ];
                break;

            case 'padre':
                $dashboardData['stats'] = [
                    'hijos_registrados' => 2,
                    'notas_promedio' => '8.5',
                    'asistencia_hijos' => '95%',
                    'comunicados_recibidos' => 5,
                ];
                $dashboardData['recent_activities'] = [
                    'Nota de matemáticas recibida',
                    'Reporte de asistencia disponible',
                    'Pago de matrícula confirmado',
                ];
                break;

            case 'estudiante':
                $dashboardData['stats'] = [
                    'nota_promedio' => '8.7',
                    'asistencia' => '96%',
                    'cursos_inscritos' => 6,
                    'tareas_pendientes' => 2,
                ];
                $dashboardData['recent_activities'] = [
                    'Nota de historia recibida: 9.0',
                    'Tarea de ciencias entregada',
                    'Asistencia del día confirmada',
                ];
                break;

            case 'suplidor':
                $dashboardData['stats'] = [
                    'servicios_activos' => 3,
                    'facturas_pendientes' => 2,
                    'total_contratos' => 5,
                    'calificacion_servicio' => '4.8/5.0',
                ];
                $dashboardData['recent_activities'] = [
                    'Factura de limpieza enviada',
                    'Servicio de mantenimiento completado',
                    'Nuevo contrato firmado',
                ];
                break;

            default:
                // Rol no reconocido
                $dashboardData['stats'] = [];
                $dashboardData['recent_activities'] = ['Rol no configurado'];
                break;
        }

        return Inertia::render('Dashboard', $dashboardData);
    }
}
